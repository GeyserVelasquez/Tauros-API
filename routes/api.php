<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

use App\Http\Controllers\AbortController;
use App\Http\Controllers\BatchController;
use App\Http\Controllers\BatchMovementController;
use App\Http\Controllers\LivestockController;
use App\Http\Controllers\AbortTypeController;
use App\Http\Controllers\BirthTypeController;
use App\Http\Controllers\BreedController;
use App\Http\Controllers\ClassificationController;
use App\Http\Controllers\ColorController;
use App\Http\Controllers\EmbrionExtractionTypeController;
use App\Http\Controllers\EntryCauseController;
use App\Http\Controllers\ExtractionTypeController;
use App\Http\Controllers\GrowthTypeController;
use App\Http\Controllers\HerdController;
use App\Http\Controllers\ImageController;
use App\Http\Controllers\MilkingController;
use App\Http\Controllers\MilkingTypeController;
use App\Http\Controllers\NewbornTypeController;
use App\Http\Controllers\OutcomeController;
use App\Http\Controllers\OutcomeTypeController;
use App\Http\Controllers\OwnerController;
use App\Http\Controllers\ProductMovementTypeController;
use App\Http\Controllers\ProductTypeController;
use App\Http\Controllers\ResultController;
use App\Http\Controllers\RevisionController;
use App\Http\Controllers\RevisionTypeController;
use App\Http\Controllers\ServiceTypeController;
use App\Http\Controllers\StateController;
use App\Http\Controllers\SupplyController;
use App\Http\Controllers\SupplyTypeController;
use App\Http\Controllers\TeasingController;
use App\Http\Controllers\TechniqueController;

require __DIR__.'/auth.php';

Route::middleware(['auth:sanctum'])->group(function () {

    Route::get('/user', function (Request $request) {
        return $request->user();
    });

    Route::apiResource('aborts', AbortController::class);
    Route::apiResource('abort-types', AbortTypeController::class);
    Route::apiResource('birth-types', BirthTypeController::class);
    Route::apiResource('breeds', BreedController::class);
    Route::apiResource('batches', BatchController::class);
    Route::apiResource('batch-movements', BatchMovementController::class);
    Route::apiResource('classifications', ClassificationController::class);
    Route::apiResource('colors', ColorController::class);
    Route::apiResource('embrion-extraction-types', EmbrionExtractionTypeController::class);
    Route::apiResource('entry-causes', EntryCauseController::class);
    Route::apiResource('extraction-types', ExtractionTypeController::class);
    Route::apiResource('growth-types', GrowthTypeController::class);
    Route::apiResource('herds', HerdController::class);
    Route::apiResource('images', ImageController::class);
    Route::apiResource('milking-types', MilkingTypeController::class);
    Route::apiResource('milkings', MilkingController::class);
    Route::apiResource('livestock', LivestockController::class);
    Route::apiResource('newborn-types', NewbornTypeController::class);
    Route::apiResource('outcomes', OutcomeController::class);
    Route::apiResource('outcome-types', OutcomeTypeController::class);
    Route::apiResource('owners', OwnerController::class);
    Route::apiResource('product-movement-types', ProductMovementTypeController::class);
    Route::apiResource('product-types', ProductTypeController::class);
    Route::apiResource('results', ResultController::class);
    Route::apiResource('revisions', RevisionController::class);
    Route::apiResource('revision-types', RevisionTypeController::class);
    Route::apiResource('service-types', ServiceTypeController::class);
    Route::apiResource('states', StateController::class);
    Route::apiResource('supplies', SupplyController::class);
    Route::apiResource('supply-types', SupplyTypeController::class);
    Route::apiResource('techniques', TechniqueController::class);
    Route::apiResource('teasings', TeasingController::class);

});
