<?php

namespace App\Http\Requests;

use App\Models\User;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class UserStore extends FormRequest
{
    public function rules(): array
    {
        return [
            'username'    => 'required|unique:users,username',
            'role'        => ['required', Rule::in(User::ROLES)],
            'deposit'     => 'prohibited',
            'password'    => 'required|min:8',
            'createToken' => 'required|boolean'
        ];
    }

    public function prepareForValidation(): void
    {
        $this->merge([
            'createToken' => true
        ]);
    }
}
