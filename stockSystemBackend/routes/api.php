<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\API\Employee\EmployeeRequisitionController as EmpReqCtrl;
use App\Http\Controllers\API\Admin\AdminRequisitionController   as AdmReqCtrl;
use App\Http\Controllers\API\Admin\ItemController;
use App\Http\Controllers\API\Admin\SupplierController;
use App\Http\Controllers\API\StockController;
use App\Http\Controllers\API\Auth\AuthController;

Route::post('/login', [AuthController::class, 'login']);
Route::middleware('auth:sanctum')->get('/me', [AuthController::class, 'me']);
Route::middleware('auth:sanctum')->post('/logout', [AuthController::class, 'logout']);

// route for admin user
Route::middleware(['auth:sanctum', 'role:admin'])->group(function () {
    Route::get('/requisitions/pending', [AdmReqCtrl::class, 'index']);
    Route::patch('/requisitions/{requisition}/approve', [AdmReqCtrl::class, 'approve']);
    Route::patch('/requisitions/{requisition}/reject',  [AdmReqCtrl::class, 'reject']);
    Route::apiResource('items',     ItemController::class);
    Route::apiResource('suppliers', SupplierController::class);
});

// routes for employee users
Route::middleware(['auth:sanctum', 'role:employee'])->group(function () {
    Route::post('/requisitions',        [EmpReqCtrl::class, 'store']);
    Route::get('/requisitions/my',      [EmpReqCtrl::class, 'my']);
});

// routes for store executive users
Route::middleware(['auth:sanctum', 'role:store_executive'])->group(function () {
    Route::post('/stock/receive',       [StockController::class, 'receive']);
    Route::post('/stock/issue/{requisition}', [StockController::class, 'issue']);
    Route::get('/stock',               [StockController::class, 'index']);
    Route::post('/stock/fifo-preview', [StockController::class, 'fifoPreview']);
});

Route::middleware(['auth:sanctum'])->group(function () {
    Route::get('/items', [ItemController::class, 'index']);
    Route::get('/suppliers', [SupplierController::class, 'index']);
    Route::get('/requisitions/approved', [AdmReqCtrl::class, 'approved']);
});
