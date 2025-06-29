<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class ReceiveItemRequest extends FormRequest
{
    public function rules(): array
    {
        return [
            'item_id'    => ['required', 'exists:items,id'],
            'supplier_id' => ['required', 'exists:suppliers,id'],
            'qty'        => ['required', 'integer', 'min:1'],
            'unit_price' => ['required', 'integer', 'min:1'],
        ];
    }
}
