<?php

namespace App\Services;

use App\Models\Note;
use Illuminate\Pagination\LengthAwarePaginator;
use Illuminate\Support\Facades\Storage;

class NoteService
{
    public function create(array $data, int $userId): Note
    {
        $imagePath = null;
        
        if (isset($data['image']) && $data['image'] instanceof \Illuminate\Http\UploadedFile) {
            $imagePath = $data['image']->store('notes', 'public');
        }

        $note = Note::query()->create([
            'user_id' => $userId,
            'title' => $data['title'],
            'content' => $data['content'],
            'image' => $imagePath,
            'is_pinned' => $data['is_pinned'] ?? false,
        ]);
        
        $note->tags()->sync($data['tag_ids'] ?? []);
        
        return $note->load('tags');
    }

    public function getAll(int $userId): LengthAwarePaginator
    {
        return Note::query()
            ->where('user_id', $userId)
            ->with('tags')
            ->latest()
            ->paginate(12);
    }

    public function findById(int $id, int $userId): Note
    {
        /** @var Note $note */
        $note = Note::query()->where('user_id', $userId)->with('tags')->findOrFail($id);
        return $note;
    }

    public function update(int $id, int $userId, array $data): Note
    {
        /** @var Note $note */
        $note = Note::query()->where('user_id', $userId)->findOrFail($id);
        
        if (isset($data['image']) && $data['image'] instanceof \Illuminate\Http\UploadedFile) {
            if ($note->image) {
                Storage::disk('public')->delete($note->image);
            }
            $data['image'] = $data['image']->store('notes', 'public');
        }

        $filteredData = array_filter($data, fn($value) => !is_null($value));
        $note->update($filteredData);

        if (isset($data['tag_ids']) && !is_null($data['tag_ids'])) {
            $note->tags()->sync($data['tag_ids']);
        }
        
        return $note->load('tags');
    }

    public function delete(int $id, int $userId): bool
    {
        $note = Note::query()->where('user_id', $userId)->findOrFail($id);
        
        if ($note->image) {
            Storage::disk('public')->delete($note->image);
        }

        return (bool) $note->delete();
    }
}
