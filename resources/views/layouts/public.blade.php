<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>@yield('title', $settings['site_name'] ?? 'NIWA FOOD')</title>
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Baloo+2:wght@400;600;700;800&family=DM+Sans:wght@300;400;500;600;700&display=swap" rel="stylesheet">
    @vite(['resources/css/app.css', 'resources/js/app.js'])
    <script>
        (function(){var t=localStorage.getItem('theme');if(t==='dark'||(!t&&window.matchMedia('(prefers-color-scheme:dark)').matches))document.documentElement.classList.add('dark')})();
    </script>
</head>
<body class="bg-background text-foreground font-sans antialiased">

    {{-- Navbar --}}
    <nav x-data="{ open: false, isDark: document.documentElement.classList.contains('dark') }" class="sticky top-0 z-50 bg-background/90 backdrop-blur-xl border-b border-primary/20">
        <div class="max-w-6xl mx-auto px-6 lg:px-12">
            <div class="flex items-center justify-between h-[72px]">
                {{-- Logo --}}
                @php
                    $logoPath = $settings['site_logo'] ?? 'images/logo-niwa.png';
                    $siteName = $settings['site_name'] ?? 'NIWA FOOD';
                    $siteNameAccent = $settings['site_name_accent'] ?? null;
                    if ($siteNameAccent && str_ends_with(strtoupper($siteName), strtoupper($siteNameAccent))) {
                        $nameMain = trim(substr($siteName, 0, -strlen($siteNameAccent)));
                        $nameAccent = $siteNameAccent;
                    } else {
                        $parts = explode(' ', trim($siteName), 2);
                        $nameMain = $parts[0];
                        $nameAccent = $parts[1] ?? '';
                    }
                @endphp
                <a href="{{ route('home') }}" class="flex items-center gap-2.5">
                    <img src="{{ asset($logoPath) }}" alt="{{ $siteName }}" class="w-10 h-10 rounded-full object-cover">
                    <span class="font-heading font-bold text-xl">{{ $nameMain }}@if($nameAccent) <span class="text-accent-mustard">{{ $nameAccent }}</span>@endif</span>
                </a>

                {{-- Desktop Nav — pill container --}}
                <div class="hidden md:flex items-center border border-primary/40 rounded-full px-2 py-1.5">
                    <a href="{{ route('home') }}" class="px-4 py-1.5 rounded-full text-sm font-medium transition-colors duration-200 {{ request()->routeIs('home') ? 'text-accent-mustard underline underline-offset-4 decoration-accent-mustard' : 'text-foreground/70 hover:text-foreground' }}">Accueil</a>
                    <a href="{{ route('menu') }}" class="px-4 py-1.5 rounded-full text-sm font-medium transition-colors duration-200 {{ request()->routeIs('menu') ? 'text-accent-mustard underline underline-offset-4 decoration-accent-mustard' : 'text-foreground/70 hover:text-foreground' }}">Menu</a>
                    <a href="{{ route('tracking') }}" class="px-4 py-1.5 rounded-full text-sm font-medium transition-colors duration-200 {{ request()->routeIs('tracking') ? 'text-accent-mustard underline underline-offset-4 decoration-accent-mustard' : 'text-foreground/70 hover:text-foreground' }}">Suivi</a>
                    <a href="{{ route('about') }}" class="px-4 py-1.5 rounded-full text-sm font-medium transition-colors duration-200 {{ request()->routeIs('about') ? 'text-accent-mustard underline underline-offset-4 decoration-accent-mustard' : 'text-foreground/70 hover:text-foreground' }}">À propos</a>
                    <a href="{{ route('contact') }}" class="px-4 py-1.5 rounded-full text-sm font-medium transition-colors duration-200 {{ request()->routeIs('contact') ? 'text-accent-mustard underline underline-offset-4 decoration-accent-mustard' : 'text-foreground/70 hover:text-foreground' }}">Contact</a>
                </div>

                {{-- Desktop CTA --}}
                <div class="hidden md:flex items-center gap-3">
                    {{-- Cart button --}}
                    <button x-show="$store.cart.count > 0" x-cloak
                            @click="$dispatch('toggle-cart')"
                            aria-label="Voir mon panier"
                            class="relative flex items-center gap-2 px-4 py-2 rounded-full border border-primary/40 text-sm font-medium text-foreground hover:border-primary transition-all">
                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 3h2l.4 2M7 13h10l4-8H5.4M7 13L5.4 5M7 13l-2.293 2.293c-.63.63-.184 1.707.707 1.707H17m0 0a2 2 0 100 4 2 2 0 000-4zm-8 2a2 2 0 100 4 2 2 0 000-4z"/></svg>
                        <span x-text="new Intl.NumberFormat('fr-FR').format($store.cart.total) + ' FCFA'"></span>
                        <span class="absolute -top-1.5 -right-1.5 w-5 h-5 bg-accent-green text-on-primary text-xs font-bold rounded-full flex items-center justify-center"
                              x-text="$store.cart.count"></span>
                    </button>
                    <button
                        @click="isDark = !isDark; document.documentElement.classList.add('theme-transition'); document.documentElement.classList.toggle('dark'); localStorage.setItem('theme', isDark ? 'dark' : 'light'); setTimeout(() => document.documentElement.classList.remove('theme-transition'), 400)"
                        class="w-10 h-10 rounded-full border border-primary/40 flex items-center justify-center text-foreground/50 hover:text-foreground hover:border-primary transition-all"
                        aria-label="Basculer le thème clair/sombre">
                        <svg x-show="!isDark" class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M20.354 15.354A9 9 0 018.646 3.646 9.003 9.003 0 0012 21a9.003 9.003 0 008.354-5.646z"/></svg>
                        <svg x-show="isDark" x-cloak class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M12 3v1m0 16v1m9-9h-1M4 12H3m15.364 6.364l-.707-.707M6.343 6.343l-.707-.707m12.728 0l-.707.707M6.343 17.657l-.707.707M16 12a4 4 0 11-8 0 4 4 0 018 0z"/></svg>
                    </button>
                    <a href="{{ route('menu') }}" class="btn-primary-warm text-sm !px-6 !py-2.5">Commander</a>
                </div>

                {{-- Mobile right actions --}}
                <div class="flex md:hidden items-center gap-2">
                    {{-- Cart --}}
                    <button x-show="$store.cart.count > 0" x-cloak
                            @click="$dispatch('toggle-cart')"
                            aria-label="Voir mon panier"
                            class="relative w-9 h-9 rounded-full border border-primary/40 flex items-center justify-center text-foreground/60 hover:text-foreground hover:border-primary transition-all">
                        <svg class="w-4.5 h-4.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 3h2l.4 2M7 13h10l4-8H5.4M7 13L5.4 5M7 13l-2.293 2.293c-.63.63-.184 1.707.707 1.707H17m0 0a2 2 0 100 4 2 2 0 000-4zm-8 2a2 2 0 100 4 2 2 0 000-4z"/></svg>
                        <span class="absolute -top-1 -right-1 w-4.5 h-4.5 min-w-[18px] bg-accent-green text-on-primary text-[10px] font-bold rounded-full flex items-center justify-center"
                              x-text="$store.cart.count"></span>
                    </button>
                    {{-- Theme toggle --}}
                    <button
                        @click="isDark = !isDark; document.documentElement.classList.add('theme-transition'); document.documentElement.classList.toggle('dark'); localStorage.setItem('theme', isDark ? 'dark' : 'light'); setTimeout(() => document.documentElement.classList.remove('theme-transition'), 400)"
                        class="w-9 h-9 rounded-full border border-primary/40 flex items-center justify-center text-foreground/50 hover:text-foreground hover:border-primary transition-all"
                        aria-label="Basculer le thème clair/sombre">
                        <svg x-show="!isDark" class="w-4.5 h-4.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M20.354 15.354A9 9 0 018.646 3.646 9.003 9.003 0 0012 21a9.003 9.003 0 008.354-5.646z"/></svg>
                        <svg x-show="isDark" x-cloak class="w-4.5 h-4.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M12 3v1m0 16v1m9-9h-1M4 12H3m15.364 6.364l-.707-.707M6.343 6.343l-.707-.707m12.728 0l-.707.707M6.343 17.657l-.707.707M16 12a4 4 0 11-8 0 4 4 0 018 0z"/></svg>
                    </button>
                    {{-- Hamburger --}}
                    <button @click="open = !open" class="text-foreground p-2">
                        <svg x-show="!open" class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6h16M4 12h16M4 18h16"/></svg>
                        <svg x-show="open" x-cloak class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/></svg>
                    </button>
                </div>
            </div>
        </div>

        {{-- Mobile menu --}}
        <div x-show="open" x-cloak
             x-transition:enter="transition ease-out duration-200"
             x-transition:enter-start="opacity-0 -translate-y-2"
             x-transition:enter-end="opacity-100 translate-y-0"
             x-transition:leave="transition ease-in duration-150"
             class="md:hidden bg-background border-t border-primary/20">
            <div class="px-6 py-5 space-y-3">
                <a href="{{ route('home') }}" class="block py-2.5 font-medium {{ request()->routeIs('home') ? 'text-accent-green' : 'text-foreground/70' }}">Accueil</a>
                <a href="{{ route('menu') }}" class="block py-2.5 font-medium {{ request()->routeIs('menu') ? 'text-accent-green' : 'text-foreground/70' }}">Menu</a>
                <a href="{{ route('tracking') }}" class="block py-2.5 font-medium text-foreground/70">Suivi</a>
                <a href="{{ route('about') }}" class="block py-2.5 font-medium {{ request()->routeIs('about') ? 'text-accent-green' : 'text-foreground/70' }}">À propos</a>
                <a href="{{ route('contact') }}" class="block py-2.5 font-medium {{ request()->routeIs('contact') ? 'text-accent-green' : 'text-foreground/70' }}">Contact</a>
                <a href="{{ route('menu') }}" class="btn-primary-warm text-sm text-center block mt-3">Commander</a>
            </div>
        </div>
    </nav>

    {{-- Page content --}}
    <main>
        @yield('content')
    </main>

    {{-- Global product composition sheet (bottom sheet like original) --}}
    <div x-data="{
            pmOpen: false,
            pm: null,
            pmVariant: null,
            pmRemoved: [],
            pmQty: 1,
            pmScrolled: false,

            get pmUnitPrice() {
                if (!this.pm) return 0;
                return this.pmVariant ? this.pmVariant.price : this.pm.price;
            },
            get pmTotal() { return this.pmUnitPrice * this.pmQty; },

            pmToggle(ing) {
                const i = this.pmRemoved.indexOf(ing);
                if (i >= 0) this.pmRemoved.splice(i, 1);
                else this.pmRemoved.push(ing);
            },
            pmFmt(n) { return new Intl.NumberFormat('fr-FR').format(n); },

            pmClose() {
                this.pmOpen = false;
                document.body.style.overflow = '';
            },

            pmAdd() {
                if (!this.pm) return;
                this.$store.cart.add({
                    productId: this.pm.id,
                    name: this.pm.name,
                    image: this.pm.image,
                    variantId: this.pmVariant?.id || null,
                    variantName: this.pmVariant?.name || null,
                    price: this.pmUnitPrice,
                    quantity: this.pmQty,
                    removedIngredients: [...this.pmRemoved],
                });
                this.pmClose();
            }
        }"
        @open-product-modal.window="
            pm = $event.detail;
            pmVariant = pm.variants?.length ? pm.variants[0] : null;
            pmRemoved = [];
            pmQty = 1;
            pmScrolled = false;
            pmOpen = true;
            document.body.style.overflow = 'hidden';
        "
        @keydown.escape.window="if (pmOpen) pmClose()">

        <template x-teleport="body">
          <div>
            {{-- Backdrop --}}
            <div x-show="pmOpen" x-cloak
                 x-transition:enter="transition ease-out duration-300" x-transition:enter-start="opacity-0" x-transition:enter-end="opacity-100"
                 x-transition:leave="transition ease-in duration-200" x-transition:leave-start="opacity-100" x-transition:leave-end="opacity-0"
                 class="fixed inset-0 z-[100] bg-black/50"
                 @click="pmClose()"></div>

            {{-- Bottom sheet --}}
            <div x-show="pmOpen" x-cloak
                 x-transition:enter="transition ease-out duration-300" x-transition:enter-start="translate-y-full" x-transition:enter-end="translate-y-0"
                 x-transition:leave="transition ease-in duration-200" x-transition:leave-start="translate-y-0" x-transition:leave-end="translate-y-full"
                 class="fixed inset-x-0 bottom-0 z-[101] bg-background rounded-t-3xl shadow-2xl max-h-[92vh] flex flex-col overflow-hidden sm:inset-x-auto sm:left-1/2 sm:-translate-x-1/2 sm:max-w-lg sm:rounded-3xl sm:bottom-4 sm:max-h-[88vh]">

                {{-- Sticky header (appears on scroll) --}}
                <div class="absolute top-0 inset-x-0 z-30 flex items-center justify-between px-4 py-3 transition-all duration-200"
                     :class="pmScrolled ? 'border-b border-primary/15 bg-background/95 backdrop-blur-md' : ''">
                    <h3 class="font-heading font-bold text-base text-foreground truncate transition-opacity duration-200"
                        :class="pmScrolled ? 'opacity-100' : 'opacity-0'" x-text="pm?.name"></h3>
                    <button @click="pmClose()" type="button"
                            class="w-9 h-9 rounded-full flex items-center justify-center shrink-0 transition-colors"
                            :class="pmScrolled ? 'bg-surface-2 text-foreground/60 hover:text-foreground' : 'bg-black/45 text-white backdrop-blur-sm hover:bg-black/60'">
                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M6 18L18 6M6 6l12 12"/></svg>
                    </button>
                </div>

                {{-- Scrollable content --}}
                <div class="overflow-y-auto flex-1 overscroll-contain" @scroll="pmScrolled = $el.scrollTop > 110">

                    {{-- Large image area --}}
                    <div class="relative h-72 sm:h-80 w-full flex items-center justify-center overflow-hidden bg-gradient-to-b from-surface-2 to-background">
                        <div class="absolute inset-0" style="background: radial-gradient(circle at 50% 38%, color-mix(in srgb, var(--color-primary) 28%, transparent), transparent 72%)"></div>
                        <template x-if="pm?.image">
                            <img :src="pm.image" :alt="pm?.name" class="relative z-10 max-h-[85%] w-auto object-contain drop-shadow-[0_12px_24px_rgba(0,0,0,0.25)] pm-dish-enter">
                        </template>
                        <template x-if="!pm?.image">
                            <span class="text-8xl opacity-20 relative z-10">🍽️</span>
                        </template>
                    </div>

                    {{-- Product info --}}
                    <div class="px-4 pt-1 pb-3">
                        <h3 class="font-heading font-bold text-2xl text-foreground" x-text="pm?.name"></h3>
                        <p class="text-sm text-foreground/65 mt-1 leading-relaxed" x-show="pm?.description" x-text="pm?.description"></p>
                    </div>

                    {{-- Sections --}}
                    <div class="px-4 pb-5 pt-2 flex flex-col gap-5">

                        {{-- Taille (size variants) --}}
                        <template x-if="pm?.variants?.length > 0">
                            <section>
                                <p class="font-heading text-sm font-bold uppercase tracking-wide text-foreground/70 mb-3">Taille</p>
                                <div class="flex flex-wrap gap-2.5">
                                    <template x-for="v in pm.variants" :key="v.id">
                                        <button @click="pmVariant = v" type="button"
                                            :class="pmVariant?.id === v.id
                                                ? 'border-primary bg-primary/10 text-primary'
                                                : 'border-primary/15 text-foreground/70 hover:border-primary/50'"
                                            class="min-h-11 inline-flex items-center gap-2 px-3.5 py-2 rounded-xl border text-sm font-semibold transition-all duration-200">
                                            <span x-text="v.name.toLowerCase()"></span>
                                            <span x-text="pmFmt(v.price) + ' FCFA'" class="text-xs opacity-70"></span>
                                        </button>
                                    </template>
                                </div>
                            </section>
                        </template>

                        {{-- Retirer (removable ingredients) --}}
                        <template x-if="pm?.ingredients?.length > 0">
                            <section>
                                <div class="flex items-center gap-2 mb-3">
                                    <p class="font-heading text-sm font-bold uppercase tracking-wide text-foreground/70">Retirer</p>
                                    <span class="text-xs text-foreground/40 font-normal normal-case">facultatif</span>
                                </div>
                                <div class="flex flex-wrap gap-2">
                                    <template x-for="(ing, idx) in pm.ingredients" :key="idx">
                                        <button @click="pmToggle(ing)" type="button"
                                            :class="pmRemoved.includes(ing)
                                                ? 'border-accent-salmon/50 bg-accent-salmon/10 text-accent-salmon line-through'
                                                : 'border-primary/15 text-foreground/70 hover:border-primary/40'"
                                            class="inline-flex items-center gap-1.5 min-h-11 px-3.5 py-2 rounded-xl border text-sm transition-all duration-200">
                                            <svg class="w-4 h-4 shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <template x-if="!pmRemoved.includes(ing)">
                                                    <g><circle cx="12" cy="12" r="9" stroke-width="1.5"/><path stroke-linecap="round" stroke-width="1.5" d="M8 12h8"/></g>
                                                </template>
                                                <template x-if="pmRemoved.includes(ing)">
                                                    <g><circle cx="12" cy="12" r="9" stroke-width="1.5"/><path stroke-linecap="round" stroke-width="1.5" d="M15 9l-6 6M9 9l6 6"/></g>
                                                </template>
                                            </svg>
                                            <span x-text="ing"></span>
                                        </button>
                                    </template>
                                </div>
                            </section>
                        </template>
                    </div>
                </div>

                {{-- Gradient fade above footer --}}
                <div class="pointer-events-none absolute inset-x-0 bottom-20 z-10 h-6 bg-gradient-to-t from-background to-transparent"></div>

                {{-- Sticky footer --}}
                <div class="border-t border-primary/15 bg-background p-4 flex items-center gap-3" style="padding-bottom: max(1rem, env(safe-area-inset-bottom))">
                    {{-- Quantity selector --}}
                    <div class="flex items-center bg-surface-2 rounded-xl p-1 shrink-0">
                        <button @click="if (pmQty > 1) pmQty--" type="button"
                                class="w-10 h-10 rounded-lg flex items-center justify-center text-foreground/50 hover:text-foreground hover:bg-background transition-colors">
                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-width="2" d="M5 12h14"/></svg>
                        </button>
                        <span class="w-8 text-center font-heading text-base font-bold text-foreground" x-text="pmQty"></span>
                        <button @click="pmQty++" type="button"
                                class="w-10 h-10 rounded-lg flex items-center justify-center text-foreground/50 hover:text-foreground hover:bg-background transition-colors">
                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-width="2" d="M12 5v14m-7-7h14"/></svg>
                        </button>
                    </div>
                    {{-- Add button --}}
                    <button type="button" @click="pmAdd()"
                            class="flex-1 h-12 rounded-full bg-primary text-on-primary font-bold text-sm tracking-wide hover:bg-primary-dark transition-colors shadow-md flex items-center justify-center gap-1">
                        Ajouter &middot; <span x-text="pmFmt(pmTotal) + ' FCFA'"></span>
                    </button>
                </div>
            </div>
          </div>
        </template>
    </div>

    {{-- Cart drawer --}}
    <div x-data="{ cartOpen: false }"
         @toggle-cart.window="cartOpen = !cartOpen"
         @keydown.escape.window="cartOpen = false">

        {{-- Floating cart — circle button on mobile, wide bar on desktop --}}
        <div x-show="$store.cart.count > 0 && !cartOpen" x-cloak
             x-transition:enter="transition ease-out duration-300"
             x-transition:enter-start="translate-y-full opacity-0"
             x-transition:enter-end="translate-y-0 opacity-100"
             x-transition:leave="transition ease-in duration-200"
             x-transition:leave-start="translate-y-0 opacity-100"
             x-transition:leave-end="translate-y-full opacity-0"
             class="fixed z-[90]
                    bottom-5 right-5 md:bottom-0 md:right-0 md:left-0 md:px-4 md:pb-4">

            {{-- Mobile: floating circle --}}
            <button @click="cartOpen = true"
                    class="md:hidden relative w-14 h-14 rounded-full bg-accent-salmon text-on-primary shadow-food-lg flex items-center justify-center hover:scale-105 active:scale-95 transition-transform"
                    aria-label="Voir mon panier">
                <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 3h2l.4 2M7 13h10l4-8H5.4M7 13L5.4 5M7 13l-2.293 2.293c-.63.63-.184 1.707.707 1.707H17m0 0a2 2 0 100 4 2 2 0 000-4zm-8 2a2 2 0 100 4 2 2 0 000-4z"/></svg>
                <span class="absolute -top-1 -right-1 w-5.5 h-5.5 min-w-[22px] bg-accent-green text-on-primary text-[11px] font-bold rounded-full flex items-center justify-center shadow"
                      x-text="$store.cart.count"></span>
            </button>

            {{-- Desktop: wide bar --}}
            <button @click="cartOpen = true"
                    class="hidden md:flex w-full max-w-xl mx-auto items-center gap-3 bg-surface border border-primary/20 rounded-2xl px-5 py-3.5 shadow-food-lg hover:shadow-food-md transition-all"
                    aria-label="Voir mon panier">
                <div class="w-10 h-10 rounded-full bg-surface-2 flex items-center justify-center shrink-0">
                    <svg class="w-5 h-5 text-foreground/60" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 3h2l.4 2M7 13h10l4-8H5.4M7 13L5.4 5M7 13l-2.293 2.293c-.63.63-.184 1.707.707 1.707H17m0 0a2 2 0 100 4 2 2 0 000-4zm-8 2a2 2 0 100 4 2 2 0 000-4z"/></svg>
                </div>
                <div class="flex-1 text-left">
                    <span class="text-sm font-bold text-foreground">Voir mon panier</span>
                    <span class="text-xs text-foreground/50 ml-1" x-text="$store.cart.count + ' article' + ($store.cart.count > 1 ? 's' : '')"></span>
                </div>
                <span class="text-sm font-bold text-foreground" x-text="new Intl.NumberFormat('fr-FR').format($store.cart.total) + ' FCFA'"></span>
                <svg class="w-4 h-4 text-foreground/40" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 15l7-7 7 7"/></svg>
            </button>
        </div>

        {{-- Backdrop --}}
        <div x-show="cartOpen" x-cloak
             x-transition:enter="transition ease-out duration-300"
             x-transition:enter-start="opacity-0"
             x-transition:enter-end="opacity-100"
             x-transition:leave="transition ease-in duration-200"
             x-transition:leave-start="opacity-100"
             x-transition:leave-end="opacity-0"
             class="fixed inset-0 z-[95] bg-black/50" @click="cartOpen = false"></div>

        {{-- Drawer --}}
        <div x-show="cartOpen" x-cloak
             x-transition:enter="transition ease-out duration-300"
             x-transition:enter-start="opacity-0 translate-y-8 scale-95"
             x-transition:enter-end="opacity-100 translate-y-0 scale-100"
             x-transition:leave="transition ease-in duration-200"
             x-transition:leave-start="opacity-100 translate-y-0 scale-100"
             x-transition:leave-end="opacity-0 translate-y-8 scale-95"
             class="fixed inset-x-0 bottom-0 sm:inset-auto sm:top-1/2 sm:left-1/2 sm:-translate-x-1/2 sm:-translate-y-1/2 z-[100] w-full sm:max-w-lg bg-surface rounded-t-2xl sm:rounded-2xl shadow-2xl max-h-[85vh] flex flex-col overflow-hidden">

            {{-- Header --}}
            <div class="flex items-center justify-between px-6 pt-5 pb-4 border-b border-primary/15">
                <div>
                    <h3 class="font-heading font-bold text-lg text-foreground">Votre commande</h3>
                    <p class="text-xs text-foreground/50" x-text="$store.cart.count + ' article' + ($store.cart.count > 1 ? 's' : '')"></p>
                </div>
                <button @click="cartOpen = false" class="w-9 h-9 rounded-full border border-primary/20 flex items-center justify-center text-foreground/50 hover:text-foreground hover:border-primary/40 transition-all">
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/></svg>
                </button>
            </div>

            {{-- Cart items --}}
            <div class="flex-1 overflow-y-auto px-6 py-4 space-y-4">
                {{-- Empty state --}}
                <div x-show="$store.cart.count === 0" class="text-center py-8">
                    <span class="text-4xl block mb-3">🛒</span>
                    <p class="text-foreground/50 text-sm">Votre panier est vide</p>
                </div>

                {{-- Items --}}
                <template x-for="item in $store.cart.items" :key="item.key">
                    <div class="flex gap-3 pb-4 border-b border-dashed border-primary/15 last:border-0">
                        {{-- Image --}}
                        <div class="w-12 h-12 rounded-xl bg-surface-2 flex items-center justify-center shrink-0 overflow-hidden">
                            <template x-if="item.image">
                                <img :src="item.image" :alt="item.name" class="w-full h-full object-contain p-1">
                            </template>
                            <template x-if="!item.image">
                                <span class="text-lg opacity-30">🍽️</span>
                            </template>
                        </div>

                        {{-- Info --}}
                        <div class="flex-1 min-w-0">
                            <div class="flex items-start justify-between gap-2">
                                <div>
                                    <h4 class="font-heading font-bold text-sm text-foreground" x-text="item.name"></h4>
                                    <p x-show="item.variantName" class="text-xs text-foreground/50" x-text="item.variantName"></p>
                                    <p x-show="item.removedIngredients?.length > 0" class="text-xs text-red-400 mt-0.5">
                                        <span>sans </span><span x-text="item.removedIngredients?.join(', ')"></span>
                                    </p>
                                </div>
                                <span class="text-sm font-bold text-foreground shrink-0" x-text="new Intl.NumberFormat('fr-FR').format(item.price * item.quantity) + ' FCFA'"></span>
                            </div>

                            {{-- Quantity controls --}}
                            <div class="flex items-center gap-3 mt-2">
                                <div class="inline-flex items-center border border-primary/20 rounded-lg overflow-hidden">
                                    <button @click="$store.cart.updateQty(item.key, item.quantity - 1)" type="button"
                                            class="w-7 h-7 flex items-center justify-center text-foreground/40 hover:text-foreground hover:bg-surface-2 transition-colors">
                                        <template x-if="item.quantity > 1">
                                            <svg class="w-3 h-3" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-width="2" d="M5 12h14"/></svg>
                                        </template>
                                        <template x-if="item.quantity <= 1">
                                            <svg class="w-3 h-3" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"/></svg>
                                        </template>
                                    </button>
                                    <span class="w-6 text-center text-xs font-bold text-foreground" x-text="item.quantity"></span>
                                    <button @click="$store.cart.updateQty(item.key, item.quantity + 1)" type="button"
                                            class="w-7 h-7 flex items-center justify-center text-foreground/40 hover:text-foreground hover:bg-surface-2 transition-colors">
                                        <svg class="w-3 h-3" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-width="2" d="M12 5v14m-7-7h14"/></svg>
                                    </button>
                                </div>
                                <button @click="$store.cart.remove(item.key)" type="button"
                                        class="text-xs text-foreground/40 hover:text-red-500 transition-colors flex items-center gap-1">
                                    <svg class="w-3 h-3" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/></svg>
                                </button>
                            </div>
                        </div>
                    </div>
                </template>
            </div>

            {{-- Footer --}}
            <div x-show="$store.cart.count > 0" class="border-t border-primary/15 px-6 py-4 space-y-3">
                {{-- Total --}}
                <div class="flex items-center justify-between">
                    <span class="text-sm font-bold tracking-wider uppercase text-foreground/60">Total</span>
                    <span class="text-xl font-heading font-bold text-accent-green" x-text="new Intl.NumberFormat('fr-FR').format($store.cart.total) + ' FCFA'"></span>
                </div>

                {{-- CTA --}}
                <a href="{{ route('checkout') }}"
                   class="w-full py-3.5 rounded-xl bg-accent-salmon text-on-primary font-bold text-sm tracking-wide hover:bg-accent-salmon/90 transition-colors shadow-md flex items-center justify-center gap-2">
                    Finaliser ma commande
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 8l4 4m0 0l-4 4m4-4H3"/></svg>
                </a>

                <div class="flex items-center justify-between">
                    <p class="text-xs text-foreground/40">Sur place, à emporter ou livraison — vous choisirez à l'étape suivante.</p>
                    <button @click="$store.cart.clear()" type="button" class="text-xs text-foreground/40 hover:text-red-500 transition-colors shrink-0 ml-3">Vider</button>
                </div>
            </div>
        </div>
    </div>

    {{-- Footer --}}
    <footer class="bg-surface-2 border-t border-primary/20">
        <div class="max-w-6xl mx-auto px-6 lg:px-12 py-16">
            <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-10">
                {{-- Brand --}}
                <div>
                    <a href="{{ route('home') }}" class="font-heading font-bold text-xl">
                        {{ $nameMain }}@if($nameAccent ?? null) <span class="text-accent-mustard">{{ $nameAccent }}</span>@endif
                    </a>
                    <p class="text-foreground/60 text-sm mt-4 leading-relaxed">{{ $settings['site_description'] ?? 'Burgers, tacos et pizzas préparés minute, 100% faits maison. Sur place, à emporter, ou livrés chez vous.' }}</p>
                    <div class="flex items-center gap-3 mt-5">
                        @if($settings['instagram_url'] ?? null)
                        <a href="{{ $settings['instagram_url'] }}" target="_blank" class="social-circle bg-rose-500/20 text-rose-600 hover:bg-rose-600 hover:text-white">
                            <svg class="w-4 h-4" fill="currentColor" viewBox="0 0 24 24"><path d="M12 2.163c3.204 0 3.584.012 4.85.07 3.252.148 4.771 1.691 4.919 4.919.058 1.265.069 1.645.069 4.849 0 3.205-.012 3.584-.069 4.849-.149 3.225-1.664 4.771-4.919 4.919-1.266.058-1.644.07-4.85.07-3.204 0-3.584-.012-4.849-.07-3.26-.149-4.771-1.699-4.919-4.92-.058-1.265-.07-1.644-.07-4.849 0-3.204.013-3.583.07-4.849.149-3.227 1.664-4.771 4.919-4.919 1.266-.057 1.645-.069 4.849-.069zM12 0C8.741 0 8.333.014 7.053.072 2.695.272.273 2.69.073 7.052.014 8.333 0 8.741 0 12c0 3.259.014 3.668.072 4.948.2 4.358 2.618 6.78 6.98 6.98C8.333 23.986 8.741 24 12 24c3.259 0 3.668-.014 4.948-.072 4.354-.2 6.782-2.618 6.979-6.98.059-1.28.073-1.689.073-4.948 0-3.259-.014-3.667-.072-4.947-.196-4.354-2.617-6.78-6.979-6.98C15.668.014 15.259 0 12 0zm0 5.838a6.162 6.162 0 100 12.324 6.162 6.162 0 000-12.324zM12 16a4 4 0 110-8 4 4 0 010 8zm6.406-11.845a1.44 1.44 0 100 2.881 1.44 1.44 0 000-2.881z"/></svg>
                        </a>
                        @endif
                        @if($settings['facebook_url'] ?? null)
                        <a href="{{ $settings['facebook_url'] }}" target="_blank" class="social-circle bg-blue-700/30 text-blue-700 hover:bg-blue-700 hover:text-white">
                            <svg class="w-4 h-4" fill="currentColor" viewBox="0 0 24 24"><path d="M24 12.073c0-6.627-5.373-12-12-12s-12 5.373-12 12c0 5.99 4.388 10.954 10.125 11.854v-8.385H7.078v-3.47h3.047V9.43c0-3.007 1.792-4.669 4.533-4.669 1.312 0 2.686.235 2.686.235v2.953H15.83c-1.491 0-1.956.925-1.956 1.874v2.25h3.328l-.532 3.47h-2.796v8.385C19.612 23.027 24 18.062 24 12.073z"/></svg>
                        </a>
                        @endif
                        @if($settings['tiktok_url'] ?? null)
                        <a href="{{ $settings['tiktok_url'] }}" target="_blank" class="social-circle bg-foreground/80 text-on-primary hover:bg-foreground hover:text-on-primary">
                            <svg class="w-4 h-4" fill="currentColor" viewBox="0 0 24 24"><path d="M12.525.02c1.31-.02 2.61-.01 3.91-.02.08 1.53.63 3.09 1.75 4.17 1.12 1.11 2.7 1.62 4.24 1.79v4.03c-1.44-.05-2.89-.35-4.2-.97-.57-.26-1.1-.59-1.62-.93-.01 2.92.01 5.84-.02 8.75-.08 1.4-.54 2.79-1.35 3.94-1.31 1.92-3.58 3.17-5.91 3.21-1.43.08-2.86-.31-4.08-1.03-2.02-1.19-3.44-3.37-3.65-5.71-.02-.5-.03-1-.01-1.49.18-1.9 1.12-3.72 2.58-4.96 1.66-1.44 3.98-2.13 6.15-1.72.02 1.48-.04 2.96-.04 4.44-.99-.32-2.15-.23-3.02.37-.63.41-1.11 1.04-1.36 1.75-.21.51-.15 1.07-.14 1.61.24 1.64 1.82 3.02 3.5 2.87 1.12-.01 2.19-.66 2.77-1.61.19-.33.4-.67.41-1.06.1-1.79.06-3.57.07-5.36.01-4.03-.01-8.05.02-12.07z"/></svg>
                        </a>
                        @endif
                    </div>
                </div>

                {{-- Navigation --}}
                <div>
                    <h4 class="font-heading font-bold text-sm uppercase tracking-wider text-foreground mb-5">Navigation</h4>
                    <ul class="space-y-3">
                        <li><a href="{{ route('home') }}" class="text-foreground/60 hover:text-accent-green transition-colors text-sm">Accueil</a></li>
                        <li><a href="{{ route('menu') }}" class="text-foreground/60 hover:text-accent-green transition-colors text-sm">Menu</a></li>
                        <li><a href="{{ route('tracking') }}" class="text-foreground/60 hover:text-accent-green transition-colors text-sm">Suivi de commande</a></li>
                        <li><a href="{{ route('about') }}" class="text-foreground/60 hover:text-accent-green transition-colors text-sm">À propos</a></li>
                        <li><a href="{{ route('contact') }}" class="text-foreground/60 hover:text-accent-green transition-colors text-sm">Contact</a></li>
                    </ul>
                </div>

                {{-- Adresses --}}
                <div>
                    <h4 class="font-heading font-bold text-sm uppercase tracking-wider text-foreground mb-5">Nos adresses</h4>
                    <div class="space-y-4">
                        @foreach($locations as $location)
                        <div>
                            <p class="flex items-center gap-1.5 text-sm font-medium"><span class="text-accent-green">📍</span> {{ $location->name }}</p>
                            <a href="tel:{{ preg_replace('/\s/', '', $location->phone) }}" class="text-accent-green text-sm hover:underline">{{ $location->phone }}</a>
                        </div>
                        @endforeach
                    </div>
                </div>

                {{-- CTA --}}
                <div>
                    <h4 class="font-heading font-bold text-sm uppercase tracking-wider text-foreground mb-5">Envie de manger ?</h4>
                    <p class="text-foreground/60 text-sm mb-5 leading-relaxed">Composez votre commande en quelques clics.</p>
                    <a href="{{ route('menu') }}" class="btn-mustard text-sm">Commander</a>
                </div>
            </div>
        </div>

        <div class="border-t border-primary/20">
            <div class="max-w-6xl mx-auto px-6 lg:px-12 py-5 flex flex-col md:flex-row items-center justify-between gap-3">
                <p class="text-foreground/40 text-sm">&copy; {{ date('Y') }} {{ $settings['site_name'] ?? 'Niwa Food' }} &mdash; Tous droits réservés</p>
                <p class="text-foreground/40 text-sm">{{ $settings['footer_credit'] ?? 'Développé par Mehdi Abdi' }}</p>
            </div>
        </div>
    </footer>
</body>
</html>
