@extends('layouts.public')

@section('title', ($settings['site_name'] ?? 'Restaurant') . ' - Commande')

@section('content')

    <section class="py-8 lg:py-12 px-6 lg:px-8 relative overflow-hidden"
        x-data="{ search: '', activeCategory: 'burgers' }">
        <div class="max-w-7xl mx-auto">
            <div class="flex gap-8">

                {{-- Sidebar --}}
                <aside class="hidden lg:block w-[280px] shrink-0">
                    <div class="sticky top-20">
                        <div class="bg-surface rounded-2xl border border-primary/20 p-5 shadow-sm">
                            {{-- Search --}}
                            <div class="relative mb-5">
                                <svg class="absolute left-3.5 top-1/2 -translate-y-1/2 w-4 h-4 text-foreground/40" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"/></svg>
                                <input x-model="search" type="text" placeholder="Un plat, un ingréd"
                                       class="w-full pl-10 pr-4 py-2.5 bg-surface-2 border border-primary/20 rounded-xl text-foreground placeholder-foreground/40 text-sm focus:outline-none focus:border-accent-green transition-colors">
                            </div>

                            {{-- Header --}}
                            <div class="flex items-center justify-between mb-4">
                                <span class="text-xs font-bold tracking-wider uppercase text-foreground/50">La carte</span>
                                @php $totalProducts = $categories->sum(fn($c) => $c->subCategories->sum(fn($s) => $s->products->count())); @endphp
                                <span class="text-sm text-foreground/50">{{ $totalProducts }} plats</span>
                            </div>

                            {{-- Category list --}}
                            <div class="space-y-1.5">
                                @php
                                    $catIcons = [
                                        'burgers' => '🍔', 'tacos' => '🌮', 'pizzas' => '🍕',
                                        'boissons' => '🥤', 'accompagnements' => '🍟', 'salades' => '🥗',
                                    ];
                                @endphp
                                @foreach($categories as $category)
                                    @php $catCount = $category->subCategories->sum(fn($s) => $s->products->count()); @endphp
                                    <a href="#cat-{{ $category->slug }}"
                                       @click="activeCategory = '{{ $category->slug }}'"
                                       :class="activeCategory === '{{ $category->slug }}' ? 'bg-surface-2 border-primary/30 text-foreground' : 'border-transparent text-foreground/60 hover:bg-surface-2'"
                                       class="flex items-center justify-between px-3.5 py-2.5 rounded-xl text-sm font-medium transition-all duration-200 border">
                                        <span class="flex items-center gap-2.5">
                                            <span class="text-base">{{ $catIcons[$category->slug] ?? '🍽️' }}</span>
                                            <span>{{ $category->name }}</span>
                                        </span>
                                        <span class="text-xs text-foreground/40">{{ $catCount }}</span>
                                    </a>
                                @endforeach
                            </div>

                            {{-- Subcategories of active category --}}
                            @foreach($categories as $category)
                                @if($category->subCategories->count() > 1)
                                    <div x-show="activeCategory === '{{ $category->slug }}'" x-cloak x-transition class="mt-5 pt-4 border-t border-primary/15">
                                        <span class="text-xs font-bold tracking-wider uppercase text-foreground/40 block mb-2">{{ $category->name }}</span>
                                        @foreach($category->subCategories as $sub)
                                            <a href="#sub-{{ $sub->slug }}" class="flex items-center justify-between px-3 py-2 text-sm text-foreground/60 hover:text-foreground transition-colors">
                                                <span>{{ $sub->name }}</span>
                                                <span class="text-xs text-foreground/40">{{ $sub->products->count() }}</span>
                                            </a>
                                        @endforeach
                                    </div>
                                @endif
                            @endforeach
                        </div>
                    </div>
                </aside>

                {{-- Main content --}}
                <div class="flex-1 min-w-0">
                    {{-- Title area --}}
                    <div class="mb-8">
                        <span class="text-accent-green text-xs font-bold tracking-widest uppercase">{{ $settings['menu_eyebrow'] ?? 'Notre carte' }}</span>
                        <h1 class="font-heading text-3xl lg:text-4xl font-bold text-foreground mt-2">{{ $settings['menu_title'] ?? 'Composez votre commande' }}</h1>
                        <p class="text-foreground/55 mt-2">{{ $settings['menu_desc'] ?? 'Sur place, à emporter ou en livraison. Vous choisirez à la fin.' }}</p>
                    </div>

                    {{-- Mobile: search + category pills --}}
                    <div class="lg:hidden mb-6 space-y-3">
                        <div class="relative">
                            <svg class="absolute left-3.5 top-1/2 -translate-y-1/2 w-4 h-4 text-foreground/40" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"/></svg>
                            <input x-model="search" type="text" placeholder="Rechercher un plat"
                                   class="w-full pl-10 pr-4 py-2.5 bg-surface border border-primary/20 rounded-xl text-foreground placeholder-foreground/40 text-sm focus:outline-none focus:border-accent-green transition-colors">
                        </div>
                        <div class="flex gap-2 overflow-x-auto pb-2 no-scrollbar">
                            @foreach($categories as $category)
                                <a href="#cat-{{ $category->slug }}"
                                   class="shrink-0 px-3.5 py-2 rounded-full text-sm font-medium text-foreground/60 bg-surface border border-primary/20 hover:border-accent-green hover:text-accent-green transition-all whitespace-nowrap">
                                    {{ $catIcons[$category->slug] ?? '🍽️' }} {{ $category->name }}
                                </a>
                            @endforeach
                        </div>
                    </div>

                    {{-- Products --}}
                    @foreach($categories as $category)
                        @php $catCount = $category->subCategories->sum(fn($s) => $s->products->count()); @endphp
                        <div id="cat-{{ $category->slug }}" class="mb-10 scroll-mt-20"
                             x-intersect:enter="activeCategory = '{{ $category->slug }}'">

                            {{-- Category header with count + line --}}
                            <div class="flex items-center gap-3 mb-5">
                                <h2 class="font-heading text-xl font-bold text-foreground shrink-0">{{ $category->name }}</h2>
                                <span class="text-xs text-foreground/40 bg-surface-2 px-2.5 py-1 rounded-full shrink-0">{{ $catCount }}</span>
                                <div class="h-px bg-primary/20 flex-1"></div>
                            </div>

                            @foreach($category->subCategories as $sub)
                                @if($category->subCategories->count() > 1)
                                    <h3 id="sub-{{ $sub->slug }}" class="text-xs font-bold tracking-widest uppercase text-accent-green mb-4 {{ !$loop->first ? 'mt-8' : '' }}">{{ $sub->name }}</h3>
                                @endif

                                <div class="grid grid-cols-1 md:grid-cols-2 gap-4 mb-4">
                                    @foreach($sub->products as $product)
                                        @php
                                            $removable = ['salade', 'tomate', 'tomates', 'oignon', 'oignons', 'cornichon', 'cornichons', 'poivron', 'poivrons', 'frites', 'olive', 'olives', 'champignon', 'champignons', 'mais', 'maïs'];
                                            $descParts = $product->description ? array_map('trim', explode(',', $product->description)) : [];
                                            $ingredients = array_values(array_filter($descParts, fn($p) => in_array(strtolower($p), $removable)));
                                            $productData = [
                                                'id' => $product->id,
                                                'name' => $product->name,
                                                'description' => $product->description,
                                                'price' => (float) $product->price,
                                                'image' => $product->image ? asset($product->image) : null,
                                                'is_composable' => (bool) $product->is_composable,
                                                'variants' => $product->variants->map(fn($v) => [
                                                    'id' => $v->id,
                                                    'name' => $v->name,
                                                    'price' => (float) $v->price,
                                                    'type' => $v->type,
                                                ])->values()->toArray(),
                                                'ingredients' => $ingredients,
                                            ];
                                        @endphp
                                        <button type="button"
                                            @click="$dispatch('open-product-modal', {{ Js::from($productData) }})"
                                            aria-label="{{ $product->name }}, choisir les options"
                                            class="bg-surface border border-primary/15 rounded-2xl overflow-hidden hover:border-primary/30 hover:shadow-md transition-all duration-300 group relative text-left w-full"
                                            x-show="!search || '{{ strtolower(addslashes($product->name)) }}'.includes(search.toLowerCase()) || '{{ strtolower(addslashes($product->description ?? '')) }}'.includes(search.toLowerCase())">
                                            <div class="flex h-full">
                                                {{-- Text content --}}
                                                <div class="flex-1 p-4 flex flex-col justify-between min-w-0">
                                                    <div>
                                                        <h4 class="font-heading font-bold text-base text-foreground">{{ $product->name }}</h4>
                                                        @if($product->description)
                                                            <p class="text-xs text-foreground/50 mt-1 leading-relaxed line-clamp-2">{{ $product->description }}</p>
                                                        @endif
                                                        @if($product->variants->count())
                                                            <div class="mt-2.5">
                                                                <span class="inline-flex items-center gap-1 text-xs text-foreground/50 bg-surface-2 border border-primary/15 rounded-full px-3 py-1">
                                                                    taille {{ $product->variants->pluck('name')->map(fn($n) => strtolower($n))->join(' · ') }}
                                                                </span>
                                                            </div>
                                                        @endif
                                                    </div>
                                                    <div class="flex items-center gap-3 mt-3">
                                                        <span class="inline-flex items-center bg-accent-green/10 text-accent-green text-xs font-bold px-3 py-1.5 rounded-full">
                                                            @if($product->variants->count())dès @endif{{ number_format($product->price, 0, ',', ' ') }} FCFA
                                                        </span>
                                                        @if($product->is_composable)
                                                            <span class="text-xs text-foreground/50 flex items-center gap-1">
                                                                <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10.325 4.317c.426-1.756 2.924-1.756 3.35 0a1.724 1.724 0 002.573 1.066c1.543-.94 3.31.826 2.37 2.37a1.724 1.724 0 001.066 2.573c1.756.426 1.756 2.924 0 3.35a1.724 1.724 0 00-1.066 2.573c.94 1.543-.826 3.31-2.37 2.37a1.724 1.724 0 00-2.573 1.066c-.426 1.756-2.924 1.756-3.35 0a1.724 1.724 0 00-2.573-1.066c-1.543.94-3.31-.826-2.37-2.37a1.724 1.724 0 00-1.066-2.573c-1.756-.426-1.756-2.924 0-3.35a1.724 1.724 0 001.066-2.573c-.94-1.543.826-3.31 2.37-2.37.996.608 2.296.07 2.572-1.065z"/><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"/></svg>
                                                                à composer
                                                            </span>
                                                        @endif
                                                    </div>
                                                </div>

                                                {{-- Product image --}}
                                                <div class="w-28 sm:w-32 shrink-0 flex items-center justify-center p-2 relative">
                                                    @if($product->image)
                                                        <img src="{{ asset($product->image) }}" alt="{{ $product->name }}" class="w-full h-auto object-contain group-hover:scale-105 transition-transform duration-500 drop-shadow-md" loading="lazy">
                                                    @else
                                                        <span class="text-4xl opacity-30">
                                                            @php
                                                                $emoji = match($category->slug) {
                                                                    'burgers' => '🍔', 'tacos' => '🌮', 'pizzas' => '🍕',
                                                                    'boissons' => '🥤', 'accompagnements' => '🍟', 'salades' => '🥗',
                                                                    default => '🍽️',
                                                                };
                                                            @endphp
                                                            {{ $emoji }}
                                                        </span>
                                                    @endif
                                                    {{-- Action icon with cart badge --}}
                                                    <span class="absolute bottom-2 right-2 w-9 h-9 rounded-full bg-primary text-on-primary flex items-center justify-center shadow-md group-hover:bg-primary-dark transition-colors">
                                                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6v6m0 0v6m0-6h6m-6 0H6"/></svg>
                                                    </span>
                                                    <span x-show="$store.cart.countFor({{ $product->id }}) > 0" x-cloak
                                                          class="absolute bottom-8 right-0 w-5 h-5 bg-accent-green text-on-primary text-xs font-bold rounded-full flex items-center justify-center shadow"
                                                          x-text="$store.cart.countFor({{ $product->id }})"></span>
                                                </div>
                                            </div>
                                        </button>
                                    @endforeach
                                </div>
                            @endforeach
                        </div>
                    @endforeach
                </div>
            </div>
        </div>
    </section>

    <style>
        .no-scrollbar::-webkit-scrollbar { display: none; }
        .no-scrollbar { -ms-overflow-style: none; scrollbar-width: none; }
    </style>

@endsection
