<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class ProductStore extends FormRequest
{
    public function rules(): array
    {
        return [
            'product_name'     => 'required|string',
            'amount_available' => 'required|numeric',
            'cost'             => 'required|numeric',
        ];
    }
}
