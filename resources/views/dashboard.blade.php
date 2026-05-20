<x-app-layout>
    <div x-data="{
        selectedNote: null,
        createNoteModal: false,
        search: '',
        notes: @js($notes),
        allTags: @js($allTags),
        selectedTagIds: [],
        editMode: false,
        editingNoteId: null,
        formatDate(dateStr) {
            if(!dateStr) return '';
            const date = new Date(dateStr);
            return date.toLocaleDateString('id-ID', { year: 'numeric', month: 'long', day: 'numeric', hour: '2-digit', minute: '2-digit' }).replace(/\./g, ':');
        },
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
        submitNote() {
            const title = this.$refs.titleInput.value;
            const content = this.$refs.contentInput.value;

            if (!title || !content) return;

            const url = this.editMode ? `/api/notes/${this.editingNoteId}` : '{{ route('notes.store') }}';
            const method = this.editMode ? 'PUT' : 'POST';

            fetch(url, {
                method: method,
                headers: {
                    'Content-Type': 'application/json',
                    'X-CSRF-TOKEN': document.querySelector('meta[name=csrf-token]').getAttribute('content'),
                    'Accept': 'application/json'
                },
                body: JSON.stringify({
                    title: title,
                    content: content,
                    tag_ids: this.selectedTagIds
                })
            })
            .then(response => response.json())
            .then(data => {
                if (data.status === 'success') {
                    if (this.editMode) {
                        const index = this.notes.findIndex(n => n.id === this.editingNoteId);
                        if (index !== -1) {
                            this.notes[index] = {
                                ...this.notes[index],
                                title: data.data.title,
                                content: data.data.content,
                                tags: data.data.tags,
                                updated_at: data.data.updated_at
                            };
                        }
                    } else {
                        this.notes.unshift({
                            id: data.data.id,
                            title: data.data.title,
                            content: data.data.content,
                            created_at: data.data.created_at,
                            tags: data.data.tags
                        });
                    }
                    this.closeModal();
                    window.dispatchEvent(new CustomEvent('notify', { detail: { type: 'success', message: this.editMode ? 'Catatan berhasil diperbarui!' : 'Catatan berhasil disimpan!' }}));
                } else {
                    window.dispatchEvent(new CustomEvent('notify', { detail: { type: 'error', message: 'Gagal menyimpan catatan.' }}));
                }
            })
            .catch(error => {
                console.error('Error:', error);
                window.dispatchEvent(new CustomEvent('notify', { detail: { type: 'error', message: 'Terjadi kesalahan sistem.' }}));
            });
        },
        deleteNote() {
            if(!this.selectedNote || !confirm('Yakin ingin menghapus catatan ini?')) return;
            
            fetch(`/api/notes/${this.selectedNote.id}`, {
                method: 'DELETE',
                headers: {
                    'X-CSRF-TOKEN': document.querySelector('meta[name=csrf-token]').getAttribute('content'),
                    'Accept': 'application/json'
                }
            })
            .then(res => res.json())
            .then(data => {
                if(data.status === 'success') {
                    this.notes = this.notes.filter(n => n.id !== this.selectedNote.id);
                    this.selectedNote = null;
                    window.dispatchEvent(new CustomEvent('notify', { detail: { type: 'success', message: 'Catatan berhasil dihapus!' }}));
                } else {
                    window.dispatchEvent(new CustomEvent('notify', { detail: { type: 'error', message: 'Gagal menghapus catatan.' }}));
                }
            })
            .catch(error => {
                console.error('Error:', error);
                window.dispatchEvent(new CustomEvent('notify', { detail: { type: 'error', message: 'Terjadi kesalahan sistem.' }}));
            });
        },
        init() {
            const urlParams = new URLSearchParams(window.location.search);
            const editId = urlParams.get('edit');
            if (editId) {
                window.location.href = `/notes/${editId}/edit`;
            }
        },
        closeModal() {
            this.createNoteModal = false;
            setTimeout(() => {
                this.editMode = false;
                this.editingNoteId = null;
                this.selectedTagIds = [];
                if (this.$refs.titleInput) this.$refs.titleInput.value = '';
                if (this.$refs.contentInput) this.$refs.contentInput.value = '';
            }, 300);
        },
        lockScroll(condition) {
            document.body.style.overflow = condition ? 'hidden' : 'auto';
        },
        natureColors: [
            'bg-[#1B5E20]/5',
            'bg-[#4CAF50]/5',
            'bg-[#F5F5DC]/40',
            'bg-white/40',
            'bg-[#DCEDC8]/30',
            'bg-[#C8E6C9]/30',
        ]
    }" x-init="init(); $watch('createNoteModal', value => lockScroll(value));">

        <!-- Dashboard Header -->
        <div class="flex flex-col lg:flex-row lg:items-center justify-between gap-8 mb-12">
            <div class="shrink-0">
                <h2 class="text-4xl font-black text-[#1B5E20] leading-none tracking-tighter italic uppercase">Koleksi
                    Alam Pikiran</h2>
                <p class="text-[#1B5E20]/60 mt-2 font-medium tracking-wide">Tuliskan ide-ide segar Anda hari ini.</p>
            </div>

            <!-- Search Bar & Create Button -->
            <div class="flex flex-col items-center lg:items-end gap-4 flex-grow max-w-3xl">
                <!-- Search Input -->
                <div class="relative w-full md:max-w-md group">
                    <div
                        class="absolute inset-y-0 left-5 flex items-center pointer-events-none text-[#1B5E20]/30 group-focus-within:text-[#4CAF50] transition-colors">
                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5"
                                d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z" />
                        </svg>
                    </div>
                    <input type="text" x-model="search" placeholder="Cari ide alam Anda..."
                        class="w-full bg-white/50 border border-white/50 rounded-2xl py-4 pl-14 pr-6 text-[#1B5E20] font-bold placeholder-[#1B5E20]/20 focus:ring-4 focus:ring-[#4CAF50]/10 focus:bg-white transition-all shadow-sm">
                </div>

                <!-- Tombol Holographic -->
                <button @click="createNoteModal = true"
                    class="group relative overflow-hidden flex items-center justify-center gap-3 bg-gradient-to-br from-[#4CAF50] to-[#1B5E20] text-white px-8 py-4 rounded-2xl font-black transition-all duration-500 hover:scale-105 hover:shadow-[0_0_30px_rgba(76,175,80,0.5)] active:scale-95 shadow-xl shadow-green-900/20 shrink-0 w-full md:w-auto">
                    <div
                        class="absolute -top-[100%] -left-[100%] w-[300%] h-[300%] bg-gradient-to-b from-transparent via-white/40 to-transparent rotate-[-45deg] transition-all duration-700 ease-in-out opacity-0 group-hover:opacity-100 group-hover:translate-y-[50%] group-hover:translate-x-[50%] pointer-events-none">
                    </div>
                    <div class="relative z-10 flex items-center gap-3 tracking-widest text-[10px] uppercase font-black">
                        <div
                            class="w-5 h-5 bg-white/20 rounded-lg flex items-center justify-center group-hover:rotate-90 transition-transform duration-500">
                            <svg class="w-3 h-3" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="3"
                                    d="M12 4v16m8-8H4" />
                            </svg>
                        </div>
                        <span>Catatan Baru</span>
                    </div>
                </button>
            </div>
        </div>

        <!-- Empty State -->
        <div x-show="notes.length === 0" x-cloak class="bg-white/40 backdrop-blur-md border-2 border-dashed border-[#1B5E20]/15 rounded-[4rem] p-20 lg:p-32 flex flex-col items-center justify-center text-center mt-8" x-transition:enter="transition ease-out duration-500" x-transition:enter-start="opacity-0 translate-y-8" x-transition:enter-end="opacity-100 translate-y-0">
            <div class="relative w-48 h-48 bg-[#1B5E20]/5 rounded-full flex items-center justify-center mx-auto mb-12 group">
                <svg class="w-20 h-20 text-[#1B5E20]/10 group-hover:scale-110 group-hover:rotate-12 transition-all duration-700" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.2" d="M12 6.253v13m0-13C10.832 5.477 9.246 5 7.5 5S4.168 5.477 3 6.253v13C4.168 18.477 5.754 18 7.5 18s3.168 0.477 4.253 1.253m0-13C13.168 5.477 14.754 5 16.5 5c1.747 0 3.332 0.477 4.5 1.253v13C19.832 18.477 18.247 18 16.5 18c-1.746 0-3.332 0.477-4.253 1.253" />
                </svg>
                <div class="absolute inset-0 bg-gradient-to-br from-[#4CAF50]/10 to-transparent rounded-full opacity-0 group-hover:opacity-100 transition-opacity"></div>
            </div>
            
            <h3 class="text-5xl font-black text-[#1B5E20] mb-6 uppercase italic tracking-tighter leading-none">Belum Ada Catatan</h3>
            <p class="text-[#1B5E20]/50 font-medium max-w-xl mx-auto leading-relaxed text-sm mb-12">
                Sepertinya alam pikiran Anda masih kosong. Mulailah menuliskan inspirasi pertama Anda hari ini.
            </p>
            
            <button @click="createNoteModal = true" class="group px-12 py-5 bg-[#1B5E20] text-white rounded-full font-black text-[10px] tracking-[0.3em] uppercase italic hover:bg-[#4CAF50] hover:shadow-[0_20px_40px_rgba(76,175,80,0.3)] transition-all active:scale-95 flex items-center gap-4 shadow-xl">
                <div class="w-6 h-6 bg-white/20 rounded-lg flex items-center justify-center group-hover:rotate-90 transition-transform">
                    <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="3" d="M12 4v16m8-8H4" />
                    </svg>
                </div>
                <span>Buat Catatan Pertama</span>
            </button>
        </div>

        <!-- No Search Results -->
        <div x-show="notes.length > 0 && notes.filter(n => n.title.toLowerCase().includes(search.toLowerCase()) || n.content.toLowerCase().includes(search.toLowerCase())).length === 0" x-cloak class="flex flex-col items-center justify-center py-24 text-center" x-transition:enter="transition ease-out duration-300" x-transition:enter-start="opacity-0 scale-95" x-transition:enter-end="opacity-100 scale-100">
            <div class="w-24 h-24 bg-[#1B5E20]/5 rounded-[2rem] flex items-center justify-center mb-8">
                <svg class="w-10 h-10 text-[#1B5E20]/20" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z" />
                </svg>
            </div>
            <h3 class="text-2xl font-black text-[#1B5E20] italic uppercase tracking-tighter mb-2 leading-none">Pencarian Tidak Ditemukan</h3>
            <p class="text-[#1B5E20]/40 text-[9px] font-bold tracking-[0.3em] uppercase">Coba gunakan kata kunci alam yang lain</p>
        </div>

        <!-- List Catatan - Refined Single Column -->
        <div x-show="notes.length > 0" class="flex flex-col gap-4">
            <template x-for="note in notes.filter(n => n.title.toLowerCase().includes(search.toLowerCase()) || n.content.toLowerCase().includes(search.toLowerCase()))" :key="note.id">
                <a :href="'/notes/' + note.id" wire:navigate
                    class="group relative bg-white/60 backdrop-blur-md rounded-2xl p-6 sm:p-7 shadow-sm hover:shadow-xl hover:-translate-y-1 transition-all duration-500 border border-[#1B5E20]/5 flex flex-col sm:flex-row sm:items-center justify-between gap-6 overflow-hidden">
                    
                    <!-- Left Side: Content & Tags -->
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

                    <!-- Right Side: Action Icon -->
                    <div class="flex items-center justify-end shrink-0 sm:pl-8 sm:border-l border-[#1B5E20]/5">
                        <div class="w-10 h-10 rounded-full bg-[#1B5E20]/5 flex items-center justify-center group-hover:bg-[#1B5E20] group-hover:text-white transition-all duration-500">
                            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M14 5l7 7m0 0l-7 7m7-7H3"/>
                            </svg>
                        </div>
                    </div>

                    <!-- Subtle Nature Accent -->
                    <div class="absolute top-0 left-0 w-1.5 h-full opacity-20" :class="natureColors[note.id % natureColors.length]"></div>
                </a>
            </template>
        </div>

        <!-- 2. Modal CREATE NOTE (Form Baru) -->
        <div x-show="createNoteModal" x-cloak x-transition:enter="transition ease-out duration-300"
            x-transition:enter-start="opacity-0" x-transition:enter-end="opacity-100"
            class="fixed inset-0 z-[100] flex items-center justify-center p-4 bg-[#1B5E20]/30 backdrop-blur-xl"
            @click.self="closeModal()">

            <div x-show="createNoteModal" x-transition:enter="transition ease-out duration-500 transform"
                x-transition:enter-start="scale-95 opacity-0 -translate-y-12"
                x-transition:enter-end="scale-100 opacity-100 translate-y-0"
                class="bg-[#F5F5DC] w-full max-w-xl rounded-[2.5rem] p-8 sm:p-12 shadow-[0_30px_100px_rgba(27,94,32,0.3)] border border-white/50 relative flex flex-col max-h-[90vh]">

                <div class="text-center mb-10 shrink-0">
                    <h2 class="text-3xl font-black text-[#1B5E20] tracking-tighter uppercase italic" x-text="editMode ? 'Edit Catatan' : 'Tulis Ide Baru'"></h2>
                    <p class="text-[#1B5E20]/50 text-[10px] font-bold tracking-[0.3em] uppercase mt-2">Simpan inspirasi
                        alam Anda</p>
                </div>

                <div class="overflow-y-auto px-2 -mr-2">
                    <form @submit.prevent="submitNote" class="space-y-6">
                        <div class="space-y-1">
                            <label class="text-[9px] font-black text-[#1B5E20]/40 tracking-widest uppercase ml-4">Judul
                                Catatan</label>
                            <input x-ref="titleInput" type="text" placeholder="Mulai dengan judul yang menarik..." required
                                class="w-full bg-white/50 border-none rounded-2xl py-4 px-6 text-[#1B5E20] placeholder-[#1B5E20]/30 focus:ring-4 focus:ring-[#4CAF50]/20 transition-all font-bold">
                        </div>

                        <div class="space-y-1">
                            <label class="text-[9px] font-black text-[#1B5E20]/40 tracking-widest uppercase ml-4">Isi
                                Pikiran</label>
                            <textarea x-ref="contentInput" rows="5" placeholder="Tuliskan detail ide Anda di sini..." required
                                class="w-full bg-white/50 border-none rounded-2xl py-4 px-6 text-[#1B5E20] placeholder-[#1B5E20]/30 focus:ring-4 focus:ring-[#4CAF50]/20 transition-all font-medium resize-none"></textarea>
                        </div>

                        <!-- Tag Selector -->
                        <div class="space-y-2">
                            <label class="text-[9px] font-black text-[#1B5E20]/40 tracking-widest uppercase ml-4">Tag/Kategori</label>
                            <div class="flex flex-wrap gap-2">
                                <template x-for="tag in allTags" :key="tag.id">
                                    <button type="button" @click="toggleTag(tag.id)"
                                        :class="isTagSelected(tag.id)
                                            ? 'bg-[#1B5E20] text-white shadow-[0_0_20px_rgba(27,94,32,0.5)]'
                                            : 'bg-white/50 text-[#1B5E20]/70 hover:bg-white'"
                                        class="px-4 py-2 rounded-xl text-xs font-bold tracking-[0.2em] uppercase transition-all">
                                        <span x-text="tag.name"></span>
                                        <span x-show="isTagSelected(tag.id)" class="ml-1">✓</span>
                                    </button>
                                </template>
                            </div>
                        </div>

                        <div class="pt-6 flex gap-3">
                            <button type="submit"
                                class="flex-grow bg-[#1B5E20] text-white py-4 rounded-2xl font-black text-xs tracking-[0.2em] uppercase hover:bg-[#4CAF50] transition-all shadow-lg">Simpan
                                Catatan</button>
                            <button type="button" @click="closeModal()"
                                class="px-8 bg-white text-[#1B5E20] py-4 rounded-2xl font-black text-xs tracking-[0.2em] uppercase hover:bg-red-50 hover:text-red-500 transition-all border border-[#1B5E20]/5">Batal</button>
                        </div>
                    </form>
                </div>
            </div>
        </div>

    </div>
</x-app-layout>
