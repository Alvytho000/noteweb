<?php

namespace App\Livewire;

use App\Models\Note;
use Auth;
use Livewire\Component;

class NoteManager extends Component
{
    public $search = '';

    public function render()
    {
        $notes = Note::query()->where('user_id', Auth::id())->where('title', 'like', '%' . $this->search . '%')->latest()->get();

        return view('livewire.note-manager', compact('notes'));
    }

    public function deleteNote($id)
    {
        $note = Note::query()->where('user_id', Auth::id())->findOrFail($id);
        $note->delete();
    }
}
