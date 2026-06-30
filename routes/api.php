<?php

use App\Http\Controllers\Api\AssignmentApiController;
use App\Http\Controllers\Api\EmployeeApiController;
use App\Http\Controllers\Api\EquipmentApiController;
use App\Http\Controllers\Api\ReportApiController;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
*/

Route::middleware('auth:sanctum')->group(function () {
    // Usuario autenticado
    Route::get('/user', function (Request $request) {
        return $request->user();
    });

    // Equipos
    Route::prefix('equipment')->group(function () {
        Route::get('/', [EquipmentApiController::class, 'index']);
        Route::get('/available', [EquipmentApiController::class, 'available']);
        Route::get('/assigned', [EquipmentApiController::class, 'assigned']);
        Route::get('/stats', [EquipmentApiController::class, 'stats']);
        Route::get('/{equipment}', [EquipmentApiController::class, 'show']);
        Route::post('/', [EquipmentApiController::class, 'store']);
        Route::put('/{equipment}', [EquipmentApiController::class, 'update']);
        Route::delete('/{equipment}', [EquipmentApiController::class, 'destroy']);
        Route::get('/{equipment}/history', [EquipmentApiController::class, 'history']);
    });

    // Empleados
    Route::prefix('employees')->group(function () {
        Route::get('/', [EmployeeApiController::class, 'index']);
        Route::get('/active', [EmployeeApiController::class, 'active']);
        Route::get('/{employee}', [EmployeeApiController::class, 'show']);
        Route::post('/', [EmployeeApiController::class, 'store']);
        Route::put('/{employee}', [EmployeeApiController::class, 'update']);
        Route::delete('/{employee}', [EmployeeApiController::class, 'destroy']);
        Route::get('/{employee}/equipment', [EmployeeApiController::class, 'equipment']);
    });

    // Asignaciones
    Route::prefix('assignments')->group(function () {
        Route::get('/', [AssignmentApiController::class, 'index']);
        Route::get('/active', [AssignmentApiController::class, 'active']);
        Route::get('/{assignment}', [AssignmentApiController::class, 'show']);
        Route::post('/', [AssignmentApiController::class, 'store']);
        Route::post('/{assignment}/return', [AssignmentApiController::class, 'return']);
        Route::post('/{assignment}/transfer', [AssignmentApiController::class, 'transfer']);
    });

    // Reportes
    Route::prefix('reports')->group(function () {
        Route::get('/dashboard', [ReportApiController::class, 'dashboard']);
        Route::get('/equipment-summary', [ReportApiController::class, 'equipmentSummary']);
        Route::get('/equipment-by-department', [ReportApiController::class, 'equipmentByDepartment']);
        Route::get('/warranty-expiring', [ReportApiController::class, 'warrantyExpiring']);
    });

    // Catálogos
    Route::get('/departments', function () {
        return \App\Models\Department::active()->orderBy('name')->get();
    });

    Route::get('/positions', function () {
        return \App\Models\Position::active()->with('department')->orderBy('name')->get();
    });

    Route::get('/categories', function () {
        return \App\Models\EquipmentCategory::active()->orderBy('name')->get();
    });

    Route::get('/brands', function () {
        return \App\Models\Brand::active()->orderBy('name')->get();
    });

    Route::get('/locations', function () {
        return \App\Models\Location::active()->orderBy('name')->get();
    });

    Route::get('/suppliers', function () {
        return \App\Models\Supplier::active()->orderBy('name')->get();
    });
});
