<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use App\Http\Requests\ReceiveItemRequest;
use App\Http\Requests\IssueRequest;
use App\Models\Item;
use App\Models\Requisition;
use App\Models\Supplier;
use App\Services\StockMovementService;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Http\Response;

class StockController extends Controller
{
    /**
     * StockController constructor.
     *
     * @param StockMovementService $service
     */
    public function __construct(private StockMovementService $service) {}

    /**
     * Receive items into stock.
     *
     * @param ReceiveItemRequest $req
     * @return JsonResponse
     */
    public function receive(ReceiveItemRequest $req): JsonResponse
    {
        $movement = $this->service->receive(
            $req->user(),
            Item::findOrFail($req->item_id),
            Supplier::findOrFail($req->supplier_id),
            $req->qty,
            $req->unit_price
        );
        return response()->json($movement,  Response::HTTP_CREATED);
    }

    /**
     * Issue items from stock based on a requisition.
     *
     * @param Requisition $requisition
     * @param IssueRequest $req
     * @return JsonResponse
     */
    public function issue(Requisition $requisition, IssueRequest $req): JsonResponse
    {
        $this->service->issue($req->user(), $requisition);
        return response()->json(['status' => 'issued'], Response::HTTP_OK);
    }

    /**
     * Get current stock levels for all items.
     *
     * @return JsonResponse
     */
    public function index(): JsonResponse
    {
        $stock = Item::with(['movements'])
            ->get()->map(fn($i) => [
                'id'       => $i->id,
                'name'     => $i->name,
                'qty'      => $i->netQty(),
                'last_cost' => $i->totalStockPrice(),
            ]);
        return response()->json($stock, Response::HTTP_OK);
    }

    /**
     * Get stock levels for a specific item.
     *
     * @param Item $item
     * @return JsonResponse
     */
    public function fifoPreview(Request $request)
    {
        $request->validate([
            'requisition_id' => 'required|exists:requisitions,id',
        ]);

        $requisition = Requisition::with('items')->findOrFail($request->requisition_id);
        $itemTotals = [];

        foreach ($requisition->items as $item) {
            $qty = $item->pivot->qty;

            // Get FIFO batches
            $batches = $item->movements()
                ->where('type', 'IN')
                ->where('qty', '>', 'issued_qty') // only consider unissued stock
                ->orderBy('created_at')
                ->get();

            $remaining = $qty;
            $total = 0;

            foreach ($batches as $batch) {
                $available = $batch->qty - $batch->issued_qty;

                if ($available <= 0) continue;

                $useQty = min($available, $remaining);
                $total += $useQty * $batch->unit_price;

                $remaining -= $useQty;
                if ($remaining <= 0) break;
            }

            if ($remaining > 0) {
                return response()->json(['message' => "Insufficient stock for item: {$item->name}"], 422);
            }

            $itemTotals[$item->id] = $total;
        }

        return response()->json([
            'item_totals' => $itemTotals
        ], Response::HTTP_OK);
    }
}
