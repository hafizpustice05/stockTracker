<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;

class Requisition extends Model
{
    use HasFactory;

    protected $fillable = ['user_id', 'status', 'approved_by', 'approved_at', 'rejected_by', 'rejection_reason'];

    protected $casts = [
        'approved_at' => 'datetime',
    ];

    public function creator(): BelongsTo
    {
        return $this->belongsTo(User::class, 'user_id');
    }
    public function approver(): BelongsTo
    {
        return $this->belongsTo(User::class, 'approved_by');
    }
    public function items(): BelongsToMany
    {
        return $this->belongsToMany(Item::class, 'requisition_item')->withPivot('qty')->withTimestamps();
    }
}
