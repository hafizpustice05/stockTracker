<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class StockMovement extends Model
{
    use HasFactory;

    protected $fillable = [
        'item_id',
        'supplier_id',
        'requisition_id',
        'handled_by',
        'type',
        'qty',
        'unit_price',
        'issued_qty',
    ];

    // Convenience accessors
    public function remaining(): int
    {
        return $this->qty - $this->issued_qty;
    }
}
