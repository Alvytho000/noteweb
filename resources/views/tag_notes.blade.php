<x-app-layout>
    <div class="max-w-7xl mx-auto p-6" x-data="{ loading: false }">
        <!-- Header -->
        <div class="flex items-center justify-between mb-8">
            <div>
                <h2 class="text-4xl font-black text-[#1B5E20] tracking-tighter italic uppercase">
                    {{ $tag->name }}
                </h2>
                <p class="text-[#1B5E20]/60 mt-2 font-medium">
                    {{ $tag->notes->count() }} Catatan terkait
                </p>
            </div>
            <a href="{{ route('web.tags') }}"
               class="inline-flex items-center gap-2 px-4 py-2 bg-[#1B5E20] text-white rounded-full font-black text-sm hover:bg-[#4CAF50] transition-colors">
                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 19l-7-7 7-7" />
                </svg>
                Kembali ke Tag
            </a>
        </div>

        <!-- Notes Grid -->
        <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 xl:grid-cols-4 gap-6">
            @forelse($tag->notes as $note)
                <div class="group bg-white/70 backdrop-blur-sm border border-[#1B5E20]/5 rounded-[3rem] p-8 shadow-sm hover:shadow-2xl hover:-translate-y-2 transition-all duration-500 flex flex-col h-full">
                    <div class="flex items-start justify-between mb-4">
                        <h3 class="text-2xl font-black text-[#1B5E20] italic">{{ $note->title }}</h3>
                        <span class="text-[10px] font-black text-[#4CAF50] uppercase">{{ $note->created_at->diffForHumans() }}</span>
                    </div>
                    <div class="flex-grow mb-4">
                        <p class="text-[#1B5E20]/70 text-sm leading-relaxed" style="display: -webkit-box; -webkit-line-clamp: 5; -webkit-box-orient: vertical; overflow: hidden;">
                            {{ $note->content }}
                        </p>
                    </div>
                    <div class="pt-4 border-t border-[#1B5E20]/5 flex justify-end">
                        <a href="{{ route('notes.show', $note->id) }}"
                           class="text-[9px] font-black tracking-[0.3em] text-[#4CAF50] uppercase flex items-center gap-2 group-hover:gap-3 transition-all">
                            <span>Lihat Detail</span>
                            <svg class="w-3 h-3" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="3" d="M17 8l4 4m0 0l-4 4m4-4H3" />
                            </svg>
                        </a>
                    </div>
                </div>
            @empty
                <div class="col-span-full text-center py-12 text-[#1B5E20]/60">
                    <p class="text-xl font-black uppercase">Tidak ada catatan untuk tag ini.</p>
                </div>
            @endforelse
        </div>
    </div>
</x-app-layout>
