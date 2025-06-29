<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Item extends Model
{
    use HasFactory;

    protected $fillable = ['name', 'description', 'status'];

    public function movements(): HasMany
    {
        return $this->hasMany(StockMovement::class);
    }

    public function netQty(): int
    {
        return $this->movements()->get()->reduce(function ($carry, $m) {
            return $carry + ($m->type === 'IN' ? $m->qty : -$m->qty);
        }, 0);
    }

    public function fifoIssue(int $qty): array
    {
        $remaining = $qty;
        $batches = $this->movements()
            ->where('type', 'IN')
            ->where('qty', '>', 'issued_qty') // only consider unissued stock
            ->orderBy('created_at')
            ->get();

        $issued = [];
        foreach ($batches as $batch) {
            $available = $batch->qty - $batch->issued_qty;
            if ($available <= 0) continue;

            $take = min($remaining, $available);
            $issued[] = [
                'movement_id' => $batch->id,
                'qty' => $take,
                'price' => $batch->unit_price,
            ];

            $batch->issued_qty += $take;
            $batch->save();

            $remaining -= $take;
            if ($remaining <= 0) break;
        }

        if ($remaining > 0) throw new \Exception("Not enough stock");

        return $issued;
    }

    public function totalStockPrice(): float
    {
        $batches = $this->movements()
            ->where('type', 'IN')
            ->where('qty', '>', 'issued_qty') // only consider unissued stock
            ->get();

        $totalValue = 0;

        foreach ($batches as $batch) {
            $available = $batch->qty - $batch->issued_qty;
            $totalValue += $available * $batch->unit_price;
        }
        return $totalValue;
    }
}
