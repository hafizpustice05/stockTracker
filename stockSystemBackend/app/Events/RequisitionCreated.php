<?php

namespace App\Events;

use App\Models\Requisition;
use Illuminate\Foundation\Events\Dispatchable;
use Illuminate\Queue\SerializesModels;

class RequisitionCreated
{
    use Dispatchable, SerializesModels;

    public function __construct(public Requisition $requisition) {}
}
