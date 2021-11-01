<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class ProductUpdate extends FormRequest
{
    public function rules(): array
    {
        return [
            'product_name'     => 'sometimes|string',
            'amount_available' => 'sometimes|numeric',
            'cost'             => 'sometimes|numeric',
        ];
    }
}
