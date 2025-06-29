<?php

use App\Models\{Item, User, Supplier, Requisition};
use App\Services\{RequisitionService, StockMovementService};
use Illuminate\Foundation\Testing\RefreshDatabase;

test('issues items in FIFO order', function () {
    $this->useRefreshDatabase();

    $exec = User::factory()->create()->assignRole('Store Executive');
    $supplier = Supplier::factory()->create();
    $item = Item::factory()->create();

    $stockSvc = app(StockMovementService::class);
    $stockSvc->receive($exec, $item, $supplier, 6, 100);
    $stockSvc->receive($exec, $item, $supplier, 3, 120);

    $emp = User::factory()->create()->assignRole('Employee');
    $reqSvc = app(RequisitionService::class);
    $req = $reqSvc->create($emp, ['items' => [['id' => $item->id, 'qty' => 5]]]);
    $req->status = 'approved';
    $req->save(); // shortcut for test

    $stockSvc->issue($exec, $req);

    expect($item->balance())->toBe(4)
        ->and($item->movements()->where('type', 'OUT')->sum('qty'))->toBe(5)
        ->and($item->movements()->where('type', 'OUT')->sum('unit_price')) // 5*100
        ->toBe(500);
});
