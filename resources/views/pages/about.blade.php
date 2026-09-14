@extends('layouts.public')

@section('title', 'NIWA FOOD - À propos')

@section('content')

    {{-- Hero —  left-aligned, split-color title --}}
    <section class="pt-10 pb-6 lg:pt-14 lg:pb-8 px-6 lg:px-12">
        <div class="max-w-6xl mx-auto">
            <span class="text-accent-green text-xs font-bold tracking-widest uppercase">Notre histoire</span>
            <h1 class="font-heading text-4xl lg:text-5xl font-bold leading-tight mt-3">
                Notre carte se lit<br>
                <span class="text-accent-mustard font-normal">comme un garage</span>
            </h1>
            <p class="text-foreground/55 mt-4 max-w-lg leading-relaxed">{{ $settings['about_page_subtitle'] ?? 'Niwa Food est un fast-food fait maison, né à Kouba et installé depuis à Chéraga. Notre spécialité tient en deux mots : le burger artisanal.' }}</p>
            <div class="h-1 w-12 bg-accent-green rounded-full mt-5"></div>
        </div>
    </section>

    {{-- Story — storefront photo + text --}}
    <section class="fade-up py-12 lg:py-20 px-6 lg:px-12">
        <div class="max-w-6xl mx-auto">
            <div class="grid grid-cols-1 lg:grid-cols-2 gap-10 lg:gap-16 items-start">
                <div class="rounded-2xl overflow-hidden shadow-lg">
                    <img src="{{ asset('images/kouba.png') }}" alt="Niwa Food Kouba" class="w-full h-auto object-cover">
                </div>
                <div class="lg:pt-4">
                    <h2 class="font-heading text-2xl lg:text-3xl font-bold text-accent-green">Tout part d'une idée simple</h2>
                    <p class="text-foreground/65 mt-5 leading-relaxed">Préparer chaque burger, chaque tacos et chaque pizza comme s'il était le premier de la journée. Rien n'est monté à l'avance pour être réchauffé plus tard : quand vous commandez, on commence.</p>
                    <p class="text-foreground/65 mt-4 leading-relaxed">Le reste, c'est une question de tempérament. On aime les deux-roues, alors nos plats en portent les noms. Un T-MAX, un MALOSSI, une VESPA — pas pour faire joli, mais parce que c'est le vocabulaire de la maison, et que nos habitués commandent désormais par plaque plutôt que par ingrédient.</p>
                </div>
            </div>
        </div>
    </section>

    {{-- Name decoder --}}
    <section class="fade-up py-12 lg:py-20 px-6 lg:px-12">
        <div class="max-w-6xl mx-auto">
            <span class="text-foreground/40 text-xs font-bold tracking-widest uppercase">Le décodeur</span>
            <h2 class="font-heading text-2xl lg:text-3xl font-bold text-foreground mt-2">D'où viennent les noms</h2>
            <p class="text-foreground/55 mt-3 max-w-xl">Chaque plat rend hommage à une référence de l'univers du scooter et de la moto. Voici la traduction.</p>

            <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 gap-4 mt-8">
                @php
                    $names = [
                        ['name' => 'T-MAX', 'desc' => 'Le maxi-scooter Yamaha'],
                        ['name' => 'N-MAX', 'desc' => 'Son petit frère, plus nerveux'],
                        ['name' => 'VESPA', 'desc' => "L'italien qui a tout commencé"],
                        ['name' => 'GILERA', 'desc' => 'Constructeur italien de motos'],
                        ['name' => 'POLINI', 'desc' => 'Préparateur moteur italien'],
                        ['name' => 'MALOSSI', 'desc' => 'Pièces de performance pour scooters'],
                        ['name' => 'GIVI', 'desc' => 'Bagagerie et top-cases'],
                        ['name' => 'ARAI', 'desc' => 'Les casques japonais'],
                        ['name' => 'J-COSTA', 'desc' => 'Variateurs de transmission'],
                    ];
                @endphp
                @foreach($names as $item)
                    <div class="bg-surface border border-primary/15 rounded-xl px-5 py-4 hover:border-primary/30 transition-colors">
                        <h4 class="font-heading font-bold text-foreground">{{ $item['name'] }}</h4>
                        <p class="text-foreground/50 text-sm mt-0.5">{{ $item['desc'] }}</p>
                    </div>
                @endforeach
            </div>
        </div>
    </section>

    {{-- Kitchen principles --}}
    <section class="fade-up py-12 lg:py-20 px-6 lg:px-12">
        <div class="max-w-6xl mx-auto">
            <span class="text-foreground/40 text-xs font-bold tracking-widest uppercase">En cuisine</span>
            <h2 class="font-heading text-2xl lg:text-3xl font-bold text-foreground mt-2">Ce qu'on refuse de faire</h2>

            <div class="grid grid-cols-1 md:grid-cols-2 gap-5 mt-8">
                <div class="bg-surface border border-primary/15 rounded-2xl p-6">
                    <div class="w-10 h-10 rounded-full bg-accent-mustard/10 flex items-center justify-center mb-3">
                        <span class="text-lg">🍞</span>
                    </div>
                    <h3 class="font-heading font-bold text-foreground">Le pain, toasté minute</h3>
                    <p class="text-foreground/55 text-sm mt-2 leading-relaxed">Aucun burger n'attend sous une lampe. Le pain passe au grill au moment où la commande tombe, pas avant.</p>
                </div>
                <div class="bg-surface border border-primary/15 rounded-2xl p-6">
                    <div class="w-10 h-10 rounded-full bg-accent-green/10 flex items-center justify-center mb-3">
                        <span class="text-lg">💧</span>
                    </div>
                    <h3 class="font-heading font-bold text-foreground">Les sauces, faites ici</h3>
                    <p class="text-foreground/55 text-sm mt-2 leading-relaxed">L'américaine, l'orientale, la blanche : elles sortent de notre cuisine, pas d'un bidon. C'est ce qui fait qu'un burger a un goût qu'on ne retrouve pas ailleurs.</p>
                </div>
                <div class="bg-surface border border-primary/15 rounded-2xl p-6">
                    <div class="w-10 h-10 rounded-full bg-accent-green/10 flex items-center justify-center mb-3">
                        <span class="text-lg">🥩</span>
                    </div>
                    <h3 class="font-heading font-bold text-foreground">La viande, jamais à l'avance</h3>
                    <p class="text-foreground/55 text-sm mt-2 leading-relaxed">Steak haché frais, saisi à la commande. Une viande cuite d'avance perd son jus en dix minutes — on ne prend pas ce raccourci.</p>
                </div>
                <div class="bg-surface border border-primary/15 rounded-2xl p-6">
                    <div class="w-10 h-10 rounded-full bg-accent-mustard/10 flex items-center justify-center mb-3">
                        <span class="text-lg">🚗</span>
                    </div>
                    <h3 class="font-heading font-bold text-foreground">Livré tant que c'est chaud</h3>
                    <p class="text-foreground/55 text-sm mt-2 leading-relaxed">Sur place, à emporter ou livré autour de Kouba et Chéraga. Le rayon est volontairement court : au-delà, ce n'est plus le même plat qui arrive.</p>
                </div>
            </div>
        </div>
    </section>

    {{-- Stats --}}
    <section class="fade-up py-12 lg:py-16 px-6 lg:px-12">
        <div class="max-w-4xl mx-auto">
            <div class="grid grid-cols-2 lg:grid-cols-4 gap-4">
                <div class="bg-surface border border-primary/15 rounded-2xl p-5 text-center">
                    <span class="text-3xl lg:text-4xl font-heading font-extrabold text-foreground block" data-counter="2">0</span>
                    <p class="text-foreground/50 text-sm mt-1">adresses à Alger</p>
                </div>
                <div class="bg-surface border border-primary/15 rounded-2xl p-5 text-center">
                    <span class="text-3xl lg:text-4xl font-heading font-extrabold text-foreground block">13h30</span>
                    <p class="text-foreground/50 text-sm mt-1">de service par jour</p>
                </div>
                <div class="bg-surface border border-primary/15 rounded-2xl p-5 text-center">
                    <span class="text-3xl lg:text-4xl font-heading font-extrabold text-foreground block">29 000+</span>
                    <p class="text-foreground/50 text-sm mt-1">abonnés sur Instagram</p>
                </div>
                <div class="bg-surface border border-primary/15 rounded-2xl p-5 text-center">
                    <span class="text-3xl lg:text-4xl font-heading font-extrabold text-foreground block" data-counter="100">0</span>
                    <p class="text-foreground/50 text-sm mt-1">% fait maison</p>
                </div>
            </div>
        </div>
    </section>

    {{-- CTA --}}
    <section class="py-12 lg:py-16 px-6 lg:px-12">
        <div class="max-w-2xl mx-auto">
            <div class="bg-surface-2 border border-primary/15 rounded-3xl p-8 lg:p-10 text-center">
                <h2 class="font-heading text-2xl lg:text-3xl font-bold text-foreground">Le reste se goûte</h2>
                <p class="text-foreground/55 mt-3">Composez votre commande en quelques minutes, sur place, à emporter ou en livraison.</p>
                <div class="flex flex-col sm:flex-row items-center justify-center gap-3 mt-6">
                    <a href="{{ route('menu') }}" class="btn-primary-warm text-sm">
                        Voir la carte
                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 8l4 4m0 0l-4 4m4-4H3"/></svg>
                    </a>
                    <a href="{{ route('contact') }}" class="btn-outline-green text-sm">Nos adresses</a>
                </div>
            </div>
        </div>
    </section>

@endsection
