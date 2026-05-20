<x-app-layout>
    @php
        $tagId = request()->query('tag');
        $backRoute = $tagId ? route('tags.show', $tagId) : route('dashboard');
        $backText = $tagId ? 'Kembali ke Kategori' : 'Kembali ke Dashboard';
        $editRoute = route('notes.edit', $note->id) . ($tagId ? '?tag=' . $tagId : '');
    @endphp
    <!-- Back to Dashboard at top left below navbar -->
    <div class="w-[98%] mx-auto mb-2 px-4 sm:px-0">
        <a href="{{ $backRoute }}" wire:navigate class="inline-flex items-center gap-2 text-[#2E7D32] hover:text-[#1B5E20] font-semibold transition-all hover:-translate-x-1">
            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M10 19l-7-7m0 0l7-7m-7 7h18" />
            </svg>
            <span>{{ $backText }}</span>
        </a>
    </div>

    <!-- Main content centered with 98% width (1% margins) -->
    <div class="min-h-screen flex items-start justify-center pt-0 pb-20">
        <!-- Main Notepad Container - 98% width and centered -->
        <div class="relative w-[98%] mx-auto bg-[#FFFEF5] rounded-lg shadow-2xl border border-[#2E7D32]/10 overflow-hidden">
            <!-- Top Edge - Simple and Clean -->
            <div class="h-2 bg-[#2E7D32]/20 border-b border-[#2E7D32]/10"></div>

            <div class="px-6 sm:px-12 py-10">
                <div class="border-l-2 border-[#2E7D32]/10 pl-6 sm:pl-10">
                    <!-- Title Area -->
                    <div class="mb-8 relative z-30">
                        <h1 class="text-3xl sm:text-5xl font-black text-[#2E7D32] leading-tight mb-4">
                            {{ $note->title }}
                        </h1>
                        <div class="flex flex-wrap gap-3">
                            @foreach($note->tags as $tag)
                            <span class="px-4 py-1.5 bg-[#2E7D32]/10 text-[#2E7D32] rounded-full text-xs font-bold tracking-wide uppercase border border-[#2E7D32]/5">
                                {{ $tag->name }}
                            </span>
                            @endforeach
                        </div>
                    </div>

                    <!-- Separator -->
                    <div class="h-px bg-[#2E7D32]/10 w-full mb-8"></div>

                    <!-- Note Content -->
                    <div class="relative min-h-[500px]">
                        <!-- Content area -->
                        <div class="relative z-10 text-lg sm:text-xl leading-relaxed text-[#2E7D32]/90 font-medium whitespace-pre-wrap pt-2">{{ $note->content }}</div>
                    </div>

                    <!-- Footer with dates -->
                    <div class="mt-12 pt-6 border-t border-[#2E7D32]/10 flex flex-col sm:flex-row justify-between items-start sm:items-center gap-4">
                        <div class="text-xs text-[#2E7D32]/50 font-mono">
                            <div class="flex items-center gap-2">
                                <span class="w-1.5 h-1.5 rounded-full bg-[#2E7D32]/20"></span>
                                <span>Dibuat: {{ $note->created_at->format('d M Y') }}</span>
                            </div>
                            <div class="flex items-center gap-2 mt-1">
                                <span class="w-1.5 h-1.5 rounded-full bg-[#2E7D32]/20"></span>
                                <span>Diperbarui: {{ $note->updated_at->format('d M Y') }}</span>
                            </div>
                        </div>
                        <div class="flex gap-4 w-full sm:w-auto">
                            <button onclick="if(confirm('Yakin ingin menghapus catatan ini?')) fetch('/api/notes/{{ $note->id }}', { method: 'DELETE', headers: { 'X-CSRF-TOKEN': document.querySelector('meta[name=csrf-token]').getAttribute('content'), 'Accept': 'application/json' } }).then(res => res.json()).then(data => { if(data.status === 'success') window.location.href = '{{ route('dashboard') }}'; });" class="flex-1 sm:flex-none px-6 py-2 bg-[#FF0F0F] text-white rounded-lg font-medium text-xs hover:bg-[#CB0000] transition-all shadow-md hover:shadow-lg active:scale-95">
                                Hapus
                            </button>
                            <button onclick="window.location.href = '{{ $editRoute }}'" class="flex-1 sm:flex-none px-6 py-2 bg-[#2E7D32] text-white rounded-lg font-medium text-xs hover:bg-[#1B5E20] transition-all shadow-md hover:shadow-lg active:scale-95">
                                Edit Catatan
                            </button>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>


</x-app-layout>