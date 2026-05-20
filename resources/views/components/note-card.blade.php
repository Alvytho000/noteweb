@props(['note'])

<div class="p-4 rounded-lg shadow-md mb-4 border-l-4 transition hover:scale-[1.02]" style="background-color: {{ $note->color }}; border-color: rgba(0,0,0,0.1);">
    
    <div class="flex justify-between items-start">
        <h3 class="font-bold text-lg text-gray-800">{{ $note->title }}</h3>
        @if ($note->is_pinned)
            <span class="text-blue-500 text-sm">📌</span>
        @endif
    </div>

    <p class="text-gray-600 mt-2 line-clamp-3">
        {{ $note->content }}
    </p>

    <div class="mt-4 flex justify-between items-center text-xs text-gray-500">
        <span>{{ $note->created_at->diffForHumans() }}</span>

        <div class="flex gap-2">
            @foreach ($note->tags as $tag)
                <span class="px-2 py-1 bg-white/50 rounded-full border border-black/5">
                    #{{ $tag->name }}
                </span>
            @endforeach
        </div>
    </div>
</div>
