<?php

use App\Http\Controllers\AssignmentController;
use App\Http\Controllers\AuditLogController;
use App\Http\Controllers\BrandController;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\DepartmentController;
use App\Http\Controllers\EmployeeController;
use App\Http\Controllers\EquipmentCategoryController;
use App\Http\Controllers\EquipmentController;
use App\Http\Controllers\HistoryController;
use App\Http\Controllers\LocationController;
use App\Http\Controllers\MaintenanceController;
use App\Http\Controllers\PositionController;
use App\Http\Controllers\ProfileController;
use App\Http\Controllers\ReportController;
use App\Http\Controllers\SettingController;
use App\Http\Controllers\SupplierController;
use App\Http\Controllers\UserController;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
*/

Route::get('/', function () {
    return redirect()->route('login');
});

// Rutas protegidas por autenticación
Route::middleware(['auth', 'verified'])->group(function () {

    // Dashboard
    Route::get('/dashboard', [DashboardController::class, 'index'])->name('dashboard');
    Route::get('/dashboard/stats', [DashboardController::class, 'getStats'])->name('dashboard.stats');

    // Perfil de usuario
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');
    Route::post('/profile/theme', [ProfileController::class, 'updateTheme'])->name('profile.theme');

    // Escaneo QR
    Route::get('/scan', [EquipmentController::class, 'scan'])->name('scan');

    // Equipos
    Route::prefix('equipment')->name('equipment.')->group(function () {
        Route::get('/available', [EquipmentController::class, 'available'])->name('available');
        Route::get('/search', [EquipmentController::class, 'search'])->name('search');
    });
    Route::resource('equipment', EquipmentController::class);

    // Empleados
    Route::prefix('employees')->name('employees.')->group(function () {
        Route::get('/directory', [EmployeeController::class, 'directory'])->name('directory');
        Route::get('/directory/pdf', [EmployeeController::class, 'exportDirectoryPdf'])->name('directory.pdf');
        Route::get('/directory/excel', [EmployeeController::class, 'exportDirectoryExcel'])->name('directory.excel');
        Route::get('/active', [EmployeeController::class, 'active'])->name('active');
        Route::get('/positions/{department}', [EmployeeController::class, 'getPositionsByDepartment'])->name('positions');
        Route::get('/search', [EmployeeController::class, 'search'])->name('search');
    });
    Route::resource('employees', EmployeeController::class);

    // Asignaciones
    Route::prefix('assignments')->name('assignments.')->group(function () {
        Route::get('/{assignment}/return', [AssignmentController::class, 'return'])->name('return');
        Route::post('/{assignment}/return', [AssignmentController::class, 'processReturn'])->name('process-return');
        Route::get('/{assignment}/transfer', [AssignmentController::class, 'transfer'])->name('transfer');
        Route::post('/{assignment}/transfer', [AssignmentController::class, 'processTransfer'])->name('process-transfer');
        Route::get('/{assignment}/pdf', [AssignmentController::class, 'generatePdf'])->name('pdf');
        Route::get('/{assignment}/pdf/preview', [AssignmentController::class, 'previewPdf'])->name('pdf.preview');
        Route::get('/search', [AssignmentController::class, 'search'])->name('search');
    });
    Route::resource('assignments', AssignmentController::class)->except(['edit', 'update']);

    // Historial
    Route::prefix('history')->name('history.')->group(function () {
        Route::get('/', [HistoryController::class, 'index'])->name('index');
        Route::get('/{history}', [HistoryController::class, 'show'])->name('show');
        Route::get('/equipment/{equipment}', [HistoryController::class, 'byEquipment'])->name('by-equipment');
        Route::get('/employee/{employee}', [HistoryController::class, 'byEmployee'])->name('by-employee');
        Route::get('/equipment/{equipment}/timeline', [HistoryController::class, 'timeline'])->name('timeline');
    });

    // Mantenimiento
    Route::resource('maintenance', MaintenanceController::class);

    // Reportes
    Route::prefix('reports')->name('reports.')->group(function () {
        Route::get('/', [ReportController::class, 'index'])->name('index');
        Route::get('/equipment-summary', [ReportController::class, 'equipmentSummary'])->name('equipment-summary');
        Route::get('/assigned-equipment', [ReportController::class, 'assignedEquipment'])->name('assigned-equipment');
        Route::get('/available-equipment', [ReportController::class, 'availableEquipment'])->name('available-equipment');
        Route::get('/equipment-by-employee', [ReportController::class, 'equipmentByEmployee'])->name('equipment-by-employee');
        Route::get('/equipment-by-department', [ReportController::class, 'equipmentByDepartment'])->name('equipment-by-department');
        Route::get('/movement-history', [ReportController::class, 'movementHistory'])->name('movement-history');
        Route::get('/warranty-expiring', [ReportController::class, 'warrantyExpiring'])->name('warranty-expiring');
        Route::get('/maintenance', [ReportController::class, 'maintenanceReport'])->name('maintenance');
    });

    // Catálogos
    Route::resource('departments', DepartmentController::class);
    Route::resource('positions', PositionController::class);
    Route::resource('locations', LocationController::class);
    Route::resource('brands', BrandController::class);
    Route::resource('suppliers', SupplierController::class);
    Route::resource('categories', EquipmentCategoryController::class);

    // Usuarios
    Route::resource('users', UserController::class);

    // Configuración
    Route::prefix('settings')->name('settings.')->group(function () {
        Route::get('/', [SettingController::class, 'index'])->name('index');
        Route::post('/', [SettingController::class, 'update'])->name('update');
    });

    // Auditoría
    Route::prefix('audit')->name('audit.')->group(function () {
        Route::get('/', [AuditLogController::class, 'index'])->name('index');
        Route::get('/{audit}', [AuditLogController::class, 'show'])->name('show');
    });
});

require __DIR__.'/auth.php';
