@extends('layouts.public')

@section('title', ($settings['site_name'] ?? 'Restaurant') . ' - Fast-food fait maison')

@section('content')

    {{-- Hero Section --}}
    <section class="relative pt-10 pb-8 lg:pt-16 lg:pb-12 px-6 lg:px-12 overflow-hidden min-h-[min(calc(100vh-72px),750px)] flex items-start">
        {{-- Background organic blob — round, right side like original --}}
        <svg class="deco-blob absolute top-[-5%] right-[-4%] w-[52%] h-[110%] pointer-events-none z-0" viewBox="0 0 500 600" fill="none" preserveAspectRatio="none">
            <path d="M320,15 Q470,40 480,160 Q490,290 430,390 Q390,460 420,540 Q440,600 340,610 Q240,620 160,570 Q80,520 100,410 Q120,310 80,220 Q50,140 130,70 Q210,15 320,15Z" fill="#D9C5AA" opacity="0.55"/>
        </svg>
        {{-- Wavy gold lines spanning full width top — like original --}}
        <svg class="deco-wave absolute top-0 left-0 w-full h-[60%] pointer-events-none z-0" viewBox="0 0 1440 500" fill="none" preserveAspectRatio="none">
            <path d="M0,60 Q200,20 400,80 Q600,140 800,60 Q1000,0 1200,60 Q1320,100 1440,50" stroke="#C9A97A" stroke-width="1.5" opacity="0.3" fill="none"/>
            <path d="M900,0 Q1050,40 1100,120 Q1150,200 1100,300 Q1060,380 1140,450 Q1200,510 1280,480 Q1380,450 1440,500" stroke="#C9A97A" stroke-width="1.2" opacity="0.2" fill="none"/>
        </svg>
        {{-- Sauces peeking bottom-left of hero (like original) --}}
        <img src="{{ asset('images/sauces.png') }}" alt="" class="hidden lg:block absolute left-0 bottom-0 w-auto max-w-[200px] object-contain pointer-events-none z-[-1]" style="transform: translateX(-35%) translateY(15%)" loading="eager">
        <div class="max-w-6xl mx-auto relative z-10 w-full">
            <div class="grid grid-cols-1 lg:grid-cols-2 gap-12 lg:gap-16 items-center">
                {{-- Left: Text --}}
                <div>
                    <span class="section-label">{{ $settings['hero_eyebrow'] ?? 'FAST-FOOD FAIT MAISON' }}</span>
                    <h1 class="font-heading text-4xl md:text-5xl lg:text-6xl font-extrabold leading-tight mt-4">
                        Commandez vos<br>
                        <span class="font-normal" style="color:#B87D5E">plats préférés</span><br>
                        en toute<br>
                        simplicité
                    </h1>
                    <p class="text-foreground/70 mt-6 text-lg leading-relaxed max-w-lg">
                        {{ $settings['hero_description'] ?? 'Tacos, pizzas, burgers et salades préparés minute, 100% faits maison. Sur place, à emporter, ou livrés directement chez vous.' }}
                    </p>
                    <div class="mt-8">
                        <a href="{{ route('menu') }}" class="btn-primary-warm text-base px-9 py-3.5">
                            {{ $settings['hero_cta'] ?? 'Passer votre commande' }}
                            <span class="w-8 h-8 rounded-full bg-on-primary/20 flex items-center justify-center ml-1">
                                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M13 7l5 5m0 0l-5 5m5-5H6"/></svg>
                            </span>
                        </a>
                    </div>
                </div>

                {{-- Right: Circular Image Carousel --}}
                <div class="flex justify-center lg:justify-center">
                    <div class="relative">
                        <div class="absolute -inset-3 rounded-full border border-primary/20"></div>
                        <div class="absolute -inset-6 rounded-full border border-primary/10"></div>

                        @php
                            $heroImages = $slides->count() > 0
                                ? $slides->map(fn($s) => [
                                    'src' => str_starts_with($s->image, 'images/') ? $s->image : 'storage/' . $s->image,
                                    'caption' => $s->caption ?? $s->title ?? '',
                                ])->toArray()
                                : [
                                    ['src' => 'images/hero-burger1.png', 'caption' => 'Burger juteux, pain toasté'],
                                    ['src' => 'images/tacos-gilera.png', 'caption' => 'Tacos généreux, sauce signature'],
                                    ['src' => 'images/hero-pizza.png', 'caption' => 'Pizza maison, pâte du jour'],
                                    ['src' => 'images/frites.png', 'caption' => 'Frites croustillantes dorées'],
                                    ['src' => 'images/salade.png', 'caption' => 'Salade César, croquante et fraîche'],
                                ];
                        @endphp

                        <div class="hero-circle-bg w-[260px] h-[260px] md:w-[310px] md:h-[310px] lg:w-[370px] lg:h-[370px] xl:w-[450px] xl:h-[450px] rounded-full overflow-hidden shadow-food-lg relative" style="background-color:#E8DDD0">
                            @foreach($heroImages as $i => $img)
                                <div class="hero-slide absolute inset-0 {{ $i === 0 ? 'active' : '' }}">
                                    <img src="{{ asset($img['src']) }}" alt="{{ $img['caption'] }}" class="w-full h-full object-cover">
                                </div>
                            @endforeach
                            {{-- Caption pill inside circle at bottom --}}
                            <div class="absolute bottom-6 left-1/2 -translate-x-1/2 z-20 w-max">
                                @foreach($heroImages as $i => $img)
                                    <span class="hero-slide-text {{ $i === 0 ? 'active' : '' }} bg-surface/85 backdrop-blur-sm text-foreground/80 text-xs font-medium px-4 py-2 rounded-full border border-primary/20 whitespace-nowrap">
                                        {{ $img['caption'] }}
                                    </span>
                                @endforeach
                            </div>
                        </div>

                        {{-- Prev/Next --}}
                        <button onclick="document.dispatchEvent(new CustomEvent('hero-prev'))" class="absolute left-[-50px] top-1/2 -translate-y-1/2 w-9 h-9 rounded-full bg-surface/80 border border-primary/20 flex items-center justify-center text-foreground/40 hover:text-foreground transition-all">
                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 19l-7-7 7-7"/></svg>
                        </button>
                        <button onclick="document.dispatchEvent(new CustomEvent('hero-next'))" class="absolute right-[-50px] top-1/2 -translate-y-1/2 w-9 h-9 rounded-full bg-surface/80 border border-primary/20 flex items-center justify-center text-foreground/40 hover:text-foreground transition-all">
                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"/></svg>
                        </button>

                        {{-- Dots --}}
                        <div class="absolute -bottom-8 left-1/2 -translate-x-1/2 flex items-center gap-2">
                            @foreach($heroImages as $i => $img)
                                <span class="hero-dot w-2.5 h-2.5 rounded-full transition-all duration-300 {{ $i === 0 ? 'bg-accent-green scale-110' : 'bg-primary/40' }}"></span>
                            @endforeach
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>

    {{-- Best-sellers Section --}}
    <section class="fade-up py-20 lg:py-28 px-6 lg:px-12 relative overflow-hidden" x-data>
        {{-- Sauces image — LEFT side, barely peeking --}}
        <img src="{{ asset('images/sauces.png') }}" alt="" class="hidden lg:block absolute left-0 top-1/2 w-auto max-h-[75%] max-w-[300px] object-contain object-left pointer-events-none z-0" style="transform: translateY(-50%) translateX(-40%)" loading="lazy">
        <div class="max-w-6xl mx-auto relative z-10">
            <div class="text-center max-w-2xl mx-auto mb-12">
                <span class="section-label">{{ $settings['bestseller_eyebrow'] ?? 'Notre sélection' }}</span>
                <h2 class="section-title mt-3">{{ $settings['bestseller_title'] ?? 'Les best-sellers' }}</h2>
                <p class="section-desc mx-auto mt-3">{{ $settings['bestseller_desc'] ?? 'Les recettes que nos clients choisissent encore et encore.' }}</p>
            </div>

            <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">
                @foreach($bestSellers as $product)
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
                        class="card-food group text-left cursor-pointer">
                        <div class="aspect-[4/3] bg-surface-2 flex items-center justify-center overflow-hidden">
                            @if($product->image)
                                <img src="{{ asset($product->image) }}" alt="{{ $product->name }}" class="w-full h-full object-cover group-hover:scale-105 transition-transform duration-500">
                            @else
                                <span class="text-7xl">
                                    @php
                                        $cat = $product->subCategory->category->slug ?? '';
                                        $emoji = match($cat) { 'burgers' => '🍔', 'tacos' => '🌮', 'pizzas' => '🍕', 'boissons' => '🥤', 'accompagnements' => '🍟', 'salades' => '🥗', default => '🍽️' };
                                    @endphp
                                    {{ $emoji }}
                                </span>
                            @endif
                        </div>
                        <div class="p-5">
                            <div class="flex items-start justify-between gap-3">
                                <h3 class="font-bold text-foreground">{{ $product->name }}</h3>
                                <span class="text-accent-mustard font-bold shrink-0">{{ number_format($product->price, 0, ',', ' ') }} FCFA</span>
                            </div>
                            <p class="text-sm text-foreground/60 mt-2 line-clamp-2 leading-relaxed">{{ $product->description }}</p>
                            @if($product->variants->count())
                                <div class="flex flex-wrap gap-1.5 mt-3">
                                    @foreach($product->variants as $variant)
                                        <span class="rounded-full bg-surface-2 border border-primary/30 px-3 py-1 text-xs text-foreground/60">{{ $variant->name }}</span>
                                    @endforeach
                                </div>
                            @endif
                        </div>
                    </button>
                @endforeach
            </div>

            <div class="text-center mt-10">
                <a href="{{ route('menu') }}" class="btn-mustard">
                    {{ $settings['bestseller_cta'] ?? 'Explorer tout le menu' }}
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 8l4 4m0 0l-4 4m4-4H3"/></svg>
                </a>
            </div>
        </div>
    </section>

    {{-- How it works --}}
    <section class="fade-up py-20 lg:py-28 px-6 lg:px-12 relative overflow-hidden">
        {{-- Background organic blob (right side, like original mid-page) --}}
        <svg class="deco-blob hidden lg:block absolute -right-[10%] top-[-20%] w-[45%] h-[140%] pointer-events-none z-0" viewBox="0 0 500 700" fill="none" preserveAspectRatio="none">
            <path d="M300,20 Q480,60 460,200 Q440,340 500,450 Q520,560 400,650 Q280,700 200,620 Q130,540 170,400 Q200,280 140,180 Q100,100 200,30 Q300,20 300,20Z" fill="#EDE4D6" opacity="0.4"/>
        </svg>
        {{-- Deco image — RIGHT side, barely peeking --}}
        <img src="{{ asset('images/deco.png') }}" alt="" class="hidden lg:block absolute right-0 top-1/2 w-auto max-h-[80%] max-w-[300px] object-contain object-right pointer-events-none z-0" style="transform: translateY(-50%) translateX(40%)" loading="lazy">
        <div class="max-w-6xl mx-auto relative z-10">
            <div class="text-center max-w-2xl mx-auto mb-12">
                <span class="section-label">{{ $settings['steps_eyebrow'] ?? 'En quatre temps' }}</span>
                <h2 class="section-title mt-3">{{ $settings['steps_title'] ?? 'Comment ça marche' }}</h2>
                <p class="section-desc mx-auto mt-3">{{ $settings['steps_desc'] ?? 'De votre écran à votre table, en quatre étapes.' }}</p>
            </div>

            <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-6">
                @foreach($steps as $step)
                    <div class="card-food-flat p-7 text-center">
                        <div class="w-14 h-14 rounded-2xl bg-accent-green/10 flex items-center justify-center mx-auto mb-4">
                            <span class="text-accent-green font-heading font-bold text-xl">{{ str_pad($step->number, 2, '0', STR_PAD_LEFT) }}</span>
                        </div>
                        <h3 class="font-heading font-bold text-lg text-foreground">{{ $step->title }}</h3>
                        <p class="text-foreground/60 text-sm mt-2 leading-relaxed">{{ $step->description }}</p>
                    </div>
                @endforeach
            </div>
        </div>
    </section>

    {{-- About / Story --}}
    <section class="fade-up py-20 lg:py-28 px-6 lg:px-12 relative overflow-hidden">
        <div class="max-w-3xl mx-auto relative z-10 text-center">
            <span class="section-label">{{ $settings['about_eyebrow'] ?? "Notre esprit" }}</span>
            <h2 class="section-title mt-3">{{ $settings['about_title'] ?? 'Le burger artisanal, c\'est notre spécialité' }}</h2>
            <p class="text-foreground/70 mt-6 leading-relaxed text-lg">{{ $settings['about_text_1'] ?? 'Tout part d\'une idée simple : préparer chaque burger, tacos et pizza comme s\'il était le premier.' }}</p>
            <p class="text-foreground/70 mt-4 leading-relaxed">{{ $settings['about_text_2'] ?? 'Depuis nos cuisines à Kouba et Chéraga, on sert celles et ceux qui veulent manger vite sans sacrifier le goût.' }}</p>

            <div class="grid grid-cols-3 gap-6 mt-12 max-w-lg mx-auto">
                <div class="text-center">
                    <span class="text-4xl lg:text-5xl font-heading font-extrabold text-accent-mustard block" data-counter="{{ $settings['stat_1_value'] ?? 2 }}">0</span>
                    <p class="text-foreground/60 text-xs mt-1">{{ $settings['stat_1_label'] ?? 'Adresses à Alger' }}</p>
                </div>
                <div class="text-center">
                    <span class="text-4xl lg:text-5xl font-heading font-extrabold text-accent-mustard block" data-counter="{{ $settings['stat_2_value'] ?? 100 }}">0</span>
                    <p class="text-foreground/60 text-xs mt-1">{{ $settings['stat_2_label'] ?? '% Fait maison' }}</p>
                </div>
                <div class="text-center">
                    <span class="text-4xl lg:text-5xl font-heading font-extrabold text-accent-mustard block">12h+</span>
                    <p class="text-foreground/60 text-xs mt-1">{{ $settings['stat_3_label'] ?? "D'ouverture/jour" }}</p>
                </div>
            </div>
        </div>
    </section>

    {{-- Testimonials --}}
    <section class="fade-up py-20 lg:py-28 px-6 lg:px-12 relative overflow-hidden" x-data="{ current: 0, total: {{ $testimonials->count() }} }">
        {{-- Sauces2 image — LEFT side, barely peeking --}}
        <img src="{{ asset('images/sauces2.png') }}" alt="" class="hidden lg:block absolute left-0 top-1/2 w-auto max-h-[75%] max-w-[300px] object-contain object-left pointer-events-none z-0" style="transform: translateY(-50%) translateX(-40%)" loading="lazy">
        <div class="max-w-6xl mx-auto relative z-10">
            <div class="text-center max-w-2xl mx-auto mb-12">
                <span class="section-label">{{ $settings['testimonials_eyebrow'] ?? 'La parole aux habitués' }}</span>
                <h2 class="section-title mt-3">{{ $settings['testimonials_title'] ?? 'Ce que disent nos clients' }}</h2>
                <p class="section-desc mx-auto mt-3">{{ $settings['testimonials_desc'] ?? 'Des expériences partagées par nos clients.' }}</p>
            </div>

            <div class="relative overflow-hidden">
                <div class="flex transition-transform duration-500 ease-out" :style="'transform: translateX(-' + (current * 100) + '%)'">
                    @foreach($testimonials as $index => $testimonial)
                        <div class="w-full shrink-0 px-2">
                            <div class="card-food-flat p-8 max-w-2xl mx-auto">
                                <p class="text-foreground/80 text-base md:text-lg italic leading-relaxed">"{{ $testimonial->content }}"</p>
                                <div class="flex justify-center gap-0.5 text-accent-mustard mt-5">
                                    @for($i = 0; $i < $testimonial->rating; $i++) <span>★</span> @endfor
                                </div>
                                <div class="mt-4">
                                    <p class="font-bold text-foreground">{{ $testimonial->name }}</p>
                                    @if($testimonial->location)
                                        <p class="text-accent-green text-sm mt-0.5">📍 {{ $testimonial->location }}</p>
                                    @endif
                                </div>
                            </div>
                        </div>
                    @endforeach
                </div>
            </div>

            <div class="flex items-center justify-center gap-4 mt-8">
                <button @click="current = current > 0 ? current - 1 : total - 1"
                        class="w-9 h-9 rounded-full bg-surface border border-primary/40 flex items-center justify-center text-foreground/50 hover:text-foreground hover:border-primary transition-all">
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 19l-7-7 7-7"/></svg>
                </button>
                <span class="text-sm text-foreground/60 font-medium">Avis <span x-text="current + 1" class="text-foreground">1</span> sur <span class="text-foreground">{{ $testimonials->count() }}</span></span>
                <button @click="current = current < total - 1 ? current + 1 : 0"
                        class="w-9 h-9 rounded-full bg-surface border border-primary/40 flex items-center justify-center text-foreground/50 hover:text-foreground hover:border-primary transition-all">
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"/></svg>
                </button>
            </div>
        </div>
    </section>

    {{-- Locations --}}
    <section class="fade-up py-20 lg:py-28 px-6 lg:px-12 relative overflow-hidden">
        {{-- Deco image — RIGHT side, barely peeking --}}
        <img src="{{ asset('images/deco.png') }}" alt="" class="hidden lg:block absolute right-0 top-1/2 w-auto max-h-[80%] max-w-[300px] object-contain object-right pointer-events-none z-0" style="transform: translateY(-50%) translateX(40%)" loading="lazy">
        <div class="max-w-5xl mx-auto relative z-10">
            <div class="text-center max-w-2xl mx-auto mb-14">
                <span class="section-label">{{ $settings['locations_eyebrow'] ?? 'Venez nous voir' }}</span>
                <h2 class="section-title mt-3">{{ $settings['locations_title'] ?? 'Deux adresses, un seul régal' }}</h2>
                <p class="section-desc mx-auto mt-3">{{ $settings['locations_desc'] ?? 'Deux cuisines, la même exigence de fraîcheur et de générosité.' }}</p>
            </div>

            <div class="grid grid-cols-1 lg:grid-cols-2 gap-8">
                @foreach($locations as $location)
                    <div class="card-food group">
                        <div class="aspect-video bg-surface-2 flex items-center justify-center overflow-hidden">
                            @php
                                $locImage = match(strtolower($location->name)) {
                                    'kouba' => 'images/kouba.png',
                                    'chéraga', 'cheraga' => 'images/cheraga.png',
                                    default => null,
                                };
                            @endphp
                            @if($location->image)
                                <img src="{{ asset('storage/' . $location->image) }}" alt="{{ $location->name }}" class="w-full h-full object-cover group-hover:scale-105 transition-transform duration-500">
                            @elseif($locImage)
                                <img src="{{ asset($locImage) }}" alt="{{ $location->name }}" class="w-full h-full object-cover group-hover:scale-105 transition-transform duration-500">
                            @else
                                <span class="text-6xl">🏪</span>
                            @endif
                        </div>
                        <div class="p-6 space-y-2">
                            <h3 class="font-heading font-bold text-xl text-foreground">{{ $location->name }}</h3>
                            <p class="text-foreground/60 text-sm">{{ $location->address }}</p>
                            @if($location->phone)
                                <p>
                                    <a href="tel:{{ preg_replace('/\s/', '', $location->phone) }}" class="text-accent-green font-semibold text-sm hover:underline">📞 {{ $location->phone }}</a>
                                </p>
                            @endif
                            @if($location->hours)
                                <p class="text-foreground/60 text-xs whitespace-pre-line leading-relaxed">🕐 {{ $location->hours }}</p>
                            @endif
                            @if($location->map_url)
                                <a href="{{ $location->map_url }}" target="_blank" class="btn-outline-green text-xs !py-2 !px-4 mt-2 inline-flex">
                                    Itinéraire
                                    <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 6H6a2 2 0 00-2 2v10a2 2 0 002 2h10a2 2 0 002-2v-4M14 4h6m0 0v6m0-6L10 14"/></svg>
                                </a>
                            @endif
                        </div>
                    </div>
                @endforeach
            </div>
        </div>
    </section>

    {{-- Social CTA --}}
    <section class="py-12 bg-surface-2 border-y border-primary/20">
        <div class="text-center">
            <p class="section-label mb-5">{{ $settings['social_cta'] ?? 'Suivez l\'aventure' }}</p>
            <div class="flex items-center justify-center gap-4">
                @if($settings['facebook_url'] ?? null)
                <a href="{{ $settings['facebook_url'] }}" target="_blank" class="social-circle !w-12 !h-12 bg-blue-700/30 text-blue-700 hover:bg-blue-700 hover:text-white">
                    <svg class="w-5 h-5" fill="currentColor" viewBox="0 0 24 24"><path d="M24 12.073c0-6.627-5.373-12-12-12s-12 5.373-12 12c0 5.99 4.388 10.954 10.125 11.854v-8.385H7.078v-3.47h3.047V9.43c0-3.007 1.792-4.669 4.533-4.669 1.312 0 2.686.235 2.686.235v2.953H15.83c-1.491 0-1.956.925-1.956 1.874v2.25h3.328l-.532 3.47h-2.796v8.385C19.612 23.027 24 18.062 24 12.073z"/></svg>
                </a>
                @endif
                @if($settings['instagram_url'] ?? null)
                <a href="{{ $settings['instagram_url'] }}" target="_blank" class="social-circle !w-12 !h-12 bg-rose-500/20 text-rose-600 hover:bg-rose-600 hover:text-white">
                    <svg class="w-5 h-5" fill="currentColor" viewBox="0 0 24 24"><path d="M12 2.163c3.204 0 3.584.012 4.85.07 3.252.148 4.771 1.691 4.919 4.919.058 1.265.069 1.645.069 4.849 0 3.205-.012 3.584-.069 4.849-.149 3.225-1.664 4.771-4.919 4.919-1.266.058-1.644.07-4.85.07-3.204 0-3.584-.012-4.849-.07-3.26-.149-4.771-1.699-4.919-4.92-.058-1.265-.07-1.644-.07-4.849 0-3.204.013-3.583.07-4.849.149-3.227 1.664-4.771 4.919-4.919 1.266-.057 1.645-.069 4.849-.069zM12 0C8.741 0 8.333.014 7.053.072 2.695.272.273 2.69.073 7.052.014 8.333 0 8.741 0 12c0 3.259.014 3.668.072 4.948.2 4.358 2.618 6.78 6.98 6.98C8.333 23.986 8.741 24 12 24c3.259 0 3.668-.014 4.948-.072 4.354-.2 6.782-2.618 6.979-6.98.059-1.28.073-1.689.073-4.948 0-3.259-.014-3.667-.072-4.947-.196-4.354-2.617-6.78-6.979-6.98C15.668.014 15.259 0 12 0zm0 5.838a6.162 6.162 0 100 12.324 6.162 6.162 0 000-12.324zM12 16a4 4 0 110-8 4 4 0 010 8zm6.406-11.845a1.44 1.44 0 100 2.881 1.44 1.44 0 000-2.881z"/></svg>
                </a>
                @endif
                @if($settings['tiktok_url'] ?? null)
                <a href="{{ $settings['tiktok_url'] }}" target="_blank" class="social-circle !w-12 !h-12 bg-foreground/80 text-on-primary hover:bg-foreground hover:text-on-primary">
                    <svg class="w-5 h-5" fill="currentColor" viewBox="0 0 24 24"><path d="M12.525.02c1.31-.02 2.61-.01 3.91-.02.08 1.53.63 3.09 1.75 4.17 1.12 1.11 2.7 1.62 4.24 1.79v4.03c-1.44-.05-2.89-.35-4.2-.97-.57-.26-1.1-.59-1.62-.93-.01 2.92.01 5.84-.02 8.75-.08 1.4-.54 2.79-1.35 3.94-1.31 1.92-3.58 3.17-5.91 3.21-1.43.08-2.86-.31-4.08-1.03-2.02-1.19-3.44-3.37-3.65-5.71-.02-.5-.03-1-.01-1.49.18-1.9 1.12-3.72 2.58-4.96 1.66-1.44 3.98-2.13 6.15-1.72.02 1.48-.04 2.96-.04 4.44-.99-.32-2.15-.23-3.02.37-.63.41-1.11 1.04-1.36 1.75-.21.51-.15 1.07-.14 1.61.24 1.64 1.82 3.02 3.5 2.87 1.12-.01 2.19-.66 2.77-1.61.19-.33.4-.67.41-1.06.1-1.79.06-3.57.07-5.36.01-4.03-.01-8.05.02-12.07z"/></svg>
                </a>
                @endif
            </div>
        </div>
    </section>

    <style>
        .hero-slide { opacity: 0; transition: opacity 0.9s cubic-bezier(0.16, 1, 0.3, 1); position: absolute; inset: 0; }
        .hero-slide.active { opacity: 1; }
        .hero-slide-text { opacity: 0; transition: opacity 0.7s ease; position: absolute; }
        .hero-slide-text.active { opacity: 1; position: relative; }
    </style>

@endsection
