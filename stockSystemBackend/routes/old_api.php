<?php

// use Illuminate\Support\Facades\Route;
// use App\Http\Controllers\Employee\RequisitionController as EmpReqCtrl;
// use App\Http\Controllers\Admin\RequisitionController   as AdmReqCtrl;
// use App\Http\Controllers\StockController;

// Route::middleware(['auth:sanctum'])->group(function () {
//     // employee
//     Route::post('/requisitions',        [EmpReqCtrl::class, 'store'])->middleware('role:Employee');
//     Route::get('/requisitions/my',      [EmpReqCtrl::class, 'my']);

//     // admin
//     Route::get('/requisitions/pending', [AdmReqCtrl::class, 'index'])->middleware('role:Admin');
//     Route::patch('/requisitions/{requisition}/approve', [AdmReqCtrl::class, 'approve']);
//     Route::patch('/requisitions/{requisition}/reject',  [AdmReqCtrl::class, 'reject']);

//     // store executive
//     Route::post('/stock/receive',       [StockController::class, 'receive'])->middleware('role:Store Executive');
//     Route::post('/stock/issue/{requisition}', [StockController::class, 'issue']);
//     Route::get('/stock',               [StockController::class, 'index']);

//     // CRUD for items & suppliers (admin only)
//     // Route::apiResource('items',     ItemController::class)->middleware('role:Admin');
//     // Route::apiResource('suppliers', SupplierController::class)->middleware('role:Admin');
// });