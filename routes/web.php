<?php

use App\Http\Controllers\ProfileController;
use Illuminate\Support\Facades\Route;

Route::get('/', function () {
    return view('welcome');
});

Route::get('/dashboard', function () {
    return view('dashboard', [
        'notes' => auth()->user()->notes()->with('tags')->latest()->get(),
        'allTags' => auth()->user()->tags()->get()
    ]);
})->middleware(['auth', 'verified'])->name('dashboard');

Route::middleware(['auth', 'verified'])->group(function () {
    Route::get('/tags', function () {
        $user = auth()->user();
        $tags = $user->tags()
            ->withCount('notes')
            ->with(['notes' => function($query) {
                $query->latest()->limit(3);
            }])
            ->get();
            
        $totalNotes = $user->notes()->count();
        $mostUsedTag = $tags->sortByDesc('notes_count')->first();

        return view('tags', [
            'tags' => $tags,
            'stats' => [
                'total_tags' => $tags->count(),
                'total_notes' => $totalNotes,
                'most_used_tag' => $mostUsedTag ? $mostUsedTag->name : 'N/A'
            ]
        ]);
    })->name('web.tags');
        // Route to display notes for a specific tag
        Route::get('/tags/{tag}', function ($tag) {
            $user = auth()->user();
            $tagModel = $user->tags()->with(['notes' => function($query) {
                $query->latest();
            }])->findOrFail($tag);
            return view('tag_notes', ['tag' => $tagModel]);
        })->name('tags.show');

    Route::get('/notes/{note}', function (\App\Models\Note $note) {
        if ($note->user_id !== auth()->id()) {
            abort(403);
        }
        return view('note_view', ['note' => $note->load('tags')]);
    })->name('notes.view');

    Route::get('/notes/{note}/edit', function (\App\Models\Note $note) {
        if ($note->user_id !== auth()->id()) {
            abort(403);
        }
        return view('note_edit', [
            'note' => $note->load('tags'),
            'allTags' => auth()->user()->tags()->get()
        ]);
    })->name('notes.edit');

    Route::get('/settings', [ProfileController::class, 'edit'])->name('web.settings');
});

Route::middleware('auth')->group(function () {
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');
});

// View Routes
Route::view('/welcome-page', 'welcome')->name('welcome-page');

// Redirect Routes
Route::redirect('/home', '/dashboard');

// Route Parameters dengan Constraints
Route::get('/user-profile/{id}', function ($id) {
    return "Profil User ID: " . $id;
})->where('id', '[0-9]+')->name('user.profile');

require __DIR__.'/auth.php';
