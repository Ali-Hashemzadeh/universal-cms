<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use App\Http\Resources\EntryCollection;
use App\Http\Resources\EntryResource;
use App\Models\ContentType;
use App\Models\Entry;
use App\Models\EntryField;
use Illuminate\Http\Request;
use Illuminate\Http\JsonResponse;

class EntryController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(string $contentTypeSlug): JsonResponse
    {
        $this->authorize('viewAny', Entry::class);
        $contentType = $this->getContentType($contentTypeSlug);
        $entries = Entry::where('content_type_id', $contentType->id)
            ->with('contentType.fields', 'user', 'entryFields.field')
            ->paginate();

        return new EntryCollection($entries);
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request, string $contentTypeSlug): JsonResponse
    {
        $this->authorize('create', Entry::class);
        $contentType = $this->getContentType($contentTypeSlug);
        $rules = $this->generateValidationRules($contentType);
        $validatedData = $request->validate($rules);

        $entry = Entry::create([
            'content_type_id' => $contentType->id,
            'user_id' => auth()->id(),
            'title' => $validatedData['title'],
            'slug' => $validatedData['slug'],
            'status' => $validatedData['status'] ?? 'draft',
        ]);

        $fieldTypeService = app(App\Services\FieldTypeService::class);
        $entryFields = [];
        foreach ($contentType->fields as $field) {
            if (isset($validatedData[$field->name])) {
                $entryFields[] = [
                    'entry_id' => $entry->id,
                    'field_id' => $field->id,
                    'value' => $fieldTypeService->serializeValue($field->type, $validatedData[$field->name]),
                    'created_at' => now(),
                    'updated_at' => now(),
                ];
            }
        }

        if (!empty($entryFields)) {
            EntryField::insert($entryFields);
        }

        $entry->load('contentType.fields', 'user', 'entryFields');

        return new EntryResource($entry);
    }

    /**
     * Display the specified resource.
     */
    public function show(string $contentTypeSlug, Entry $entry): JsonResponse
    {
        $this->authorize('view', $entry);
        $contentType = $this->getContentType($contentTypeSlug);
        if ($entry->content_type_id !== $contentType->id) {
            abort(404);
        }

        $entry->load('contentType.fields', 'user', 'entryFields.field');

        return new EntryResource($entry);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $contentTypeSlug, Entry $entry): JsonResponse
    {
        $this->authorize('update', $entry);
        $contentType = $this->getContentType($contentTypeSlug);
        $rules = $this->generateValidationRules($contentType);
        $rules['slug'] .= ',' . $entry->id; // Ignore unique check for current entry
        $validatedData = $request->validate($rules);

        $entry->update([
            'title' => $validatedData['title'],
            'slug' => $validatedData['slug'],
            'status' => $validatedData['status'] ?? $entry->status,
        ]);

        $fieldTypeService = app(App\Services\FieldTypeService::class);
        $entryFieldsData = [];
        foreach ($contentType->fields as $field) {
            if (isset($validatedData[$field->name])) {
                $entryFieldsData[] = [
                    'entry_id' => $entry->id,
                    'field_id' => $field->id,
                    'value' => $fieldTypeService->serializeValue($field->type, $validatedData[$field->name]),
                ];
            }
        }

        if (!empty($entryFieldsData)) {
            EntryField::upsert(
                $entryFieldsData,
                ['entry_id', 'field_id'],
                ['value']
            );
        }

        $entry->load('contentType.fields', 'user', 'entryFields');

        return new EntryResource($entry);
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $contentTypeSlug, Entry $entry): JsonResponse
    {
        $this->authorize('delete', $entry);
        $contentType = $this->getContentType($contentTypeSlug);
        if ($entry->content_type_id !== $contentType->id) {
            abort(404);
        }

        $entry->delete();

        return response()->json(null, 204);
    }

    /**
     * Get the content type by slug.
     */
    private function getContentType(string $slug): ContentType
    {
        $contentType = ContentType::where('slug', $slug)->first();
        if (!$contentType) {
            abort(404, "Content type '{$slug}' not found.");
        }
        return $contentType;
    }

    /**
     * Generate validation rules for the given content type.
     */
    private function generateValidationRules(ContentType $contentType): array
    {
        $fieldTypeService = app(App\Services\FieldTypeService::class);
        $rules = [
            'title' => 'required|string|max:255',
            'slug' => 'required|string|max:255|unique:entries,slug',
            'status' => 'sometimes|in:draft,published,archived',
        ];

        foreach ($contentType->fields as $field) {
            $baseRules = $fieldTypeService->getValidationRules($field->type);
            $customRules = is_array($field->validation) ? $field->validation : explode('|', $field->validation ?? '');

            $allRules = array_unique(array_merge($baseRules, array_filter($customRules)));

            if (!empty($allRules)) {
                $rules[$field->name] = $allRules;
            }
        }

        return $rules;
    }
}
