<?php

namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\JsonResource;

class TenantResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return array
     */
    public function toArray($request)
    {
        return [
            'id' => $this->id,
            'no_unit' => $this->no_unit,
            'name' => $this->name,
            'phone' => $this->phone,
            'email' => $this->email,
            'number' => $this->number,
            'created_at' => $this->created_at,
            'updated_at' => $this->updated_at
        ];
    }
}
