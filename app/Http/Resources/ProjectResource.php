<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class ProjectResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @return array<string, mixed>
     */
    public function toArray(Request $request): array
    {
        /* @var \App\Models\Project $this */
        return [
            'id' => $this->id,
            'name' => $this->name,
            'url' => $this->url,
            'route' => route('projects.show', $this->id),
            'created_at' => $this->created_at->diffForHumans(),
            'updated_at' => $this->updated_at->diffForHumans(),
            'status' => $this->status->value,
            'visibility' => $this->visibility->value,
            'order' => $this->order_column,
        ];
    }
}
