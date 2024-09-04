<?php

namespace App\Http\Controllers;

use App\Models\Amendment;
use http\Env\Response;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Log;
use function PHPUnit\Framework\isEmpty;

class AmendmentController extends Controller
{
    /**
     * Retrieves all the amended tasks within a given set of weeks that haven't been deleted
     *
     * @param  array      $viewDates Refers to the dates being viewed on the scheduler
     * @return array|null
     */
    public static function retrieveAmendments(array $viewDates): array|null
    {
        if(empty($viewDates)) {
            return null;
        }

        try{
            $objectivesForWeeks = Amendment::where("user_fid", Auth::id())->where(function($query) use ($viewDates) { $query->wherein("task_due_date", $viewDates);})->get();

            return $objectivesForWeeks->toArray();
        } catch(\Exception $err) {
            Log::error("Failed to retrieve objectives for view: ". $err->getMessage());

            return null;
        }
    }

    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        //
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        //
    }

    /**
     * Display the specified resource.
     */
    public function show(Amendment $accomplishments)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Amendment $accomplishments)
    {
        //
    }

    /**
     * Updates either the accomplished column of tasks - or the edited_time/task/description fields upon form submission
     *
     * @param  Request      $request                       Refers to the data sent from ticking/unticking objectives
     * @param  array|string $updateSingleRepeatTaskDetails Refers to the data submitted from the edit form
     * @return array
     */
    public static function update(Request $request, array|string $updateSingleRepeatTaskDetails = []): array
    {
        //if youre wondering why i used array|string for the $updateSingleRepeatTaskDetails, its because the axios request
        //kept on sending some string as the second param - once i added it to this method. im not sure why. I assumed
        //that it wouldve been empty, since my axios has only sent data to fill the request object. Tried debugging but
        //im not sure whats up cos it seems to crash from ControllerDispatcher.php. Used this as quick fix

        if(!(empty($updateSingleRepeatTaskDetails) || is_string($updateSingleRepeatTaskDetails))){
            try{
                 $amendment = Amendment::updateOrCreate(
                    [
                        "user_fid"      => Auth::id(),
                        "objective_fid" => $updateSingleRepeatTaskDetails["objId"],
                        "task_due_date" => $updateSingleRepeatTaskDetails["taskDate"]
                    ],
                    [
                        "accomplished" => 0,
                        "deleted"      => 1
                    ]
                );

                return ["message" => $amendment, "statusCode" => 204];
            } catch (\Exception $e){
                Log::error("Failed to updated edited fields of amendment" . $e->getMessage());

                return ["message" => $e->getMessage(), "statusCode" => 500];
            }
        }

        $validatedReq = $request->validate([
            "checked"     => "required|bool",
            "objectiveId" => "required|int",
            "dateDue"     => "required|string",
        ]);

        try{
            $amendment = Amendment::updateOrCreate([
                "user_fid"      => Auth::id(),
                "objective_fid" => $validatedReq["objectiveId"],
                "task_due_date" => $validatedReq["dateDue"]
            ],
                ["accomplished"  => $validatedReq["checked"]]
            );

            return ["message" => $amendment, "statusCode" => 204];
        } catch(\Exception $e) {
            Log::error("Failed to update/create amendment: " . $e->getMessage());

            return ["message" => $e->getMessage(), "statusCode" => 500];
        }
    }

    /**
     * Handles deletion of amendment rows as well as the updating of the "deleted" column for single repeating tasks
     *
     * @param  int     $objId  Refers to the id of the objective
     * @param  string  $date   Refers to the date due of the objective
     * @param  bool    $single Refers to whether or not a single instance is being deleted
     * @param  bool    $repeat Refers to whether an objective is repeating or not
     * @return array
     */
    public static function destroy(int $objId, string $date, bool $single, bool $repeat): array
    {
        if($single && $repeat){
            try{
                $amendment = Amendment::updateOrCreate([
                    "user_fid"      => Auth::id(),
                    "objective_fid" => $objId,
                    "task_due_date" => $date,
                    "accomplished"  => 0
                ],
                    ["deleted" => 1]
                );

                return ["message" => $amendment, "statusCode" => 204];
            } catch(\Exception $e){
                Log::error("Failed to update delete column of amendment: " . $e->getMessage());

                return ["message" => $e->getMessage(), "statusCode" => 500];
            }

        }

        try{
            $deletion = Amendment::where("objective_fid", $objId)->where("user_fid", Auth::id())->delete();

            return ["message" => $deletion, "statusCode" => 200];
        }catch (\Exception $e){
            Log::error("Failed to delete amendment: " . $e->getMessage());

            return ["message" => $e->getMessage(), "statusCode" => 500];
        }

    }
}
