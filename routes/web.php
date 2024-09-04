<?php

use App\Http\Controllers\{
    ObjectiveController,
    ProfileController,
    AmendmentController
};
use Illuminate\Support\Facades\Route;

Route::get('/', function () {
    return view('welcome');
});

Route::get('/dashboard', [ObjectiveController::class, "index"])->middleware(['auth', 'verified'])->name('dashboard');

Route::middleware('auth')->group(function () {
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');
});

Route::post("objective/objectivesRetrieval", [ObjectiveController::class, "retrieveNewObjectives"])
    ->name("objective.retrieve");
Route::get("/objective/repeatObjectivesRetrieval", [ObjectiveController::class, "retrieveRepeatObjectives"])
    ->name("objective.retrieveRepeatObjectives");

Route::resource("objective", ObjectiveController::class)->only(["store", "show", "update", "destroy"]);
Route::resource("amendment", AmendmentController::class);

require __DIR__.'/auth.php';
