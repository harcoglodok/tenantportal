<?php

namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\JsonResource;

class NotificationResource extends JsonResource
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
            'notification_category_id' => $this->notification_category_id,
            'title' => $this->title,
            'image' => $this->image,
            'message' => $this->message,
            'date' => $this->date,
            'created_at' => $this->created_at,
            'updated_at' => $this->updated_at
        ];
    }
}
