<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class MediaResource extends JsonResource
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
            'code' => $this->code,
            'title' => $this->title,
            'type' => $this->type,
            'genre' => $this->genre,
            'rating' => $this->rating,
            'synopsis' => $this->synopsis,
            'is_available' => $this->is_available,
            $this->mergeWhen($this->type === 'anime', [
                'total_episodes' => $this->total_episodes,
                'studio' => $this->studio,
                'resolution' => $this->resolution,
                'has_sub_indo' => $this->has_sub_indo,
            ]),
            $this->mergeWhen($this->type === 'manga', [
                'total_chapters' => $this->total_chapters,
                'author' => $this->author,
                'is_colored' => $this->is_colored,
                'is_completed' => $this->is_completed,
            ]),
            'progress' => $this->whenPivotLoaded('media_user', fn () => $this->pivot->progress),
            'users' => UserResource::collection($this->whenLoaded('users')),
            'created_at' => $this->created_at,
            'updated_at' => $this->updated_at,
        ];
    }
}
