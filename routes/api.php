<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

use App\Http\Controllers\AbortController;
use App\Http\Controllers\BatchController;
use App\Http\Controllers\BatchMovementController;
use App\Http\Controllers\BirthController;
use App\Http\Controllers\CertificateController;
use App\Http\Controllers\LivestockController;
use App\Http\Controllers\AbortTypeController;
use App\Http\Controllers\BirthTypeController;
use App\Http\Controllers\BreedController;
use App\Http\Controllers\ClassificationController;
use App\Http\Controllers\ClinicDiagnosticController;
use App\Http\Controllers\ClinicHistoryController;
use App\Http\Controllers\EmbrionBatchController;
use App\Http\Controllers\ClinicalTreatmentController;
use App\Http\Controllers\ColorController;
use App\Http\Controllers\CommentController;
use App\Http\Controllers\EmbrionExtractionTypeController;
use App\Http\Controllers\EntryCauseController;
use App\Http\Controllers\ExtractionController;
use App\Http\Controllers\ExtractionTypeController;
use App\Http\Controllers\GrowthController;
use App\Http\Controllers\GrowthTypeController;
use App\Http\Controllers\HerdController;
use App\Http\Controllers\ImageController;
use App\Http\Controllers\MilkingController;
use App\Http\Controllers\MilkingTypeController;
use App\Http\Controllers\MovementKardexController;
use App\Http\Controllers\NewbornController;
use App\Http\Controllers\NewbornTypeController;
use App\Http\Controllers\OutcomeController;
use App\Http\Controllers\OutcomeTypeController;
use App\Http\Controllers\OwnerController;
use App\Http\Controllers\ProductController;
use App\Http\Controllers\ProductMovementController;
use App\Http\Controllers\ProductTypeController;
use App\Http\Controllers\RevisionController;
use App\Http\Controllers\RevisionTypeController;
use App\Http\Controllers\SemenBatchController;
use App\Http\Controllers\ServiceController;
use App\Http\Controllers\ServiceTypeController;
use App\Http\Controllers\StateController;
use App\Http\Controllers\SupplyController;
use App\Http\Controllers\SupplyMovementController;
use App\Http\Controllers\SupplyTypeController;
use App\Http\Controllers\TeasingController;
use App\Http\Controllers\TechnicianController;

require __DIR__.'/auth.php';

Route::middleware(['auth:sanctum'])->group(function () {

    Route::get('/user', function (Request $request) {
        return $request->user();
    });

    Route::apiResource('aborts', AbortController::class);
    Route::apiResource('births', BirthController::class);
    Route::apiResource('abort-types', AbortTypeController::class);
    Route::apiResource('birth-types', BirthTypeController::class);
    Route::apiResource('breeds', BreedController::class);
    Route::apiResource('certificates', CertificateController::class);
    Route::apiResource('batches', BatchController::class);
    Route::apiResource('batch-movements', BatchMovementController::class);
    Route::apiResource('classifications', ClassificationController::class);
    Route::apiResource('clinic-diagnostics', ClinicDiagnosticController::class);
    Route::apiResource('clinic-histories', ClinicHistoryController::class);
    Route::apiResource('embrion-batches', EmbrionBatchController::class);
    Route::apiResource('clinical-treatments', ClinicalTreatmentController::class);
    Route::apiResource('colors', ColorController::class);
    Route::apiResource('comments', CommentController::class);
    Route::apiResource('embrion-extraction-types', EmbrionExtractionTypeController::class);
    Route::apiResource('entry-causes', EntryCauseController::class);
    Route::apiResource('extractions', ExtractionController::class);
    Route::apiResource('growths', GrowthController::class);
    Route::apiResource('extraction-types', ExtractionTypeController::class);
    Route::apiResource('growth-types', GrowthTypeController::class);
    Route::apiResource('herds', HerdController::class);
    Route::apiResource('images', ImageController::class);
    Route::apiResource('milking-types', MilkingTypeController::class);
    Route::apiResource('milkings', MilkingController::class);
    Route::apiResource('movement-kardex', MovementKardexController::class);
    Route::apiResource('livestock', LivestockController::class);
    Route::apiResource('newborns', NewbornController::class);
    Route::apiResource('newborn-types', NewbornTypeController::class);
    Route::apiResource('outcomes', OutcomeController::class);
    Route::apiResource('outcome-types', OutcomeTypeController::class);
    Route::apiResource('owners', OwnerController::class);
    Route::apiResource('products', ProductController::class);
    Route::apiResource('product-movements', ProductMovementController::class);
    Route::apiResource('product-types', ProductTypeController::class);
    Route::apiResource('revisions', RevisionController::class);
    Route::apiResource('revision-types', RevisionTypeController::class);
    Route::apiResource('semen-batches', SemenBatchController::class);
    Route::apiResource('services', ServiceController::class);
    Route::apiResource('service-types', ServiceTypeController::class);
    Route::apiResource('states', StateController::class);
    Route::apiResource('supplies', SupplyController::class);
    Route::apiResource('supply-movements', SupplyMovementController::class);
    Route::apiResource('supply-types', SupplyTypeController::class);
    Route::apiResource('technicians', TechnicianController::class);
    Route::apiResource('teasings', TeasingController::class);

});
