<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">

    <title>{{ config('app.name', 'NoteWeb') }}</title>

    <!-- Fonts -->
    <link rel="preconnect" href="https://fonts.bunny.net">
    <link href="https://fonts.bunny.net/css?family=figtree:400,500,600&display=swap" rel="stylesheet" />

    <style>
        [x-cloak] { display: none !important; }

        /* Saat BUKA (Enter) */
        .dropdown-enter {
            /* Slide meluncur standar (400ms) */
            transition: transform 400ms cubic-bezier(0.4, 0, 0.2, 1);
        }

        .dropdown-enter-content {
            /* Opacity MENUNGGU (delay 300ms) baru muncul perlahan */
            transition: opacity 600ms ease-out 300ms !important;
        }

        /* Saat TUTUP (Leave) */
        .dropdown-leave {
            /* Slide naik perlahan (600ms) */
            transition: transform 600ms cubic-bezier(0.4, 0, 0.2, 1);
        }

        .dropdown-leave-content {
            /* Opacity menghilang cepat (250ms) tapi tidak instan */
            transition: opacity 250ms ease-in !important;
        }

        /* Staggered Pulse Animation (No movement) */
        @keyframes staggeredPulse {
            0%, 100% { opacity: 0.2; }
            50% { opacity: 1; }
        }
        .animate-pulse-1 { animation: staggeredPulse 0.8s infinite 0.3s; }
        .animate-pulse-2 { animation: staggeredPulse 0.8s infinite 0.15s; }
        .animate-pulse-3 { animation: staggeredPulse 0.8s infinite 0s; }

    </style>

    <!-- Scripts -->
    @vite(['resources/css/app.css', 'resources/js/app.js'])

    @livewireStyles
</head>

<!-- Cari tag body, ubah background menjadi warna Beige (#F5F5DC) -->

<body class="bg-[#F5F5DC] font-sans antialiased text-[#1B5E20]">
    <div class="min-h-screen flex flex-col" x-data="{ mobileMenuOpen: false }" @keydown.escape="mobileMenuOpen = false">
        <!-- Navigation Bar & Full Screen Menu -->
        <div>

            <!-- 1. Floating Navbar (Utama) -->
            <div class="fixed top-6 inset-x-0 z-50 flex justify-center px-4 transition-all duration-500"
                :class="mobileMenuOpen ? 'opacity-0 pointer-events-none' : 'opacity-100'">
                <nav
                    class="bg-white/95 backdrop-blur-xl border border-white/20 shadow-xl rounded-full w-full max-w-6xl h-14 sm:h-16 flex items-center justify-between px-6 sm:px-10">

                    <!-- Kiri: Logo -->
                    <div class="flex items-center gap-2">
                        <div
                            class="w-7 h-7 sm:w-9 sm:h-9 bg-[#1B5E20] rounded-full flex items-center justify-center text-white font-black text-[10px] sm:text-xs">
                            N</div>
                        <h1 class="text-[10px] sm:text-xs font-black text-[#1B5E20] tracking-tighter uppercase italic">
                            NoteWeb</h1>
                    </div>

                    <!-- Tengah: Menu Desktop (Hanya muncul di Desktop/Laptop) -->
                    <div class="hidden md:flex items-center gap-2">
                        <div x-data="{ 
                                activeLeft: 0, 
                                activeWidth: 0, 
                                left: 0, 
                                width: 0, 
                                opacity: 0,
                                isLoaded: false,
                                init() {
                                    $nextTick(() => {
                                        const activeEl = this.$refs.navContainer.querySelector('.active-nav');
                                        if (activeEl) {
                                            this.activeLeft = activeEl.offsetLeft;
                                            this.activeWidth = activeEl.offsetWidth;
                                            this.left = this.activeLeft;
                                            this.width = this.activeWidth;
                                            this.opacity = 1;
                                        }
                                        // Aktifkan animasi setelah posisi awal di-set
                                        setTimeout(() => { this.isLoaded = true; }, 50);
                                    });
                                }
                            }"
                            x-ref="navContainer"
                            @mouseleave="left = activeLeft; width = activeWidth; opacity = (activeWidth > 0 ? 1 : 0)"
                            class="relative flex items-center gap-1 bg-[#1B5E20]/10 p-1.5 rounded-full">
                            <!-- Sliding Background -->
                            <div class="absolute h-8 bg-white rounded-full shadow-sm ease-out pointer-events-none"
                                :class="isLoaded ? 'transition-all duration-300' : ''"
                                :style="`left: ${left}px; width: ${width}px; opacity: ${opacity};`"></div>

                            <a href="{{ route('dashboard') }}" wire:navigate
                                @mouseenter="left = $el.offsetLeft; width = $el.offsetWidth; opacity = 1"
                                @click="activeLeft = $el.offsetLeft; activeWidth = $el.offsetWidth"
                                class="relative px-5 py-2 font-black text-[10px] tracking-widest transition-colors duration-300 {{ request()->routeIs('dashboard') ? 'active-nav text-[#1B5E20]' : 'text-[#1B5E20]/60 hover:text-[#1B5E20]' }}">DASHBOARD</a>

                            <a href="{{ route('web.tags') }}" wire:navigate
                                @mouseenter="left = $el.offsetLeft; width = $el.offsetWidth; opacity = 1"
                                @click="activeLeft = $el.offsetLeft; activeWidth = $el.offsetWidth"
                                class="relative px-5 py-2 font-black text-[10px] tracking-widest transition-colors duration-300 {{ request()->routeIs('web.tags', 'tags.*') ? 'active-nav text-[#1B5E20]' : 'text-[#1B5E20]/60 hover:text-[#1B5E20]' }}">TAGS</a>

                            <a href="{{ route('web.settings') }}" wire:navigate
                                @mouseenter="left = $el.offsetLeft; width = $el.offsetWidth; opacity = 1"
                                @click="activeLeft = $el.offsetLeft; activeWidth = $el.offsetWidth"
                                class="relative px-5 py-2 font-black text-[10px] tracking-widest transition-colors duration-300 {{ request()->routeIs('web.settings', 'profile.*') ? 'active-nav text-[#1B5E20]' : 'text-[#1B5E20]/60 hover:text-[#1B5E20]' }}">SETTINGS</a>
                        </div>
                    </div>

                    <!-- Kanan: Profile (Desktop) & Menu Button (Mobile) -->
                    <div class="flex items-center gap-4">
                        <!-- User Profile Desktop -->
                        <div class="hidden md:flex items-center gap-3 pl-4 border-l border-[#1B5E20]/10">
                            <span class="text-[10px] font-black text-[#1B5E20] italic">{{ auth()->user()->name }}</span>
                            <div
                                class="w-8 h-8 bg-[#4CAF50] rounded-full flex items-center justify-center text-[10px] text-white font-black shadow-md shadow-green-900/20">
                                {{ substr(auth()->user()->name, 0, 1) }}
                            </div>
                        </div>

                        <!-- Tombol MENU (Hanya muncul di Mobile/Tablet Kecil) -->
                        <button @click="mobileMenuOpen = true"
                            class="md:hidden bg-[#1B5E20] px-5 py-2.5 rounded-full text-[10px] font-black text-white tracking-widest shadow-lg active:scale-95 transition-all">
                            MENU
                        </button>
                    </div>
                </nav>
            </div>

            <!-- 2. Full Screen Menu Overlay (Responsive & Compact) -->
            <div x-show="mobileMenuOpen" x-cloak x-transition:enter="transition transform duration-500 ease-in-out"
                x-transition:enter-start="translate-y-full" x-transition:enter-end="translate-y-0"
                x-transition:leave="transition transform duration-500 ease-in-out"
                x-transition:leave-start="translate-y-0" x-transition:leave-end="translate-y-full"
                class="fixed inset-0 z-[60] bg-[#4CAF50] flex flex-col p-6 sm:p-10 h-screen overflow-hidden">

                <!-- Header (Jarak Atas Disesuaikan) -->
                <div class="flex items-center justify-between pt-2 mb-6 sm:mb-10">
                    <h1 class="text-white font-black text-xl sm:text-2xl tracking-tighter italic">NW.</h1>
                    <button @click="mobileMenuOpen = false"
                        class="bg-white px-6 sm:px-8 py-2.5 sm:py-3 rounded-full text-[10px] sm:text-[11px] font-black text-[#1B5E20] tracking-[0.2em] shadow-2xl active:scale-95 transition-all">
                        CLOSE
                    </button>
                </div>

                <!-- Konten Tengah (Menu Utama dengan Ukuran Dinamis) -->
                <div class="flex-grow flex flex-col justify-center max-w-xl mx-auto w-full">
                    <p
                        class="text-white/60 text-[9px] sm:text-[10px] font-bold tracking-[0.3em] uppercase text-center mb-6 sm:mb-12">
                        Main Navigation</p>

                    <!-- Bagian List Menu di dalam Overlay -->
                    <nav class="flex flex-col border-t border-white/20">
                        <!-- Dashboard -->
                        <a href="{{ route('dashboard') }}" wire:navigate @click="mobileMenuOpen = false"
                            class="group py-4 sm:py-6 flex items-center justify-between border-b border-white/20 hover:px-4 transition-all duration-300 {{ request()->routeIs('dashboard') ? 'px-4' : '' }}">
                            <span
                                class="text-2xl sm:text-4xl font-black tracking-tighter uppercase transition-colors {{ request()->routeIs('dashboard') ? 'text-[#1B5E20]' : 'text-white' }}">Dashboard</span>
                            <div
                                class="w-8 h-8 sm:w-10 sm:h-10 rounded-full flex items-center justify-center transition-all {{ request()->routeIs('dashboard') ? 'bg-white text-[#4CAF50]' : 'bg-white/10 text-white group-hover:bg-white group-hover:text-[#4CAF50]' }}">
                                <svg class="w-4 h-4 sm:w-6 sm:h-6" fill="none" stroke="currentColor"
                                    viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                        d="M17 8l4 4m0 0l-4 4m4-4H3" />
                                </svg>
                            </div>
                        </a>

                        <!-- Tags -->
                        <a href="{{ route('web.tags') }}" wire:navigate @click="mobileMenuOpen = false"
                            class="group py-4 sm:py-6 flex items-center justify-between border-b border-white/20 hover:px-4 transition-all duration-300 {{ request()->routeIs('web.tags', 'tags.*') ? 'px-4' : '' }}">
                            <span class="text-2xl sm:text-4xl font-black tracking-tighter uppercase transition-colors {{ request()->routeIs('web.tags', 'tags.*') ? 'text-[#1B5E20]' : 'text-white' }}">Tags</span>
                            <div
                                class="w-8 h-8 sm:w-10 sm:h-10 rounded-full flex items-center justify-center transition-all {{ request()->routeIs('web.tags', 'tags.*') ? 'bg-white text-[#4CAF50]' : 'bg-white/10 text-white group-hover:bg-white group-hover:text-[#4CAF50]' }}">
                                <svg class="w-4 h-4 sm:w-6 sm:h-6" fill="none" stroke="currentColor"
                                    viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                        d="M17 8l4 4m0 0l-4 4m4-4H3" />
                                </svg>
                            </div>
                        </a>

                        <!-- Settings -->
                        <a href="{{ route('web.settings') }}" wire:navigate @click="mobileMenuOpen = false"
                            class="group py-4 sm:py-6 flex items-center justify-between border-b border-white/20 hover:px-4 transition-all duration-300 {{ request()->routeIs('web.settings', 'profile.*') ? 'px-4' : '' }}">
                            <span
                                class="text-2xl sm:text-4xl font-black tracking-tighter uppercase transition-colors {{ request()->routeIs('web.settings', 'profile.*') ? 'text-[#1B5E20]' : 'text-white' }}">Settings</span>
                            <div
                                class="w-8 h-8 sm:w-10 sm:h-10 rounded-full flex items-center justify-center transition-all {{ request()->routeIs('web.settings', 'profile.*') ? 'bg-white text-[#4CAF50]' : 'bg-white/10 text-white group-hover:bg-white group-hover:text-[#4CAF50]' }}">
                                <svg class="w-4 h-4 sm:w-6 sm:h-6" fill="none" stroke="currentColor"
                                    viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                        d="M17 8l4 4m0 0l-4 4m4-4H3" />
                                </svg>
                            </div>
                        </a>
                    </nav>
                </div>

                <!-- Footer Profile (Kompak & Rapi) -->
                <div class="mt-auto pb-4 sm:pb-8 max-w-xl mx-auto w-full">
                    <div
                        class="p-4 sm:p-6 bg-white/10 rounded-[1.5rem] sm:rounded-[2rem] flex items-center justify-between border border-white/10 backdrop-blur-sm">
                        <div class="flex items-center gap-3 sm:gap-4">
                            <div
                                class="w-10 h-10 sm:w-12 sm:h-12 bg-white rounded-xl flex items-center justify-center text-[#4CAF50] font-black italic shadow-lg">
                                {{ substr(auth()->user()->name, 0, 1) }}
                            </div>
                            <div class="flex flex-col text-white">
                                <span
                                    class="font-black uppercase text-xs sm:text-sm leading-none tracking-tighter italic">{{ auth()->user()->name }}</span>
                                <span
                                    class="opacity-50 text-[8px] font-bold tracking-widest mt-1 uppercase">Authenticated</span>
                            </div>
                        </div>
                        <form method="POST" action="{{ route('logout') }}">
                            @csrf
                            <button type="submit"
                                class="bg-white px-4 sm:px-6 py-2 rounded-xl sm:rounded-2xl text-[8px] sm:text-[9px] font-black text-[#4CAF50] tracking-widest hover:bg-[#1B5E20] hover:text-white transition-all shadow-xl uppercase">
                                Logout
                            </button>
                        </form>
                    </div>
                </div>
            </div>
        </div>

        <main class="{{ ($fullWidth ?? false) ? 'w-full pt-36 pb-24' : 'flex-grow w-full max-w-7xl mx-auto px-6 sm:px-12 lg:px-20 pt-36 pb-24' }}">
            {{ $slot }}
        </main>

        <!-- Footer Section (Ultra-Minimal) -->
        <footer id="main-footer" class="bg-[#1B5E20] py-8 px-6 sm:px-12 lg:px-20 text-white/50 border-t border-white/5">
            <div class="max-w-7xl mx-auto flex flex-col md:flex-row items-center justify-between gap-6">
                <!-- Left: Logo & Copyright -->
                <div class="flex items-center gap-4">
                    <div class="flex items-center gap-2">
                        <div class="w-6 h-6 bg-white rounded-full flex items-center justify-center text-[#1B5E20] font-black italic text-[8px]">N</div>
                        <span class="text-xs font-black tracking-tighter uppercase italic text-white">NoteWeb</span>
                    </div>
                    <div class="w-px h-3 bg-white/10 hidden sm:block"></div>
                    <p class="text-[10px] font-medium tracking-wide uppercase hidden sm:block">© 2026 NoteWeb Studio.</p>
                </div>

                <!-- Center: Social Media -->
                <div class="flex items-center gap-6 sm:gap-4">
                    <a href="https://github.com" target="_blank" class="text-white/30 hover:text-white transition-all duration-300" title="GitHub">
                        <svg class="w-6 h-6" fill="currentColor" viewBox="0 0 24 24"><path fill-rule="evenodd" d="M12 2C6.477 2 2 6.484 2 12.017c0 4.425 2.865 8.18 6.839 9.504.5.092.682-.217.682-.483 0-.237-.008-.868-.013-1.703-2.782.605-3.369-1.343-3.369-1.343-.454-1.158-1.11-1.466-1.11-1.466-.908-.62.069-.608.069-.608 1.003.07 1.531 1.032 1.531 1.032.892 1.53 2.341 1.088 2.91.832.092-.647.35-1.088.636-1.338-2.22-.253-4.555-1.113-4.555-4.951 0-1.093.39-1.988 1.029-2.688-.103-.253-.446-1.272.098-2.65 0 0 .84-.27 2.75 1.026A9.564 9.564 0 0112 6.844c.85.004 1.705.115 2.504.337 1.909-1.296 2.747-1.027 2.747-1.027.546 1.379.202 2.398.1 2.651.64.7 1.028 1.595 1.028 2.688 0 3.848-2.339 4.695-4.566 4.943.359.309.678.92.678 1.855 0 1.338-.012 2.419-.012 2.747 0 .268.18.58.688.482A10.019 10.019 0 0022 12.017C22 6.484 17.522 2 12 2z" clip-rule="evenodd" /></svg>
                    </a>
                    <a href="https://instagram.com" target="_blank" class="text-white/30 hover:text-white transition-all duration-300" title="Instagram">
                        <svg class="w-6 h-6" fill="currentColor" viewBox="0 0 24 24"><path fill-rule="evenodd" d="M12.315 2c2.43 0 2.784.013 3.808.06 1.064.049 1.791.218 2.427.465a4.902 4.902 0 011.772 1.153 4.902 4.902 0 011.153 1.772c.247.636.416 1.363.465 2.427.048 1.067.06 1.407.06 4.123v.08c0 2.643-.012 2.987-.06 4.043-.049 1.064-.218 1.791-.465 2.427a4.902 4.902 0 01-1.153 1.772 4.902 4.902 0 01-1.772 1.153c-.636.247-1.363.416-2.427.465-1.067.048-1.407.06-4.123.06h-.08c-2.643 0-2.987-.012-4.043-.06-1.064-.049-1.791-.218-2.427-.465a4.902 4.902 0 01-1.772-1.153 4.902 4.902 0 01-1.153-1.772c-.247-.636-.416-1.363-.465-2.427-.047-1.024-.06-1.379-.06-3.808v-.63c0-2.43.013-2.784.06-3.808.049-1.064.218-1.791.465-2.427a4.902 4.902 0 011.153-1.772A4.902 4.902 0 015.45 2.525c.636-.247 1.363-.416 2.427-.465C8.901 2.013 9.256 2 11.685 2h.63zm-.081 1.802h-.468c-2.456 0-2.784.011-3.807.058-.975.045-1.504.207-1.857.344-.467.182-.8.398-1.15.748-.35.35-.566.683-.748 1.15-.137.353-.3.882-.344 1.857-.047 1.023-.058 1.351-.058 3.807v.468c0 2.456.011 2.784.058 3.807.045.975.207 1.504.344 1.857.182.466.399.8.748 1.15.35.35.683.566 1.15.748.353.137.882.3 1.857.344 1.054.048 1.37.058 4.041.058h.08c2.597 0 2.917-.01 3.96-.058.976-.045 1.505-.207 1.858-.344.466-.182.8-.398 1.15-.748.35-.35.566-.683.748-1.15.137-.353.3-.882.344-1.857.048-1.055.058-1.37.058-4.041v-.08c0-2.597-.01-2.917-.058-3.96-.045-.976-.207-1.505-.344-1.858a3.097 3.097 0 00-.748-1.15 3.098 3.098 0 00-1.15-.748c-.353-.137-.882-.3-1.857-.344-1.023-.047-1.351-.058-3.807-.058zM12 5.838a6.162 6.162 0 100 12.324 6.162 6.162 0 000-12.324zM12 16.4a4.4 4.4 0 110-8.8 4.4 4.4 0 010 8.8zm6.487-11.595a1.44 1.44 0 100 2.881 1.44 1.44 0 000-2.881z" clip-rule="evenodd" /></svg>
                    </a>
                    <a href="https://facebook.com" target="_blank" class="text-white/30 hover:text-white transition-all duration-300" title="Facebook">
                        <svg class="w-6 h-6" fill="currentColor" viewBox="0 0 24 24"><path fill-rule="evenodd" d="M22 12c0-5.523-4.477-10-10-10S2 6.477 2 12c0 4.991 3.657 9.128 8.438 9.878v-6.987h-2.54V12h2.54V9.797c0-2.506 1.492-3.89 3.777-3.89 1.094 0 2.238.195 2.238.195v2.46h-1.26c-1.243 0-1.63.771-1.63 1.562V12h2.773l-.443 2.89h-2.33v6.988C18.343 21.128 22 16.991 22 12z" clip-rule="evenodd" /></svg>
                    </a>
                    <a href="https://linkedin.com" target="_blank" class="text-white/30 hover:text-white transition-all duration-300" title="LinkedIn">
                        <svg class="w-6 h-6" fill="currentColor" viewBox="0 0 24 24"><path d="M19 0h-14c-2.761 0-5 2.239-5 5v14c0 2.761 2.239 5 5 5h14c2.762 0 5-2.239 5-5v-14c0-2.761-2.238-5-5-5zm-11 19h-3v-11h3v11zm-1.5-12.268c-.966 0-1.75-.79-1.75-1.764s.784-1.764 1.75-1.764 1.75.79 1.75 1.764-.783 1.764-1.75 1.764zm13.5 12.268h-3v-5.604c0-3.368-4-3.113-4 0v5.604h-3v-11h3v1.765c1.396-2.586 7-2.777 7 2.476v6.759z" /></svg>
                    </a>
                </div>

                <!-- Right: Status -->
                <div class="flex items-center gap-3">
                    <div class="w-1.5 h-1.5 bg-[#4CAF50] rounded-full animate-pulse"></div>
                    <span class="text-[8px] font-bold tracking-[0.3em] uppercase opacity-40">System Operational</span>
                </div>

            </div>
            <!-- Mobile Copyright (Visible only on smallest screens) -->
            <p class="text-[9px] font-medium tracking-wide uppercase text-center mt-6 sm:hidden">© 2026 NoteWeb Studio.</p>
        </footer>

        <!-- Tombol Back to Top -->
        <div x-data="{ 
            showTop: false,
            bottomOffset: 32
        }" 
        @scroll.window="
            showTop = (window.pageYOffset > 400);
            const footer = document.getElementById('main-footer');
            if (footer) {
                const footerRect = footer.getBoundingClientRect();
                const footerVisibleHeight = window.innerHeight - footerRect.top;
                bottomOffset = Math.max(32, (footerVisibleHeight > 0 ? footerVisibleHeight + 20 : 32));
            }
        ">
            <button x-show="showTop && !mobileMenuOpen" x-cloak 
                x-transition:enter="transition ease-out duration-300"
                x-transition:enter-start="opacity-0 translate-y-10" x-transition:enter-end="opacity-100 translate-y-0"
                x-transition:leave="transition ease-in duration-300" x-transition:leave-start="opacity-100 translate-y-0"
                x-transition:leave-end="opacity-0 translate-y-10" 
                @click="window.scrollTo({top: 0, behavior: 'smooth'})"
                :style="{ bottom: bottomOffset + 'px' }"
                class="fixed right-8 z-40 w-12 h-12 md:w-12 md:h-12 bg-[#1B5E20] text-white rounded-[0.5rem] border border-white/10 shadow-2xl flex items-center justify-center md:hover:bg-[#4CAF50] transition-all duration-500 active:scale-90 group overflow-hidden">
                
                <!-- Icon Default (Panah dengan Garis) -->
                <svg class="w-6 h-6 md:group-hover:hidden transition-all" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="3" d="M5 10l7-7m0 0l7 7m-7-7v18" />
                </svg>

                <!-- Icon Hover (3 Panah Tanpa Garis / Staggered Pulse) -->
                <div class="hidden md:group-hover:flex flex-col items-center -space-y-2.5">
                    <svg class="w-5 h-5 animate-pulse-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="4" d="M5 15l7-7 7 7" />
                    </svg>
                    <svg class="w-5 h-5 animate-pulse-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="4" d="M5 15l7-7 7 7" />
                    </svg>
                    <svg class="w-5 h-5 animate-pulse-3" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="4" d="M5 15l7-7 7 7" />
                    </svg>
                </div>
            </button>
        </div>
        <!-- Global Toast Notification -->
        <div x-data="{ 
                show: false, 
                message: '', 
                type: 'success',
                timeout: null
            }" 
            @notify.window="
                message = $event.detail.message;
                type = $event.detail.type || 'success';
                show = true;
                clearTimeout(timeout);
                timeout = setTimeout(() => { show = false }, 3000);
            "
            class="fixed top-8 left-1/2 -translate-x-1/2 z-[200]"
        >
            <div x-show="show" x-transition:enter="transition ease-out duration-300"
                x-transition:enter-start="opacity-0 -translate-y-4 scale-95"
                x-transition:enter-end="opacity-100 translate-y-0 scale-100"
                x-transition:leave="transition ease-in duration-200"
                x-transition:leave-start="opacity-100 translate-y-0 scale-100"
                x-transition:leave-end="opacity-0 -translate-y-4 scale-95"
                :class="type === 'error' ? 'bg-red-50 text-red-600 border-red-200' : 'bg-green-50 text-green-600 border-green-200'"
                class="px-6 py-3 rounded-full border shadow-xl flex items-center gap-3 backdrop-blur-md"
                x-cloak>
                <div x-show="type === 'success'" class="w-6 h-6 rounded-full bg-green-100 flex items-center justify-center">
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="3" d="M5 13l4 4L19 7"></path></svg>
                </div>
                <div x-show="type === 'error'" class="w-6 h-6 rounded-full bg-red-100 flex items-center justify-center">
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="3" d="M6 18L18 6M6 6l12 12"></path></svg>
                </div>
                <span x-text="message" class="text-sm font-bold tracking-wide"></span>
            </div>
        </div>

    </div>
    @livewireScripts
</body>


</html>
