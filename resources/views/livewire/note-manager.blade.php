<div>
    <!-- Search Bar dengan Border Hijau Segar -->
    <div class="relative max-w-2xl mx-auto mb-12">
        <input wire:model.live="search" 
               type="text" 
               placeholder="Cari ide hijau Anda..." 
               class="block w-full pl-6 pr-3 py-5 border-2 border-[#4CAF50]/20 rounded-3xl bg-white shadow-lg focus:border-[#4CAF50] focus:ring-0 text-[#1B5E20] placeholder-[#1B5E20]/30 transition-all duration-300">
        <div class="absolute right-4 top-4 p-1 bg-[#F5F5DC] rounded-xl">
            <svg class="h-6 w-6 text-[#4CAF50]" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"/>
            </svg>
        </div>
    </div>

    <!-- Grid -->
    <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-8">
        @forelse($notes as $note)
            <div class="relative group">
                <x-note-card :note="$note" />
                
                <!-- Tombol Hapus dengan tema alam -->
                <button wire:click="deleteNote({{ $note->id }})" 
                        wire:confirm="Hapus catatan ini?"
                        class="absolute -top-3 -right-3 opacity-0 group-hover:opacity-100 bg-[#1B5E20] hover:bg-red-700 text-white p-2.5 rounded-2xl shadow-xl transition-all duration-300 transform hover:rotate-12">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16" />
                    </svg>
                </button>
            </div>
        @empty
            <div class="col-span-full text-center py-24 bg-white/40 rounded-[3rem] border-4 border-dashed border-[#4CAF50]/10">
                <p class="text-4xl mb-4">🍃</p>
                <h3 class="text-2xl font-bold text-[#1B5E20]">Belum ada benih ide</h3>
                <p class="text-[#1B5E20]/50">Mulai tuliskan sesuatu yang segar hari ini.</p>
            </div>
        @endforelse
    </div>
</div>
