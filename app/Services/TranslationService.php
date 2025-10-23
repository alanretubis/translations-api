<?php

namespace App\Services;

use App\Models\Tag;
use App\Models\Translation;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Http\Request;

class TranslationService
{
    /**
     * Search translations by key, locale, or tag.
     */
    public function search(array $filters)
    {
        $query = Translation::with(['locale', 'tags']);

        if (!empty($filters['key'])) {
            $query->where('key', 'like', '%' . $filters['key'] . '%');
        }

        if (!empty($filters['locale'])) {
            $query->whereHas('locale', fn($q) =>
                $q->where('code', $filters['locale'])
            );
        }

        if (!empty($filters['tag'])) {
            $query->whereHas('tags', fn($q) =>
                $q->where('name', 'like', '%' . $filters['tag'] . '%')
            );
        }

        if (!empty($filters['content'])) {
            $query->where('value', 'like', '%' . $filters['content'] . '%');
        }

        return $query->paginate(50);
    }

    /**
     * Create a translation with optional tags.
     */
    public function create(array $data): Translation
    {
        $translation = new Translation([
            'key' => $data['key'],
            'locale_id' => $data['locale_id'],
            'value' => $data['value'],
        ]);

        $translation->save();

        if (!empty($data['tags'])) {
            $tagIds = $this->resolveTags($data['tags']);
            $translation->tags()->sync($tagIds);
        }

        return $translation->load(['tags', 'locale']);
    }

    /**
     * Find translation by ID.
     */
    public function find(int $id): ?Translation
    {
        return Translation::with(['tags', 'locale'])->find($id);
    }

    /**
     * Update translation and its tags.
     */
    public function update(int $id, array $data): Translation
    {
        $translation = Translation::findOrFail($id);

        $translation->fill([
            'key' => $data['key'] ?? $translation->key,
            'locale_id' => $data['locale_id'] ?? $translation->locale_id,
            'value' => $data['value'] ?? $translation->value,
        ]);

        $translation->save();

        if (isset($data['tags'])) {
            $tagIds = $this->resolveTags($data['tags']);
            $translation->tags()->sync($tagIds);
        }

        return $translation->load(['tags', 'locale']);
    }

    /**
     * Delete a translation by ID.
     */
    public function delete(int $id): void
    {
        $translation = Translation::findOrFail($id);
        $translation->tags()->detach();
        $translation->delete();
    }

    /**
     * Export translations with pagination.
     */
    public function export(?int $perPage = 1000)
    {
        $translations = Translation::with(['locale', 'tags'])
            ->paginate($perPage);

        // Transform items in the paginator
        $translations->getCollection()->transform(function ($t) {
            return [
                'key' => $t->key,
                'value' => $t->value,
                'locale' => $t->locale->code ?? 'unknown',
                'tags' => $t->tags->pluck('name')->toArray(),
            ];
        });

        return $translations;
    }

    /**
     * Helper: Resolve tag names (string or array) to IDs.
     */
    private function resolveTags(string|array $tags): array
    {
        $tagsArray = is_string($tags)
            ? explode(',', $tags)
            : $tags;

        return collect($tagsArray)
            ->map(fn($t) => trim($t))
            ->filter()
            ->map(fn($name) => Tag::firstOrCreate(['name' => $name])->id)
            ->toArray();
    }
}
