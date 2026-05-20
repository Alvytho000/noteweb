<?php

namespace App\Services;

use App\Models\Tag;
use Illuminate\Database\Eloquent\Collection;

class TagService
{
    public function create(array $data, int $userId): Tag
    {
        $tag = Tag::query()->create([
            'user_id' => $userId,
            'name' => $data['name'],
        ]);
        
        $tag->notes()->sync($data['note_ids'] ?? []);
        
        return $tag->load('notes');
    }

    public function getAll(int $userId): Collection
    {
        return Tag::query()->where('user_id', $userId)->with('notes')->get();
    }

    public function findById(int $id, int $userId): Tag
    {
        /** @var Tag $tag */
        $tag = Tag::query()->where('user_id', $userId)->with('notes')->findOrFail($id);
        return $tag;
    }

    public function update(int $id, int $userId, array $data): Tag
    {
        /** @var Tag $tag */
        $tag = Tag::query()->where('user_id', $userId)->findOrFail($id);
        
        $filteredData = array_filter($data, fn($value) => !is_null($value));
        $tag->update($filteredData);

        if (isset($data['note_ids']) && !is_null($data['note_ids'])) {
            $tag->notes()->sync($data['note_ids']);
        }
        
        return $tag->load('notes');
    }

    public function delete(int $id, int $userId): bool
    {
        $tag = Tag::query()->where('user_id', $userId)->findOrFail($id);
        
        return (bool) $tag->delete();
    }
}
