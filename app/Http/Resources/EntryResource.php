<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class EntryResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @return array<string, mixed>
     */
    public function toArray(Request $request): array
    {
        $data = [
            'id' => $this->id,
            'title' => $this->title,
            'slug' => $this->slug,
            'status' => $this->status,
            'published_at' => $this->published_at,
            'created_at' => $this->created_at,
            'updated_at' => $this->updated_at,
            'content_type' => $this->whenLoaded('contentType', function () {
                return $this->contentType->name;
            }),
            'author' => $this->whenLoaded('user', function () {
                return $this->user->name;
            }),
        ];

        // Eager load fields and entryFields to prevent N+1 issues
        $this->whenLoaded('entryFields', function () use (&$data) {
            $fieldTypeService = app(App\Services\FieldTypeService::class);
            $fields = $this->contentType->fields->keyBy('id');
            foreach ($this->entryFields as $entryField) {
                if (isset($fields[$entryField->field_id])) {
                    $field = $fields[$entryField->field_id];
                    $data[$field->name] = $fieldTypeService->normalizeValue($field->type, $entryField->value);
                }
            }
        });

        return $data;
    }
}
