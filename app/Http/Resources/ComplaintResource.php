<?php

namespace App\Http\Resources;

use App\Models\ComplaintReply;
use Illuminate\Http\Resources\Json\JsonResource;

class ComplaintResource extends JsonResource
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
            'category_id' => $this->category_id,
            'category_name' => $this->category->title,
            'title' => $this->title,
            'content' => $this->content,
            'status' => $this->status,
            'photo' => $this->photo,
            'created_by' => $this->createdBy->name,
            'updated_by' => $this->updatedBy->name,
            'created_at' => $this->created_at,
            'updated_at' => $this->updated_at,
            'replies' => ComplaintReplyResource::collection($this->replies),
            'unit' => new UnitResource($this->unit),
        ];
    }
}
