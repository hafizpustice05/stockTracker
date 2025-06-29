<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class StoreRequisitionRequest extends FormRequest
{
    public function rules(): array
    {
        return [
            'items'            => ['required', 'array', 'min:1'],
            'items.*.id'       => ['required', 'integer', 'exists:items,id'],
            'items.*.qty'      => ['required', 'integer', 'min:1'],
        ];
    }
}
