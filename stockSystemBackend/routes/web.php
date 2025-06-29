<?php

use App\Models\Item;
use App\Models\Requisition;
use Illuminate\Support\Facades\Route;

Route::get('/', function () {

    $requisitionId = 4;
    $requisition = Requisition::with('items')->findOrFail($requisitionId);

    if ($requisition->status !== 'approved') {
        return response()->json(['message' => 'Requisition must be approved first.'], 422);
    }


    foreach ($requisition->items as $item) {
        $qtyToIssue = $item->pivot->qty;

        try {
            $issuedBatches = $item->fifoIssue($qtyToIssue); // 👈 using the model method

            return $issuedBatches;
        } catch (\Exception $e) {
            return response()->json(['message' => $e->getMessage()], 400);
        }
    }
});
