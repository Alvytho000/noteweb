<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class NoteResource extends JsonResource
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
            'title' => $this->title,
            'excerpt' => $this->excerpt, // Menampilkan field virtual kita
            'content' => $this->content,
            'image_url' => $this->image ? asset('storage/' . $this->image) : null,
            'is_pinned' => (bool) $this->is_pinned,
            'created_at' => $this->created_at?->format('Y-m-d H:i:s'),
            'updated_at' => $this->updated_at?->format('Y-m-d H:i:s'),
            'tags' => TagResource::collection($this->whenLoaded('tags')), // Jika ada relasi tag
        ];
    }
}
