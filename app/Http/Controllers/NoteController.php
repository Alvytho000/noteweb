<?php

namespace App\Http\Controllers;

use App\Http\Requests\StoreNoteRequest;
use App\Http\Requests\UpdateNoteRequest;
use App\Http\Resources\NoteResource;
use App\Models\Note;
use App\Services\NoteService;
use App\Traits\ApiResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Validation\ValidationException;

class NoteController extends Controller
{
    protected $noteService;
    use ApiResponse;

    public function __construct(NoteService $noteService)
    {
        $this->noteService = $noteService;
    }

    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
        // Ambil semua note dengan pagination
        $notes = $this->noteService->getAll($request->user()->id);

        return $this->successResponse(
            NoteResource::collection($notes),
            'Notes retrieved successfully'
        );
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        return $this->successResponse([
            'title' => '',
            'content' => '',
            'tag_ids' => []
        ]);
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(StoreNoteRequest $request)
    {
        $note = $this->noteService->create($request->validated(), (int) Auth::id());

        return $this->successResponse(
            new NoteResource($note),
            'Note created successfully',
            201
        );
    }

    /**
     * Display the specified resource.
     */
    public function show(Note $note)
    {
        return $this->successResponse(
            new NoteResource($note->load('tags'))
        );
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Note $note)
    {
        return $this->successResponse(
            new NoteResource($note->load('tags'))
        );
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(UpdateNoteRequest $request, Note $note)
    {
        $note = $this->noteService->update($note->id, (int) Auth::id(), $request->validated());

        return $this->successResponse(
            new NoteResource($note),
            'Note updated successfully',
            200
        );
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Note $note)
    {
        // Panggil service untuk delete
        $this->noteService->delete($note->id, (int) Auth::id());

        return $this->successResponse(null, 'Note deleted successfully');
    }

    /**
     * Export the specified resource.
     */
    public function export(Note $note)
    {
        return response()->streamDownload(function () use ($note) {
            echo "Title: " . $note->title . "\n";
            echo "Date: " . $note->created_at->format('Y-m-d H:i:s') . "\n";
            echo "----------------------------------\n\n";
            echo $note->content;
        }, str($note->title)->slug() . '.txt');
    }
}
