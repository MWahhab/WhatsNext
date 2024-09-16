<?php

namespace App\Http\Controllers;

use App\Models\Amendment;
use App\Models\Objective;
use App\Utilities\DaysOfWeek;
use Carbon\Carbon;
use http\Env\Response;
use Illuminate\Contracts\View\Factory;
use Illuminate\Contracts\View\View;
use Illuminate\Foundation\Application;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use PhpParser\Node\Expr\Array_;
use function Laravel\Prompts\table;
use function PHPUnit\Framework\isEmpty;

class ObjectiveController extends Controller
{

    /**
     * Retrieves the objectives set for the next two weeks and sends them over to the view
     */
    public function index(string $currentDate = null): View|RedirectResponse|array
    {
        $carbonCurrentDate = $currentDate ? Carbon::createFromFormat("d/m/Y", $currentDate) : Carbon::today();

        $viewDates = self::retrieveViewDates($carbonCurrentDate);

        if(empty($viewDates)){
            return redirect()->back()->with("Error: ", "date requested is before today! Cannot fetch data");
        }

        $queryDates = array_map(function($carbonDate) {return $carbonDate->format("d/m/Y");}, $viewDates);

        try{
            $objectivesForWeeks = Objective::where("user_fid", Auth::id())->where(function($query) use ($queryDates) {
                $query->wherein("date_due", $queryDates)->orWhere("repeat", 1);
            })->orderBy("time_due")->get();

        } catch(\Exception $err) {
            Log::error("Failed to retrieve objectives for view: ". $err->getMessage());
        }

        if(!isset($objectivesForWeeks)) {
            return redirect()->back()->with("Error ", "Failed to retrieve objectives for view");
        }

        $accomplishments = AmendmentController::retrieveAmendments($queryDates);

        if(is_null($accomplishments)){
            return redirect()->back()->with("Error: ", "Viewdates not provided in order to retrieve accomplishments!");
        }

        $datesWithObjectives = self::matchDates($viewDates, $objectivesForWeeks->toArray(), $accomplishments);

        if(empty($datesWithObjectives)){
            redirect()->back()->with("Error: ", "Failed to match dates to objectives");
        }

        return $currentDate ?
            $datesWithObjectives : view("objectives.index", compact("datesWithObjectives"));
    }

    /**
     * Retrieves the dates for the 2 weeks from the date selected
     *
     * @param  Carbon $startingDate Refers to the first day of the first week
     * @return array
     */
    private static function retrieveViewDates(Carbon $startingDate): array
    {
        if($startingDate->isBefore(Carbon::today())) {
            return [];
        }

        $endDate = $startingDate->copy()->addDays(13);

        $datesInTwoWeeks = [];

        while ($startingDate->lessThanOrEqualTo($endDate)) {
            $datesInTwoWeeks[] = $startingDate->copy();
            $startingDate->addDay();
        }

        return $datesInTwoWeeks;
    }

    /**
     * Takes the dates being viewed; the tasks set within those dates and combines them
     *
     * @param  array $viewDates  Refers to the dates being viewed on the scheduler
     * @param  array $tasks      Refers to the tasks set for those days
     * @param  array $amendments Refers to the amendments set for those days
     * @return array
     */
    private static function matchDates(array $viewDates, array $tasks, array $amendments): array
    {
        if(empty($viewDates)) {
            return [];
        }

        $finalisedViewDates = [];

        for($i = 0; $i < count($viewDates); $i++) {
            $viewDateFormattedDate = $viewDates[$i]->format("d/m/Y");
            $viewDateDay           = $i<7 ? $viewDates[$i]->format("l") : strtolower($viewDates[$i]->format("l"));

            $finalisedViewDates[$viewDateDay]["date"] = $viewDateFormattedDate;

            for($j = 0; $j < count($tasks); $j++) {
                if($viewDateFormattedDate == $tasks[$j]["date_due"] && $tasks[$j]["repeat"] !== 1) {
                    $finalisedTask = self::assignMatchedDayDetails($viewDateFormattedDate, $tasks[$j], $amendments);

                    if(is_null($finalisedTask)){
                        return [];
                    }

                    !(empty($finalisedTask)) && $finalisedViewDates[$viewDateDay]["objectives"][$j] = $finalisedTask;

                    continue;
                }

                if($tasks[$j]["repeat"] !== 1) {
                    continue;
                }

                $carbonDateDue = Carbon::createFromFormat("d/m/Y", $tasks[$j]["date_due"]);

                if(isset($tasks[$j][strtolower($viewDateDay)])) {
                    if($viewDates[$i]->setTime(0, 0)->isBefore($carbonDateDue->setTime(0, 0))){
                        continue;
                    }

                    $finalisedTask = self::assignMatchedDayDetails($viewDateFormattedDate, $tasks[$j], $amendments);

                    if(is_null($finalisedTask)){
                        return [];
                    }

                    !(empty($finalisedTask)) && $finalisedViewDates[$viewDateDay]["objectives"][$j] = $finalisedTask;

                    continue;
                }

                $carbonDateDay = self::convertDateToDay($tasks[$j]["date_due"]);

                if(!$carbonDateDay){
                    return [];
                }

                if (strtolower($viewDateDay) == strtolower($carbonDateDay) && $tasks[$j]["repeat_interval"]) {
                    $differenceInWeeks =
                        $carbonDateDue->setTime(0, 0)->diffInWeeks($viewDates[$i]->setTime(0, 0));

                    $finalisedTask = $differenceInWeeks % $tasks[$j]["repeat_interval"] === 0 ?
                        self::assignMatchedDayDetails($viewDateFormattedDate, $tasks[$j], $amendments) : [];

                    if(is_null($finalisedTask)){
                        return [];
                    }

                    !(empty($finalisedTask)) && $finalisedViewDates[$viewDateDay]["objectives"][$j] = $finalisedTask;
                }

            }

        }

        return $finalisedViewDates;
    }

    /**
     * Takes the relevant details of a task matched with a date on the chosen weeks, then assigns it to a finalised array
     *
     * @param  string     $date            Refers to the date being matched to
     * @param  array      $taskDetails     Refers to an array containing the details of the task
     * @param  array      $accomplishments Refers to the accomplishments for the time frame chosen
     * @return array|null
     */
    private static function assignMatchedDayDetails(string $date, array $taskDetails, array $accomplishments): array|null
    {
        if(empty($taskDetails) || !isset(
            $taskDetails["task"], $taskDetails["description"], $taskDetails["time_due"], $taskDetails["id"]
            )) {
            return null;
        }

        $task = [
            "task"        => $taskDetails["task"],
            "description" => $taskDetails["description"],
            "time_due"    => $taskDetails["time_due"],
            "obj_id"      => $taskDetails["id"],
            "repeat"      => $taskDetails["repeat"]
        ];

        if(empty($accomplishments)){
            return $task;
        }

        foreach($accomplishments as $accomplishment) {
            if($accomplishment["task_due_date"] !== $date){
                continue;
            }

            if($taskDetails["id"] !== $accomplishment["objective_fid"]){
                continue;
            }

            if($accomplishment["deleted"] == 1){
                return [];
            }

            $accomplishment["accomplished"] == 1 && $task["accomplished"] = "yes";
            break;
        }


        return $task;
    }

    /**
     * Takes the new initial view date that the user's selected - and passes it onto the index method to display the new
     * view
     *
     * @param  Request      $request Refers to the new initial view date
     * @return JsonResponse
     */
    public function retrieveNewObjectives(Request $request): JsonResponse
    {
        $validatedRequest = $request->validate([
            "selectedDate" => "string|required"
        ]);

        return response()->json($this->index($validatedRequest["selectedDate"]), 200);
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create(Request $request)
    {
        //
    }

    /**
     * Stores a new objective in the database
     *
     * @param  Request      $request Refers to the data passed to the method
     * @return JsonResponse
     */
    public function store(Request $request): JsonResponse
    {
        $validatedRequest = $request->validate(
            [
                "task"        => "required|string",
                "description" => "required|string",
                "repeat"      => "required|string",
                "date_due"    => "required|string",
                "time_due"    => "required|string",
                "monday"      => "nullable|string", // Nullable days of the week
                "tuesday"     => "nullable|string",
                "wednesday"   => "nullable|string",
                "thursday"    => "nullable|string",
                "friday"      => "nullable|string",
                "saturday"    => "nullable|string",
                "sunday"      => "nullable|string",
            ]
        );

        $validatedRequest["repeat"]          = $validatedRequest["repeat"] == "on" ? 1 : 0;
        $validatedRequest["repeat_interval"] = $request->input("interval");
        $validatedRequest["user_fid"]        = Auth::id();

        try{
            $insertedRow = Objective::create($validatedRequest);

            $insertedRow["obj_id"] = $insertedRow->id;

            return response()->json($insertedRow, 201);
        } catch (\Exception $error) {
            Log::error("Issue creating objective: " . $error->getMessage());

            return response()->json(["error" => "Failed to create objective row"], 500);
        }

    }

    /**
     * Retrieves the day of the date that's been passed
     *
     * @param  string $dateString Refers to the date that's being converted into a day
     * @return string
     */
    private static function convertDateToDay(string $dateString): string
    {
        if($dateString == "") {
            return "";
        }

        return Carbon::createFromFormat("d/m/Y", $dateString)->format("l");
    }

    /**
     * Display the specified resource.
     */
    public function show(Objective $objective)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Objective $objective)
    {
        //
    }

    /**
     * Retrieves all repeating tasks, to be sent to the view
     *
     * @return JsonResponse
     */
    public function retrieveRepeatObjectives(): JsonResponse
    {
        try{
            $repeatObjectives =
                Objective::where("user_fid", Auth::id())->where("repeat", 1)->orderBy("time_due")->get()->toArray();

            return \response()->json($repeatObjectives, 200);
        } catch(\Exception $exception){
            return \response()->json($exception->getMessage(), 500);
        }
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, Objective $objective): JsonResponse
    {
        $validateReq = $request->validate(
            [
                "newTime"    => "required|string",
                "newTask"    => "required|string",
                "newDesc"    => "required|string",
                "taskDate"   => "required|string",
                "repeatTask" => "required|boolean",
                "singleTask" => "required|boolean"
            ]
        );

        $validateReq["objId"] = $objective->id;

        if($validateReq["singleTask"] && $validateReq["repeatTask"]){
            try{
                $newObj = Objective::create([
                    "user_fid"    => Auth::id(),
                    "repeat"      => 0,
                    "task"        => $validateReq["newTask"],
                    "description" => $validateReq["newDesc"],
                    "date_due"    => $validateReq["taskDate"],
                    "time_due"    => $validateReq["newTime"]
                ]);

                $updateResult = AmendmentController::update($request, $validateReq);

                return response()->json([$newObj, $updateResult["message"]], $updateResult["statusCode"]);
            } catch (\Exception $exception){
                Log::error("Failed to update single repeating edited task: " . $exception->getMessage());

                return \response()->json($exception, 500);
            }
        }

        try{
            $updatedObj = $objective->update([
                "time_due"    => $validateReq["newTime"],
                "task"        => $validateReq["newTask"],
                "description" => $validateReq["newDesc"]
            ]);

            return \response()->json($updatedObj, 204);
        }catch(\Exception $e){
            Log::error("Failed to update edited task: " . $e->getMessage());

            return \response()->json("Failed to update edited task: ", 500);
        }
    }

    /**
     * Handles deletion of objectives
     *
     * @param  Objective    $objective Refers to the objective instance
     * @return JsonResponse
     */
    public function destroy(Objective $objective): JsonResponse
    {
        $repeatTask = filter_var(\request()->query("repeatTask"), FILTER_VALIDATE_BOOLEAN);
        $singleTask = filter_var(\request()->query("singleTask"), FILTER_VALIDATE_BOOLEAN);
        $taskDate   = \request()->query("taskDate");

        $amendmentDeletion = AmendmentController::destroy($objective->id, $taskDate , $singleTask, $repeatTask);

        if($amendmentDeletion["statusCode"] == 500){
            return response()->json($amendmentDeletion["message"], $amendmentDeletion["statusCode"]);
        }

        if($singleTask && $repeatTask){
            return \response()->json("Successful deletion of single objective ", 204);
        }

        try{
            $objective->delete();

            return \response()->json("Successful deletion of objective(s) ", 204);
        }catch (\Exception $e){
            Log::error("Failed to delete objective: " . $e->getMessage());

            return \response()->json("Failed to delete objective: " . $e->getMessage(), 500);
        }

    }
}
