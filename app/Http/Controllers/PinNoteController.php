<?php

namespace App\Http\Controllers;

use App\Http\Resources\NoteResource;
use App\Models\Note;
use Illuminate\Http\Request;

class PinNoteController extends Controller
{
    public function __invoke(Note $note)
    {
        $note->update(['is_pinned' => !$note->is_pinned]);
        
        return response()->json([
            'status' => 'success',
            'data' => new NoteResource($note)
        ]);
    }
}
