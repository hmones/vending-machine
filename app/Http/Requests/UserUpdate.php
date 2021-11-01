<?php

namespace App\Http\Requests;

use App\Models\User;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class UserUpdate extends FormRequest
{
    public function rules(): array
    {
        return [
            'username'    => 'sometimes|unique:users,username,' . auth()->user()->username,
            'role'        => ['sometimes', Rule::in(User::ROLES)],
            'deposit'     => 'prohibited',
            'password'    => 'sometimes|min:8'
        ];
    }
}
