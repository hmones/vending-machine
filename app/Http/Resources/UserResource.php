<?php

namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\JsonResource;

class UserResource extends JsonResource
{
    public function toArray($request)
    {
        return [
            'id'         => $this->id,
            'username'   => $this->username,
            'role'       => $this->role,
            'deposit'    => $this->deposit,
            'created_at' => $this->created_at->toJson(),
            'updated_at' => $this->updated_at->toJson(),
            'token'      => $this->when($request->createToken, $this->createToken($this->role)->plainTextToken)
        ];
    }
}
