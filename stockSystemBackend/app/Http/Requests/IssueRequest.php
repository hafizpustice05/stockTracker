<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class IssueRequest extends FormRequest
{
    public function rules(): array
    {
        return [ // no body needed – route model binding ensures requisition exists
        ];
    }
}
