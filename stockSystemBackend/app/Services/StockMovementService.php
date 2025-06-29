<?php

namespace App\Services;

use App\Models\Item;
use App\Models\Requisition;
use App\Models\StockMovement;
use App\Models\Supplier;
use App\Models\User;
use Illuminate\Support\Facades\DB;
use RuntimeException;

class StockMovementService
{
    /**
     * Receive items into stock.
     *
     * @param User $exec
     * @param Item $item
     * @param Supplier $sup
     * @param int $qty
     * @param int $unitPrice
     * @return StockMovement
     */
    public function receive(User $exec, Item $item, Supplier $sup, int $qty, int $unitPrice): StockMovement
    {
        return DB::transaction(function () use ($exec, $item, $sup, $qty, $unitPrice) {
            return StockMovement::create([
                'item_id'       => $item->id,
                'supplier_id'   => $sup->id,
                'handled_by'    => $exec->id,
                'type'          => 'IN',
                'qty'           => $qty,
                'unit_price'    => $unitPrice,
            ]);
        });
    }

    /**
     * Issue items from stock based on a requisition.
     *
     * @param User $exec
     * @param Requisition $req
     * @throws RuntimeException
     */
    public function issue(User $exec, Requisition $req): void
    {
        if ($req->status !== 'approved') {
            throw new RuntimeException('Requisition not approved');
        }

        DB::transaction(function () use ($exec, $req) {
            foreach ($req->items as $item) {
                $remaining = $item->pivot->qty;

                /** @var \Illuminate\Support\Collection $layers */
                $layers = $item->movements()
                    ->where('type', 'IN')
                    ->whereColumn('qty', '>', 'issued_qty')
                    ->orderBy('id') // FIFO = chronological
                    ->lockForUpdate()
                    ->get();

                foreach ($layers as $layer) {
                    $take = min($remaining, $layer->remaining());

                    // create matching OUT row
                    StockMovement::create([
                        'item_id'       => $item->id,
                        'requisition_id' => $req->id,
                        'handled_by'    => $exec->id,
                        'type'          => 'OUT',
                        'qty'           => $take,
                        'unit_price'    => $layer->unit_price,
                    ]);

                    // mark as issued in the IN layer
                    $layer->increment('issued_qty', $take);

                    $remaining -= $take;
                    if ($remaining === 0) break;
                }

                if ($remaining > 0) {
                    throw new RuntimeException('Insufficient stock for item ' . $item->id);
                }
            }
        });
    }
}
