<?php

namespace App\Http\Resources;

use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class PostResource extends JsonResource
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
            'author' => new UserResource($this->author),
            'title' => $this->title,
            'content' => $this->content,
            'category' => $this->category,
            'created_at' => (new Carbon($this->created_at))->format('Y-m-d')
        ];
    }
}
