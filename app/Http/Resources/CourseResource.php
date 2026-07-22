<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class CourseResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @return array<string, mixed>
     */
    public function toArray(Request $request): array
    {
        return [
            'id'               => $this->id,
            'title'            => $this->title,
            'slug'             => $this->slug,
            'description'      => $this->description,
            'thumbnail_url'    => $this->thumbnail_url,
            'difficulty_level' => $this->difficulty_level,
            'duration'         => $this->formatted_duration,
            'language'         => $this->language,

            // Present whenever the controller added ->withCount('enrollments').
            'students_count' => $this->when(
                isset($this->enrollments_count),
                fn () => $this->enrollments_count
            ),

            'category' => $this->whenLoaded('category', fn () => [
                'title' => $this->category->title,
                'slug'  => $this->category->slug,
            ]),

            // Detail-page only — omitted entirely from catalog cards.
            'creator' => $this->whenLoaded('creator', fn () => [
                'name' => $this->creator->name,
            ]),

            'modules' => $this->whenLoaded('modules', fn () => $this->modules->map(fn ($module) => [
                'id'          => $module->id,
                'title'       => $module->title,
                'description' => $module->description,
                'items'       => $module->moduleItems->map(fn ($item) => [
                    'id'               => $item->id,
                    'position'         => $item->position,
                    'type'             => $item->content_type,
                    'title'            => $item->itemable?->title,
                    'duration_seconds' => $item->itemable_type === Lesson::class
                        ? $item->itemable?->duration_seconds
                        : null,
                ]),
            ])),
        ];
    }
}
