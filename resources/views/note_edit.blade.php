<x-app-layout>
    @php
        $tagId = request()->query('tag');
        $cancelRoute = route('notes.view', $note->id) . ($tagId ? '?tag=' . $tagId : '');
    @endphp
    <div x-data="{
        title: @js($note->title),
        content: @js($note->content),
        selectedTagIds: @js($note->tags->pluck('id')),
        allTags: @js($allTags),
        saving: false,
        toggleTag(id) {
            const index = this.selectedTagIds.indexOf(id);
            if (index === -1) {
                this.selectedTagIds.push(id);
            } else {
                this.selectedTagIds.splice(index, 1);
            }
        },
        isTagSelected(id) {
            return this.selectedTagIds.includes(id);
        },
        save() {
            this.saving = true;
            fetch('/api/notes/{{ $note->id }}', {
                method: 'PUT',
                headers: {
                    'Content-Type': 'application/json',
                    'X-CSRF-TOKEN': document.querySelector('meta[name=csrf-token]').getAttribute('content'),
                    'Accept': 'application/json'
                },
                body: JSON.stringify({
                    title: this.title,
                    content: this.content,
                    tag_ids: this.selectedTagIds
                })
            })
            .then(res => res.json())
            .then(data => {
                if (data.status === 'success') {
                    window.location.href = '{{ $cancelRoute }}';
                } else {
                    alert('Gagal menyimpan perubahan');
                }
            })
            .catch(err => {
                console.error(err);
                alert('Terjadi kesalahan sistem');
            })
            .finally(() => this.saving = false);
        }
    }">
        <!-- Back to View at top left below navbar -->
        <div class="w-[98%] mx-auto mb-2 px-4 sm:px-0">
            <a href="{{ $cancelRoute }}" wire:navigate class="inline-flex items-center gap-2 text-[#2E7D32] hover:text-[#1B5E20] font-semibold transition-all hover:-translate-x-1">
                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18" />
                </svg>
                Batal & Kembali
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
                        <div class="mb-8 relative z-30" x-data="{ showTagSelector: false }">
                            <input type="text" x-model="title" 
                                class="w-full bg-transparent border-none p-0 text-3xl sm:text-5xl font-black text-[#2E7D32] leading-tight focus:ring-0 placeholder-[#2E7D32]/20 mb-4"
                                placeholder="Judul Catatan...">
                            
                            <div class="flex flex-wrap items-center gap-2 mt-4">
                                <!-- Add Tag Button & Dropdown -->
                                <div class="relative">
                                    <button @click="showTagSelector = !showTagSelector" 
                                        class="px-4 py-1.5 bg-[#2E7D32]/5 text-[#2E7D32] rounded-full text-xs font-black tracking-widest uppercase border border-[#2E7D32]/10 hover:bg-[#2E7D32]/10 transition-all flex items-center gap-2">
                                        <svg class="w-3 h-3" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="3" d="M12 4v16m8-8H4" />
                                        </svg>
                                        <span>Tambah Tag</span>
                                    </button>

                                    <!-- Tag Selector Dropdown -->
                                    <div x-show="showTagSelector" 
                                        @click.away="showTagSelector = false"
                                        x-transition:enter="transition ease-out duration-200"
                                        x-transition:enter-start="opacity-0 scale-95 translate-y-2"
                                        x-transition:enter-end="opacity-100 scale-100 translate-y-0"
                                        class="absolute left-0 top-full mt-3 w-64 bg-white rounded-2xl shadow-2xl border border-[#2E7D32]/10 p-4 z-50">
                                        <p class="text-[9px] font-black text-[#2E7D32]/30 tracking-widest uppercase mb-3 px-2">Pilih Kategori</p>
                                        <div class="max-h-48 overflow-y-auto space-y-1 pr-1 custom-scrollbar">
                                            <template x-for="tag in allTags" :key="tag.id">
                                                <button @click="toggleTag(tag.id)" 
                                                    class="w-full text-left px-3 py-2.5 rounded-xl text-xs font-bold transition-all flex items-center justify-between group"
                                                    :class="isTagSelected(tag.id) ? 'bg-[#2E7D32] text-white' : 'text-[#2E7D32]/70 hover:bg-[#2E7D32]/5'">
                                                    <span x-text="tag.name"></span>
                                                    <span x-show="isTagSelected(tag.id)">✓</span>
                                                </button>
                                            </template>
                                        </div>
                                    </div>
                                </div>

                                <!-- Selected Tags Badges -->
                                <template x-for="tagId in selectedTagIds" :key="tagId">
                                    <div class="px-4 py-1.5 bg-[#2E7D32] text-white rounded-full text-xs font-bold tracking-wide uppercase border border-[#2E7D32]/5 flex items-center gap-2">
                                        <span x-text="allTags.find(t => t.id === tagId)?.name"></span>
                                        <button @click="toggleTag(tagId)" class="hover:text-red-200">
                                            <svg class="w-3 h-3" fill="currentColor" viewBox="0 0 20 20">
                                                <path fill-rule="evenodd" d="M4.293 4.293a1 1 0 011.414 0L10 8.586l4.293-4.293a1 1 0 111.414 1.414L11.414 10l4.293 4.293a1 1 0 01-1.414 1.414L10 11.414l-4.293 4.293a1 1 0 01-1.414-1.414L8.586 10 4.293 5.707a1 1 0 010-1.414z" clip-rule="evenodd"/>
                                            </svg>
                                        </button>
                                    </div>
                                </template>
                            </div>
                        </div>

                        <!-- Separator -->
                        <div class="h-px bg-[#2E7D32]/10 w-full mb-8"></div>

                        <!-- Note Content -->
                        <div class="relative min-h-[500px]">
                            <textarea x-model="content" 
                                class="w-full bg-transparent border-none p-0 text-lg sm:text-xl leading-relaxed text-[#2E7D32]/90 font-medium whitespace-pre-wrap pt-2 focus:ring-0 min-h-[500px] resize-none"
                                placeholder="Tuliskan isi pikiran Anda di sini..."></textarea>
                        </div>

                        <!-- Footer with actions -->
                        <div class="mt-12 pt-6 border-t border-[#2E7D32]/10 flex flex-col sm:flex-row justify-between items-start sm:items-center gap-4">
                            <div class="text-xs text-[#2E7D32]/50 font-mono">
                                <div class="flex items-center gap-2">
                                    <span class="w-1.5 h-1.5 rounded-full bg-[#2E7D32]/20"></span>
                                    <span>Mengedit catatan...</span>
                                </div>
                            </div>
                            <div class="flex gap-4 w-full sm:w-auto">
                                <a href="{{ $cancelRoute }}" class="flex-1 sm:flex-none text-center px-6 py-2 border border-[#2E7D32]/10 text-[#2E7D32]/60 rounded-lg font-medium text-xs hover:bg-[#2E7D32]/5 transition-all">
                                    Batal
                                </a>
                                <button @click="save()" :disabled="saving"
                                    class="flex-1 sm:flex-none px-8 py-2 bg-[#2E7D32] text-white rounded-lg font-medium text-xs hover:bg-[#1B5E20] transition-all shadow-md hover:shadow-lg active:scale-95 disabled:opacity-50 disabled:cursor-not-allowed">
                                    <span x-show="!saving">Simpan Perubahan</span>
                                    <span x-show="saving">Menyimpan...</span>
                                </button>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</x-app-layout>
