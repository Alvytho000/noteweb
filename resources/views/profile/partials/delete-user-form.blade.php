<section class="space-y-6">
    <header>
        <h2 class="text-2xl font-black text-red-600 italic uppercase tracking-tighter leading-none">
            {{ __('Delete Account') }}
        </h2>
        <p class="mt-2 text-sm text-[#1B5E20]/60 font-medium leading-relaxed tracking-wide">
            {{ __('Setelah akun Anda dihapus, semua sumber daya dan datanya akan dihapus secara permanen. Sebelum menghapus akun, harap unduh data apa pun yang ingin Anda simpan.') }}
        </p>
    </header>

    <button
        x-data=""
        x-on:click.prevent="$dispatch('open-modal', 'confirm-user-deletion')"
        class="bg-red-600/10 text-red-600 border border-red-600/20 px-8 py-3 rounded-xl font-black text-[10px] tracking-widest uppercase italic hover:bg-red-600 hover:text-white transition-all active:scale-95 shadow-sm"
    >{{ __('Delete Account') }}</button>

    <template x-teleport="body">
        <x-modal name="confirm-user-deletion" :show="$errors->userDeletion->isNotEmpty()" maxWidth="3xl" focusable>
            <form method="post" action="{{ route('profile.destroy') }}" class="p-8 sm:p-12 bg-white rounded-[2.5rem]">
                @csrf
                @method('delete')

                <h2 class="text-3xl font-black text-[#1B5E20] italic uppercase tracking-tighter leading-tight mb-2">
                    {{ __('Are you sure?') }}
                </h2>

                <p class="text-sm text-[#1B5E20]/60 font-medium mb-8">
                    {{ __('Tindakan ini tidak dapat dibatalkan. Harap masukkan email dan kata sandi Anda untuk mengonfirmasi penghapusan akun secara permanen.') }}
                </p>

                <div class="space-y-6">
                    <!-- Email Confirmation -->
                    <div class="space-y-1">
                        <label for="email" class="text-[9px] font-black text-[#1B5E20]/40 tracking-widest uppercase ml-4 block mb-1">Email Confirmation</label>
                        <input id="email" name="email" type="email" value="{{ old('email') }}" required
                            class="w-full bg-[#F5F5DC]/50 border-none rounded-2xl py-4 px-6 text-[#1B5E20] font-bold focus:ring-4 focus:ring-red-600/20 transition-all shadow-sm"
                            placeholder="Type your email: {{ auth()->user()->email }}"
                        />
                        <x-input-error :messages="$errors->userDeletion->get('email')" class="mt-2" />
                    </div>

                    <!-- Password Confirmation -->
                    <div class="space-y-1">
                        <label for="password" class="text-[9px] font-black text-[#1B5E20]/40 tracking-widest uppercase ml-4 block mb-1">Password Confirmation</label>
                        <input id="password" name="password" type="password" required
                            class="w-full bg-[#F5F5DC]/50 border-none rounded-2xl py-4 px-6 text-[#1B5E20] font-bold focus:ring-4 focus:ring-red-600/20 transition-all shadow-sm"
                            placeholder="{{ __('Password') }}"
                        />
                        <x-input-error :messages="$errors->userDeletion->get('password')" class="mt-2" />
                    </div>
                </div>

                <div class="mt-10 flex flex-col sm:flex-row gap-3">
                    <button type="submit" class="flex-grow bg-red-600 text-white px-8 py-4 rounded-xl font-black text-[11px] tracking-widest uppercase italic hover:bg-red-700 transition-all shadow-xl active:scale-95">
                        Delete Permanently
                    </button>
                    <button type="button" x-on:click="$dispatch('close')" class="flex-grow bg-[#F5F5DC] text-[#1B5E20] px-8 py-4 rounded-xl font-black text-[11px] tracking-widest uppercase italic border border-[#1B5E20]/5 hover:bg-white transition-all active:scale-95">
                        Cancel
                    </button>
                </div>
            </form>
        </x-modal>
    </template>
</section>
