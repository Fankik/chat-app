<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

/**
 * Resource списка чатов
 */
class ChatIndexResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @return array<string, mixed>
     */
    public function toArray(Request $request): array
    {
        return [
            'id' => $this->id,
            'name' => $this->users->where('id', '!=', $this->pivot->user_id)->first()->full_name,
            'created_at' => $this->created_at,
            'updated_at' => $this->updated_at,
            'users' => ChatUserIndexResource::collection($this->users),
        ];
    }
}
