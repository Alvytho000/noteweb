<section x-data="{ 
    clear() {
        $refs.current.value = '';
        $refs.new.value = '';
        $refs.confirm.value = '';
    }
}" @clear-passwords.window="clear()">
    <header class="mb-8">
        <h2 class="text-2xl font-black text-[#1B5E20] italic uppercase tracking-tighter leading-none">
            {{ __('Update Password') }}
        </h2>
        <p class="mt-2 text-sm text-[#1B5E20]/60 font-medium tracking-wide">
            {{ __('Gunakan kata sandi yang panjang dan acak untuk menjaga keamanan akun Anda.') }}
        </p>
    </header>

    <form method="post" action="{{ route('password.update') }}" class="space-y-6">
        @csrf
        @method('put')

        <div class="space-y-1">
            <div class="flex flex-wrap justify-between items-center px-4 mb-1 gap-2">
                <label for="update_password_current_password" class="text-[9px] font-black text-[#1B5E20]/40 tracking-widest uppercase">{{ __('Current Password') }}</label>
                @if (Route::has('password.request'))
                    <a href="{{ route('password.request') }}" class="text-[9px] font-black text-[#4CAF50] tracking-widest uppercase hover:underline">
                        {{ __('Forgot Password?') }}
                    </a>
                @endif
            </div>
            <input id="update_password_current_password" x-ref="current" name="current_password" type="password" 
                class="w-full bg-white/50 border-none rounded-2xl py-4 px-6 text-[#1B5E20] font-bold focus:ring-4 focus:ring-[#4CAF50]/20 transition-all shadow-sm" autocomplete="current-password" />
            <x-input-error :messages="$errors->updatePassword->get('current_password')" class="mt-2" />
        </div>

        <div class="space-y-1">
            <label for="update_password_password" class="text-[9px] font-black text-[#1B5E20]/40 tracking-widest uppercase ml-4 block mb-1">{{ __('New Password') }}</label>
            <input id="update_password_password" x-ref="new" name="password" type="password" 
                class="w-full bg-white/50 border-none rounded-2xl py-4 px-6 text-[#1B5E20] font-bold focus:ring-4 focus:ring-[#4CAF50]/20 transition-all shadow-sm" autocomplete="new-password" />
            <x-input-error :messages="$errors->updatePassword->get('password')" class="mt-2" />
        </div>

        <div class="space-y-1">
            <label for="update_password_password_confirmation" class="text-[9px] font-black text-[#1B5E20]/40 tracking-widest uppercase ml-4 block mb-1">{{ __('Confirm Password') }}</label>
            <input id="update_password_password_confirmation" x-ref="confirm" name="password_confirmation" type="password" 
                class="w-full bg-white/50 border-none rounded-2xl py-4 px-6 text-[#1B5E20] font-bold focus:ring-4 focus:ring-[#4CAF50]/20 transition-all shadow-sm" autocomplete="new-password" />
            <x-input-error :messages="$errors->updatePassword->get('password_confirmation')" class="mt-2" />
        </div>

        <div class="flex items-center gap-4 pt-4">
            <button type="submit" class="bg-[#1B5E20] text-white px-10 py-4 rounded-xl font-black text-[10px] tracking-widest uppercase italic hover:bg-[#4CAF50] transition-all shadow-xl active:scale-95">
                Update Password
            </button>

            @if (session('status') === 'password-updated')
                <div x-init="$dispatch('notify', { message: 'Password updated successfully!', type: 'success' })"></div>
            @endif
        </div>
    </form>
</section>
