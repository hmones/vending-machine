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
            'deposit'    => (float)$this->deposit,
            'created_at' => optional($this->created_at)->toJson(),
            'updated_at' => optional($this->updated_at)->toJson()
        ];
    }
}
