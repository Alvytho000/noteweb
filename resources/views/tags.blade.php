<x-app-layout>
    <div class="max-w-7xl mx-auto" x-data="{
        search: '',
        tags: @js($tags),
        createTagModal: false,
        newTagName: '',
        get filteredTags() {
            if (this.search === '') return this.tags;
            return this.tags.filter(tag => tag.name.toLowerCase().includes(this.search.toLowerCase()));
        },
        createTag() {
            if (!this.newTagName) return;
            fetch('{{ route('tags.store') }}', {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json',
                    'X-CSRF-TOKEN': document.querySelector('meta[name=csrf-token]').getAttribute('content'),
                    'Accept': 'application/json'
                },
                body: JSON.stringify({ name: this.newTagName })
            })
            .then(async response => {
                const data = await response.json();
                if (!response.ok) {
                    throw data;
                }
                return data;
            })
            .then(data => {
                if (data.status === 'success') {
                    this.tags.unshift(data.data);
                    this.createTagModal = false;
                    this.newTagName = '';
                    window.dispatchEvent(new CustomEvent('notify', { detail: { type: 'success', message: 'Kategori berhasil dibuat!' }}));
                }
            })
            .catch(error => {
                this.newTagName = '';
                let errorMsg = 'Gagal membuat kategori.';
                if (error.errors && error.errors.name) {
                    errorMsg = error.errors.name[0];
                } else if (error.message) {
                    errorMsg = error.message;
                }
                window.dispatchEvent(new CustomEvent('notify', { detail: { type: 'error', message: errorMsg }}));
            });
        },
        deleteTag(id) {
            if (!confirm('Yakin ingin menghapus kategori ini?')) return;
            fetch(`/api/tags/${id}`, {
                method: 'DELETE',
                headers: {
                    'X-CSRF-TOKEN': document.querySelector('meta[name=csrf-token]').getAttribute('content'),
                    'Accept': 'application/json'
                }
            })
            .then(res => res.json())
            .then(data => {
                if (data.status === 'success') {
                    this.tags = this.tags.filter(t => t.id !== id);
                    window.dispatchEvent(new CustomEvent('notify', { detail: { type: 'success', message: 'Kategori berhasil dihapus!' }}));
                } else {
                    window.dispatchEvent(new CustomEvent('notify', { detail: { type: 'error', message: data.message || 'Gagal menghapus kategori.' }}));
                }
            })
            .catch(err => {
                console.error(err);
                window.dispatchEvent(new CustomEvent('notify', { detail: { type: 'error', message: 'Terjadi kesalahan saat menghapus kategori.' }}));
            });
        }
    }">
        <!-- Hero Section -->
        <div class="mb-12">
            <div class="flex flex-col lg:flex-row lg:items-start justify-between gap-6 mb-10">
                <!-- Left: Title & Subtitle -->
                <div class="flex-1">
                    <h2 class="text-5xl font-black text-[#1B5E20] leading-none tracking-tighter italic uppercase">
                        Eksplorasi Tag</h2>
                    <p class="text-[#1B5E20]/60 mt-3 font-medium tracking-wide max-w-xl">
                        Organisir pikiran Anda secara visual. Temukan keterkaitan antar ide melalui kategori yang telah
                        Anda buat.
                    </p>
                    <button @click="createTagModal = true"
                        class="mt-4 px-8 py-3 bg-[#1B5E20] text-white rounded-2xl font-black text-xs tracking-[0.2em] uppercase hover:bg-[#4CAF50] transition-all shadow-lg">
                        Buat Tag Baru
                    </button>
                </div>

                <!-- Right: Search Bar & Total Tags -->
                <div class="flex flex-col gap-2 w-full lg:w-80 shrink-0">
                    <!-- Search Bar -->
                    <div class="relative w-full group">
                        <div
                            class="absolute inset-y-0 left-5 flex items-center pointer-events-none text-[#1B5E20]/30 group-focus-within:text-[#4CAF50] transition-colors z-10">
                            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5"
                                    d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z" />
                            </svg>
                        </div>

                        <input type="text" x-model="search" placeholder="Cari kategori..."
                            class="w-full bg-white/70 backdrop-blur-md border border-[#1B5E20]/10 rounded-2xl py-4 pl-14 pr-6 text-sm font-bold text-[#1B5E20] placeholder-[#1B5E20]/30 focus:outline-none focus:ring-2 focus:ring-[#4CAF50]/20 focus:border-[#4CAF50] transition-all shadow-sm relative">
                    </div>
                    <div class="flex lg:justify-end">
                        <div
                            class="bg-white/60 backdrop-blur-md border border-[#1B5E20]/5 rounded-[1rem] px-6 py-4 shadow-sm flex items-center gap-4 w-full lg:w-auto">
                            <div
                                class="w-10 h-10 bg-[#4CAF50]/10 rounded-xl flex items-center justify-center text-[#4CAF50]">
                                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                        d="M7 7h.01M7 3h5c.512 0 1.024.195 1.414.586l7 7a2 2 0 010 2.828l-7 7a2 2 0 01-2.828 0l-7-7A1.994 1.994 0 013 12V7a4 4 0 014-4z" />
                                </svg>
                            </div>
                            <div>
                                <p class="text-[10px] font-black text-[#1B5E20]/40 tracking-[0.2em] uppercase">Total
                                    Kategori</p>
                                <h4 class="text-2xl font-black text-[#1B5E20] italic leading-tight" x-text="tags.length"></h4>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Tags Grid -->
            <template x-if="filteredTags.length > 0">
                <div class="grid grid-cols-1 sm:grid-cols-2 md:grid-cols-3 lg:grid-cols-4 xl:grid-cols-5 gap-4">
                    <template x-for="tag in filteredTags" :key="tag.id">
                        <div @click="window.location.href = `{{ url('tags') }}/${tag.id}`"
                            class="group h-32 flex flex-col items-center justify-center bg-white/80 backdrop-blur-sm border border-[#1B5E20]/10 rounded-xl p-4 shadow-sm hover:shadow-md hover:border-[#4CAF50]/30 hover:bg-[#4CAF50]/5 transition-all cursor-pointer relative">

                            <!-- Title -->
                            <span class="text-center text-sm font-bold text-[#1B5E20] tracking-tight italic uppercase group-hover:text-[#4CAF50] transition-colors truncate w-full px-2"
                                x-text="tag.name" :title="tag.name"></span>

                            <!-- Notes Badge -->
                            <span class="text-[11px] font-black text-[#1B5E20]/60 tracking-wider uppercase bg-[#1B5E20]/5 px-3 py-1 rounded-full mt-3"
                                x-text="`${tag.notes_count} Notes`"></span>

                            <!-- Delete Button -->
                            <button @click.stop.prevent="deleteTag(tag.id)"
                                class="absolute top-2 right-2 text-[#1B5E20]/30 hover:text-red-500 transition-colors hover:bg-red-50 p-2 rounded-full"
                                title="Hapus Kategori">
                                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5"
                                        d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16" />
                                </svg>
                            </button>
                        </div>
                    </template>
                </div>
            </template>

            <!-- No Results Search -->
            <template x-if="filteredTags.length === 0 && search !== ''">
                <div
                    class="py-20 text-center bg-white/40 backdrop-blur-sm rounded-[3rem] border border-dashed border-[#1B5E20]/20">
                    <div class="w-20 h-20 bg-[#1B5E20]/5 rounded-full flex items-center justify-center mx-auto mb-6">
                        <svg class="w-10 h-10 text-[#1B5E20]/20" fill="none" stroke="currentColor"
                            viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z" />
                        </svg>
                    </div>
                    <h3 class="text-xl font-black text-[#1B5E20] mb-2 uppercase italic">Tidak Menemukan "<span
                            x-text="search"></span>"</h3>
                    <p class="text-[#1B5E20]/60 font-medium">Coba gunakan kata kunci lain atau buat kategori baru.</p>
                </div>
            </template>

            <!-- Empty State (No Tags at all) -->
            <template x-if="tags.length === 0">
                <div class="bg-white/40 backdrop-blur-md border-2 border-dashed border-[#1B5E20]/15 rounded-[4rem] p-24 lg:p-32 flex flex-col items-center justify-center text-center mt-8" x-transition:enter="transition ease-out duration-500" x-transition:enter-start="opacity-0 translate-y-8" x-transition:enter-end="opacity-100 translate-y-0">
                    <div class="relative w-48 h-48 bg-[#1B5E20]/5 rounded-full flex items-center justify-center mx-auto mb-12 group">
                        <svg class="w-20 h-20 text-[#1B5E20]/10 group-hover:scale-110 group-hover:rotate-12 transition-all duration-700" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.2" d="M7 7h.01M7 3h5c.512 0 1.024.195 1.414.586l7 7a2 2 0 010 2.828l-7 7a2 2 0 01-2.828 0l-7-7A1.994 1.994 0 013 12V7a4 4 0 014-4z" />
                        </svg>
                        <div class="absolute inset-0 bg-gradient-to-br from-[#4CAF50]/10 to-transparent rounded-full opacity-0 group-hover:opacity-100 transition-opacity"></div>
                    </div>
                    
                    <h3 class="text-5xl font-black text-[#1B5E20] mb-6 uppercase italic tracking-tighter">Kategori Kosong</h3>
                    <p class="text-[#1B5E20]/50 font-medium max-w-xl mx-auto leading-relaxed text-sm">
                        Mulai kelompokkan catatan Anda untuk mempermudah pencarian di masa depan. Tambahkan tag saat Anda membuat atau mengedit catatan.
                    </p>
                    
                    <a href="{{ route('dashboard') }}" class="inline-flex items-center gap-4 mt-12 px-12 py-5 bg-[#1B5E20] text-white rounded-full font-black text-[10px] tracking-[0.3em] uppercase italic hover:bg-[#4CAF50] hover:shadow-[0_20px_40px_rgba(27,94,32,0.3)] transition-all shadow-xl group">
                        <span>Kembali ke Dashboard</span>
                        <svg class="w-4 h-4 group-hover:translate-x-1 transition-transform" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="3" d="M17 8l4 4m0 0l-4 4m4-4H3" />
                        </svg>
                    </a>
                </div>
            </template>

        </div>

    <!-- Create Tag Modal -->
    <div x-show="createTagModal" x-cloak x-transition:enter="transition ease-out duration-300"
        x-transition:enter-start="opacity-0" x-transition:enter-end="opacity-100"
        class="fixed inset-0 z-[100] flex items-center justify-center p-4 bg-[#1B5E20]/30 backdrop-blur-xl"
        @click.self="createTagModal = false; newTagName = '';">
        <div class="bg-[#F5F5DC] w-full max-w-md rounded-[2rem] p-8 sm:p-10 shadow-[0_30px_100px_rgba(27,94,32,0.3)] border border-white/50 relative">
            <h2 class="text-2xl font-black text-[#1B5E20] mb-4 text-center">Buat Tag Baru</h2>

            <input type="text" x-model="newTagName" placeholder="Nama tag..."
                class="w-full mb-4 bg-white/50 border-none rounded-xl py-3 px-4 text-[#1B5E20] focus:ring-2 focus:ring-[#4CAF50]/20 transition-all" />
            <div class="flex justify-end gap-2">
                <button @click="createTag()" class="px-4 py-2 bg-[#1B5E20] text-white rounded-xl hover:bg-[#4CAF50] transition-colors">Simpan</button>
                <button @click="createTagModal = false; newTagName = '';" class="px-4 py-2 bg-white text-[#1B5E20] rounded-xl border border-[#1B5E20]/5 hover:bg-red-50 hover:text-red-500 transition-colors">Batal</button>
            </div>
        </div>
    </div>
</x-app-layout>
