<x-app-layout>
    <div class="max-w-7xl mx-auto" x-data="{ activeTab: '{{ ($errors->updatePassword->isNotEmpty() || $errors->userDeletion->isNotEmpty()) ? 'security' : 'general' }}' }">
        <div class="mb-12">
            <h2 class="text-4xl font-black text-[#1B5E20] leading-none tracking-tighter italic uppercase">Settings</h2>
            <p class="text-[#1B5E20]/60 mt-2 font-medium tracking-wide">Personalisasi akun dan keamanan NoteWeb Anda.</p>
        </div>

        <div class="grid grid-cols-1 md:grid-cols-4 gap-8">
            <!-- Settings Menu Sidebar -->
            <div class="md:col-span-1 space-y-2">
                <div class="bg-white/95 rounded-2xl p-4 shadow-sm border border-[#1B5E20]/5 space-y-1">
                    <button @click="activeTab = 'general'; $dispatch('clear-passwords')" :class="activeTab === 'general' ? 'bg-[#1B5E20] text-white' : 'text-[#1B5E20]/40 hover:text-[#1B5E20] hover:bg-[#1B5E20]/5'" class="w-full text-left p-4 rounded-xl font-black text-[10px] tracking-widest uppercase italic transition-all">General Profile</button>
                    <button @click="activeTab = 'security'" :class="activeTab === 'security' ? 'bg-[#1B5E20] text-white' : 'text-[#1B5E20]/40 hover:text-[#1B5E20] hover:bg-[#1B5E20]/5'" class="w-full text-left p-4 rounded-xl font-black text-[10px] tracking-widest uppercase italic transition-all">Security</button>
                    
                    <div class="h-px bg-[#1B5E20]/5 my-2"></div>

                    <form method="POST" action="{{ route('logout') }}" class="w-full">
                        @csrf
                        <button type="submit" class="w-full text-left p-4 rounded-xl font-black text-[10px] tracking-widest uppercase italic transition-all text-red-600/60 hover:text-red-600 hover:bg-red-600/5 flex items-center justify-between group">
                            <span>Logout</span>
                            <svg class="w-4 h-4 opacity-0 group-hover:opacity-100 transition-all -translate-x-2 group-hover:translate-x-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M17 16l4-4m0 0l-4-4m4 4H7m6 4v1a3 3 0 01-3 3H6a3 3 0 01-3-3V7a3 3 0 013-3h4a3 3 0 013 3v1" />
                            </svg>
                        </button>
                    </form>
                </div>
            </div>

            <!-- Settings Content -->
            <div class="md:col-span-3">
                <!-- General Tab -->
                <div x-show="activeTab === 'general'" x-cloak x-transition:enter="transition ease-out duration-300" x-transition:enter-start="opacity-0 translate-y-4" x-transition:enter-end="opacity-100 translate-y-0">
                    <div class="bg-white/70 backdrop-blur-sm border border-[#1B5E20]/10 rounded-[2.5rem] p-8 sm:p-12 shadow-sm mb-8">
                        <div class="max-w-xl">
                            @include('profile.partials.update-profile-information-form')
                        </div>
                    </div>
                </div>

                <!-- Security Tab -->
                <div x-show="activeTab === 'security'" x-cloak x-transition:enter="transition ease-out duration-300" x-transition:enter-start="opacity-0 translate-y-4" x-transition:enter-end="opacity-100 translate-y-0">
                    <div class="bg-white/70 backdrop-blur-sm border border-[#1B5E20]/10 rounded-[2.5rem] p-8 sm:p-12 shadow-sm mb-8">
                        <div class="max-w-xl">
                            @include('profile.partials.update-password-form')
                        </div>
                    </div>

                    <div class="bg-red-50/50 backdrop-blur-sm border border-red-200 rounded-[2.5rem] p-8 sm:p-12 shadow-sm">
                        <div class="max-w-xl">
                            @include('profile.partials.delete-user-form')
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</x-app-layout>
