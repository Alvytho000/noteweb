<x-app-layout>
    <div class="max-w-7xl mx-auto p-6" x-data="{
        search: '',
        tagName: '{{ $tag->name }}',
        notes: @js($tag->notes),
        allTags: @js($allTags),
        natureColors: [
            'bg-[#1B5E20]/5',
            'bg-[#4CAF50]/5',
            'bg-[#F5F5DC]/40',
            'bg-white/40',
            'bg-[#DCEDC8]/30',
            'bg-[#C8E6C9]/30',
        ]
    }">
        <div class="flex flex-col sm:flex-row sm:items-center justify-between gap-4 mb-12">
            <div>
                <h2 class="text-4xl font-black text-[#1B5E20] tracking-tighter italic uppercase leading-none" x-text="tagName"></h2>
                <p class="text-[#1B5E20]/60 mt-2 font-medium tracking-wide">
                    <span x-text="notes.length"></span> Catatan terkait kategori ini
                </p>
            </div>
            <a href="{{ route('web.tags') }}"
               class="inline-flex items-center justify-center gap-2 px-6 py-3 bg-[#1B5E20] text-white rounded-2xl font-black text-xs tracking-widest uppercase hover:bg-[#4CAF50] transition-all shadow-md self-start sm:self-center">
                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M15 19l-7-7 7-7" />
                </svg>
                Kembali ke Tag
            </a>
        </div>

        <div x-show="notes.length === 0" x-cloak 
             class="flex flex-col items-center justify-center py-24 text-center" 
             x-transition:enter="transition ease-out duration-300" x-transition:enter-start="opacity-0 scale-95" x-transition:enter-end="opacity-100 scale-100">
            <div class="w-24 h-24 bg-[#1B5E20]/5 rounded-[2rem] flex items-center justify-center mb-8">
                <svg class="w-10 h-10 text-[#1B5E20]/15" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6.253v13m0-13C10.832 5.477 9.246 5 7.5 5S4.168 5.477 3 6.253v13C4.168 18.477 5.754 18 7.5 18s3.168 0.477 4.253 1.253m0-13C13.168 5.477 14.754 5 16.5 5c1.747 0 3.332 0.477 4.5 1.253v13C19.832 18.477 18.247 18 16.5 18c-1.746 0-3.332 0.477-4.253 1.253" />
                </svg>
            </div>
            <h3 class="text-2xl font-black text-[#1B5E20] italic uppercase tracking-tighter mb-2 leading-none">Tidak Ada Catatan</h3>
            <p class="text-[#1B5E20]/40 text-[9px] font-bold tracking-[0.3em] uppercase">Belum ada catatan alam yang terhubung dengan tag ini</p>
        </div>

        <div x-show="notes.length > 0" class="flex flex-col gap-4">
            <template x-for="(note, index) in notes" :key="note.id">
                <a :href="'/notes/' + note.id + '?tag=' + {{ $tag->id }}" wire:navigate
                    class="group relative bg-white/60 backdrop-blur-md rounded-2xl p-6 sm:p-7 shadow-sm hover:shadow-xl hover:-translate-y-1 transition-all duration-500 border border-[#1B5E20]/5 flex flex-col sm:flex-row sm:items-center justify-between gap-6 overflow-hidden">
                    
                    <div class="flex-grow min-w-0">
                        <div class="mb-2">
                            <h3 class="text-xl sm:text-2xl font-black text-[#1B5E20] tracking-tighter line-clamp-2 group-hover:text-[#4CAF50] transition-colors" x-text="note.title"></h3>
                        </div>
                        
                        <p class="text-[#1B5E20]/60 text-sm leading-relaxed font-medium line-clamp-1 mb-4" x-text="note.content"></p>

                        <div class="flex flex-wrap gap-2">
                            <template x-for="tag in note.tags.slice(0, 3)" :key="tag.id">
                                <span class="px-3 py-1 bg-[#1B5E20]/5 text-[#1B5E20]/80 rounded-lg text-[10px] font-bold tracking-wider uppercase border border-[#1B5E20]/5">
                                    <span x-text="tag.name"></span>
                                </span>
                            </template>
                            <template x-if="note.tags.length > 3">
                                <span class="px-3 py-1 bg-[#1B5E20]/5 text-[#1B5E20]/40 rounded-lg text-[10px] font-bold tracking-wider uppercase">
                                    +<span x-text="note.tags.length - 3"></span>
                                </span>
                            </template>
                        </div>
                    </div>

                    <div class="flex items-center justify-end shrink-0 sm:pl-8 sm:border-l border-[#1B5E20]/5">
                        <div class="w-10 h-10 rounded-full bg-[#1B5E20]/5 flex items-center justify-center group-hover:bg-[#1B5E20] group-hover:text-white transition-all duration-500">
                            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M14 5l7 7m0 0l-7 7m7-7H3"/>
                            </svg>
                        </div>
                    </div>

                    <div class="absolute top-0 left-0 w-1.5 h-full opacity-20" :class="natureColors[index % natureColors.length]"></div>
                </a>
            </template>
        </div>
    </div>
</x-app-layout>