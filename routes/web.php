<?php
use App\Http\Controllers\Auth\LoginController;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\EmployeeController;
use App\Http\Controllers\LoginHistoryController;
use App\Http\Controllers\PositionController;
use App\Http\Controllers\UnitController;
use Illuminate\Support\Facades\Route;

Route::get('/login', [LoginController::class, 'showLoginForm'])->name('login');
Route::post('/login', [LoginController::class, 'login']);

Route::middleware(['auth'])->group(function () {
    Route::get('/dashboard', [DashboardController::class, 'index'])->name('dashboard');
    Route::get('/unit', [UnitController::class, 'page'])->name('unit');
    Route::get('/position', [PositionController::class, 'page'])->name('position');
    Route::get('/login-history', [LoginHistoryController::class, 'page'])->name('login_history');
    Route::get('/employee', [EmployeeController::class, 'page'])->name('employee');

    Route::get('/positions', [PositionController::class, 'index']);
    Route::get('/units', [UnitController::class, 'index']);
    Route::get('/employees-paginated', [EmployeeController::class, 'employeePaginated']);
    Route::get('/units-paginated', [UnitController::class, 'unitPaginated']);
    Route::get('/positions-paginated', [PositionController::class, 'positionPaginated']);
    Route::get('/login-histories-paginated', [LoginHistoryController::class, 'loginHistoryPaginated']);
    Route::post('/add-new-user', [DashboardController::class, 'addNewUser']);

    Route::get('/dashboard-data', [DashboardController::class, 'dashboardData']);
    Route::post('/logout', [LoginController::class, 'destroy'])->name('logout');
});
