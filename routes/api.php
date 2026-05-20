<?php

use App\Http\Controllers\NoteController;
use App\Http\Controllers\PinNoteController;
use App\Http\Controllers\TagController;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Route;

// 1. Basic Routing & Route Group
// 2. Advanced Routing: Rate Limiting (throttle:api)
Route::middleware(['throttle:api', 'auth:web,sanctum'])->group(function () {
    Route::get('/user', function (Request $request) {
        return $request->user();
    });

    // 3. Named Routes & Prefixing
    // Route Tes untuk Query Scope (Catatan Penting Saja)
    Route::get('/notes/only-pinned', function() {
        $pinnedNotes = \App\Models\Note::query()->where('user_id', Auth::id())->pinned()->get();
        return \App\Http\Resources\NoteResource::collection($pinnedNotes);
    });

    Route::prefix('notes')->name('notes.')->group(function () {
        Route::get('/', [NoteController::class, 'index'])->name('index');
        Route::post('/', [NoteController::class, 'store'])->name('store');

        Route::middleware('can:view,note')->group(function () {
            Route::get('/{note}', [NoteController::class, 'show'])->name('show');
            Route::put('/{note}', [NoteController::class, 'update'])->name('update');
            Route::delete('/{note}', [NoteController::class, 'destroy'])->name('destroy');
            Route::get('/{note}/export', [NoteController::class, 'export'])->name('export');
            Route::post('/{note}/pin', PinNoteController::class)->name('pin');
        });
    });

    Route::prefix('tags')->name('tags.')->group(function () {
        Route::get('/', [TagController::class, 'index'])->name('index');
        Route::post('/', [TagController::class, 'store'])->name('store');
        
        Route::middleware('can:view,tag')->group(function () {
            Route::get('/{tag}', [TagController::class, 'show'])->name('show');
            Route::put('/{tag}', [TagController::class, 'update'])->name('update');
            Route::delete('/{tag}', [TagController::class, 'destroy'])->name('destroy');
        });
    });
});

// Route::get('/dev-token', function () {
//     return Auth::user()->createToken('dev-token')->plainTextToken;
// })->middleware('auth:web');
