<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class UserResource extends JsonResource
{
    /**
     * Transform the resource collection into an array.
     *
     * @return array<int|string, mixed>
     */
    public function toArray(Request $request): array
    {
        return [
            'id' => $this->id,
            'name' => $this->name,
            'email' => $this->email,
            'birthdate' => $this->birthdate,
            'avatar' => $this->avatar,
            'device_token' => $this->device_token,
            'verified_at' => $this->verified_at,
            'blocked_at' => $this->blocked_at,
            'block_message' => $this->block_message,
            'role' => $this->role,
        ];
    }
}
