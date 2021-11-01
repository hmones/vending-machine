<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class OrderStore extends FormRequest
{
    public function rules(): array
    {
        return [
            'product_id' => 'required|exists:products,id',
            'amount'     => 'required|numeric|integer'
        ];
    }
}
