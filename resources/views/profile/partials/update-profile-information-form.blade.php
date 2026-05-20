<section x-data="{ editing: false }">
    <header class="flex flex-col sm:flex-row justify-between items-start gap-4 mb-10">
        <div class="space-y-1">
            <h2 class="text-3xl font-black text-[#1B5E20] italic uppercase tracking-tighter leading-none">
                {{ __('Profile Information') }}
            </h2>
            <p class="text-sm text-[#1B5E20]/50 font-bold tracking-widest uppercase">
                {{ __("Manage your identity and email address") }}
            </p>
        </div>
    </header>

    <!-- Read Only View -->
    <div x-show="!editing" x-transition:enter="transition ease-out duration-300" x-transition:enter-start="opacity-0 translate-y-2" x-transition:enter-end="opacity-100 translate-y-0" class="space-y-8">
        <div class="group">
            <label class="text-[9px] font-black text-[#1B5E20]/40 tracking-[0.3em] uppercase ml-1 mb-2 block">Display Name</label>
            <div class="text-lg font-bold text-[#1B5E20] bg-white/40 rounded-2xl px-6 py-4 border border-[#1B5E20]/5 group-hover:border-[#4CAF50]/20 transition-all flex items-center gap-4">
                <div class="w-10 h-10 bg-[#4CAF50]/10 rounded-xl flex items-center justify-center text-[#4CAF50]">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z" /></svg>
                </div>
                {{ $user->name }}
            </div>
        </div>

        <div class="group">
            <label class="text-[9px] font-black text-[#1B5E20]/40 tracking-[0.3em] uppercase ml-1 mb-2 block">Email Address</label>
            <div class="text-lg font-bold text-[#1B5E20] bg-white/40 rounded-2xl px-6 py-4 border border-[#1B5E20]/5 group-hover:border-[#4CAF50]/20 transition-all flex items-center gap-4">
                <div class="w-10 h-10 bg-[#4CAF50]/10 rounded-xl flex items-center justify-center text-[#4CAF50]">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M3 8l7.89 5.26a2 2 0 002.22 0L21 8M5 19h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z" /></svg>
                </div>
                {{ $user->email }}
            </div>
        </div>

        <div class="flex pt-4">
            <button @click="editing = true" class="group relative overflow-hidden flex items-center justify-center gap-2 bg-[#1B5E20] text-white px-8 py-3 rounded-xl font-black text-[10px] tracking-widest uppercase italic transition-all hover:scale-105 hover:bg-[#4CAF50] shadow-lg active:scale-95">
                <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z" />
                </svg>
                <span>Edit Profile</span>
            </button>
        </div>
    </div>

    <!-- Edit Form View -->
    <div x-show="editing" x-cloak x-transition:enter="transition ease-out duration-300" x-transition:enter-start="opacity-0 translate-y-2" x-transition:enter-end="opacity-100 translate-y-0">
        <form method="post" action="{{ route('profile.update') }}" class="space-y-6">
            @csrf
            @method('patch')

            <div class="space-y-2">
                <label for="name" class="text-[9px] font-black text-[#1B5E20]/40 tracking-[0.3em] uppercase ml-4 block">Nama Lengkap</label>
                <input id="name" name="name" type="text" value="{{ old('name', $user->name) }}" required autofocus
                    class="w-full bg-white/80 border-none rounded-2xl py-4 px-6 text-[#1B5E20] font-bold placeholder-[#1B5E20]/20 focus:ring-4 focus:ring-[#4CAF50]/20 transition-all shadow-sm" />
                <x-input-error class="mt-2" :messages="$errors->get('name')" />
            </div>

            <div class="space-y-2">
                <label for="email" class="text-[9px] font-black text-[#1B5E20]/40 tracking-[0.3em] uppercase ml-4 block">Alamat Email</label>
                <input id="email" name="email" type="email" value="{{ old('email', $user->email) }}" required
                    class="w-full bg-white/80 border-none rounded-2xl py-4 px-6 text-[#1B5E20] font-bold placeholder-[#1B5E20]/20 focus:ring-4 focus:ring-[#4CAF50]/20 transition-all shadow-sm" />
                <x-input-error class="mt-2" :messages="$errors->get('email')" />
            </div>

            <div class="flex items-center gap-3 pt-6 border-t border-[#1B5E20]/5">
                <button type="submit" class="flex-grow sm:flex-none bg-[#1B5E20] text-white px-10 py-4 rounded-xl font-black text-[11px] tracking-widest uppercase italic hover:bg-[#4CAF50] transition-all shadow-xl active:scale-95">Save Changes</button>
                <button type="button" @click="editing = false" class="flex-grow sm:flex-none px-10 py-4 bg-white text-[#1B5E20] rounded-xl font-black text-[11px] tracking-widest uppercase italic border border-[#1B5E20]/5 hover:bg-red-50 hover:text-red-500 transition-all active:scale-95">Cancel</button>
            </div>
        </form>
    </div>

    @if (session('status') === 'profile-updated')
        <div x-init="$nextTick(() => $dispatch('notify', { message: 'Profil berhasil diperbarui!', type: 'success' }))"></div>
    @endif
</section>
