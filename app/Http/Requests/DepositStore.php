<?php

namespace App\Http\Requests;

use App\Models\Deposit;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class DepositStore extends FormRequest
{
    public function rules(): array
    {
        return [
            'amount' => ['required', Rule::in(Deposit::ACCEPTED_AMOUNTS)]
        ];
    }
}
