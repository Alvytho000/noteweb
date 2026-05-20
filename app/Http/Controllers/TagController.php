<?php

namespace App\Http\Controllers;

use App\Http\Requests\StoreTagRequest;
use App\Http\Requests\UpdateTagRequest;
use App\Http\Resources\TagResource;
use App\Models\Tag;
use App\Services\TagService;
use App\Traits\ApiResponse;
use Illuminate\Support\Facades\Auth;
use Illuminate\Http\Request;

class TagController extends Controller
{
    protected $tagService;
    use ApiResponse;

    public function __construct(TagService $tagService)
    {
        $this->tagService = $tagService;
    }

    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
        // Ambil semua tag dengan pagination
        $tag = $this->tagService->getAll($request->user()->id);

        return $this->successResponse(
            TagResource::collection($tag),
            'Tags retrieved successfully'
        );
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        return $this->successResponse([
            'name' => '',
            'note_ids' => []
        ]);
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(StoreTagRequest $request)
    {
        // Panggil service untuk membangun tag
        $tag = $this->tagService->create($request->validated(), (int) Auth::id());

        return $this->successResponse(
            new TagResource($tag),
            'Tag created successfully',
            201
        );
    }

    /**
     * Display the specified resource.
     */
    public function show(Tag $tag)
    {
        // Muat relasi notes, DAN pastikan setiap note memuat relasi tags-nya juga (Eager Loading Nested)
        // serta diurutkan berdasarkan catatan terbaru
        $tag->load(['notes' => function ($query) {
            $query->with('tags')->latest();
        }]);

        return $this->successResponse(
            new TagResource($tag),
            'Tag and its nested notes retrieved successfully'
        );
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Tag $tag)
    {
        return $this->successResponse(
            new TagResource($tag->load('notes'))
        );
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(UpdateTagRequest $request, Tag $tag)
    {
        // Panggil service untuk update
        $tag = $this->tagService->update($tag->id, (int) Auth::id(), $request->validated());

        return $this->successResponse(
            new TagResource($tag),
            'Tag updated successfully',
            200
        );
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Tag $tag)
    {
        // Panggil service untuk delete
        $this->tagService->delete($tag->id, Auth::id());

        return $this->successResponse(null, 'Tag deleted successfully');
    }
}
