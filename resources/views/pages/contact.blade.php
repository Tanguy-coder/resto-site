@extends('layouts.public')

@section('title', 'NIWA FOOD - Contact')

@section('content')

    {{-- Hero — left-aligned, split-color title --}}
    <section class="pt-10 pb-6 lg:pt-14 lg:pb-8 px-6 lg:px-12">
        <div class="max-w-6xl mx-auto">
            <span class="text-accent-green text-xs font-bold tracking-widest uppercase">Où nous trouver</span>
            <h1 class="font-heading text-4xl lg:text-5xl font-bold leading-tight mt-3">
                Deux adresses,<br>
                <span class="text-accent-mustard font-normal">un seul régal</span>
            </h1>
            <p class="text-foreground/55 mt-4 max-w-lg leading-relaxed">{{ $settings['contact_subtitle'] ?? 'Kouba et Chéraga, ouvertes tous les jours. Un appel suffit pour commander.' }}</p>
            <div class="h-1 w-12 bg-accent-green rounded-full mt-5"></div>
        </div>
    </section>

    {{-- Location cards --}}
    <section class="fade-up py-10 lg:py-16 px-6 lg:px-12">
        <div class="max-w-6xl mx-auto">
            <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                @foreach($locations as $loc)
                    @php
                        $locImage = $loc->image
                            ? asset('storage/' . $loc->image)
                            : asset('images/' . strtolower(str_replace(['é', 'è'], 'e', $loc->name)) . '.png');
                    @endphp
                    <div class="bg-surface border border-primary/15 rounded-2xl overflow-hidden">
                        {{-- Photo with overlay --}}
                        <div class="relative aspect-[16/10] overflow-hidden">
                            <img src="{{ $locImage }}" alt="{{ $loc->name }}" class="w-full h-full object-cover">
                            <div class="absolute inset-0 bg-gradient-to-t from-black/60 to-transparent"></div>
                            <div class="absolute bottom-4 left-5 right-5 flex items-end justify-between">
                                <div class="flex items-center gap-2">
                                    <span class="text-amber-400 text-lg">📍</span>
                                    <span class="text-white font-heading font-bold text-xl">{{ $loc->name }}</span>
                                </div>
                                <span class="text-white/80 text-sm text-right whitespace-pre-line">{{ $loc->address }}</span>
                            </div>
                        </div>

                        {{-- Phone button --}}
                        @if($loc->phone)
                        <div class="px-5 pt-4 pb-3">
                            <a href="tel:{{ preg_replace('/\s/', '', $loc->phone) }}"
                               class="flex items-center justify-center gap-2 w-full py-3.5 bg-primary text-on-primary font-semibold rounded-xl hover:bg-primary-dark transition-colors">
                                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 5a2 2 0 012-2h3.28a1 1 0 01.948.684l1.498 4.493a1 1 0 01-.502 1.21l-2.257 1.13a11.042 11.042 0 005.516 5.516l1.13-2.257a1 1 0 011.21-.502l4.493 1.498a1 1 0 01.684.949V19a2 2 0 01-2 2h-1C9.716 21 3 14.284 3 6V5z"/></svg>
                                {{ $loc->phone }}
                            </a>
                        </div>
                        @endif

                        {{-- Hours --}}
                        @if($loc->hours)
                        <div class="px-5 pb-4">
                            <div class="border border-primary/15 rounded-xl p-4">
                                <div class="flex items-center gap-2 mb-3">
                                    <svg class="w-4 h-4 text-foreground/40" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
                                    <span class="text-xs font-bold tracking-wider uppercase text-foreground/40">Horaires</span>
                                </div>
                                <p class="text-sm text-foreground/60 whitespace-pre-line leading-relaxed">{{ $loc->hours }}</p>
                            </div>
                        </div>
                        @endif

                        {{-- Directions button --}}
                        <div class="px-5 pb-5">
                            <a href="{{ $loc->map_url ?? 'https://maps.google.com' }}" target="_blank"
                               class="flex items-center justify-center gap-2 w-full py-3 border border-primary/30 text-foreground/60 font-medium rounded-xl hover:border-primary/50 hover:text-foreground transition-all text-sm">
                                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17.657 16.657L13.414 20.9a1.998 1.998 0 01-2.827 0l-4.244-4.243a8 8 0 1111.314 0z"/><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 11a3 3 0 11-6 0 3 3 0 016 0z"/></svg>
                                Voir l'itinéraire
                            </a>
                        </div>
                    </div>
                @endforeach
            </div>
        </div>
    </section>

    {{-- Shared hours banner --}}
    <section class="fade-up py-6 px-6 lg:px-12">
        <div class="max-w-3xl mx-auto">
            <div class="bg-surface border border-primary/15 rounded-2xl p-5 flex flex-col sm:flex-row items-start sm:items-center justify-between gap-4">
                <div class="flex items-center gap-3">
                    <svg class="w-5 h-5 text-foreground/40 shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
                    <div>
                        <h3 class="font-heading font-bold text-foreground text-sm">Horaires d'ouverture</h3>
                        <p class="text-foreground/50 text-xs">Identiques sur les deux adresses.</p>
                    </div>
                </div>
                <div class="text-right space-y-0.5">
                    <div class="text-sm"><span class="text-foreground/60">Samedi à jeudi</span> <span class="text-accent-green font-bold ml-2">11h00 – 00h30</span></div>
                    <div class="text-sm"><span class="text-foreground/60">Vendredi</span> <span class="text-accent-green font-bold ml-2">18h00 – 00h30</span></div>
                </div>
            </div>
        </div>
    </section>

    {{-- How to order --}}
    <section class="fade-up py-12 lg:py-20 px-6 lg:px-12">
        <div class="max-w-6xl mx-auto">
            <span class="text-foreground/40 text-xs font-bold tracking-widest uppercase">Comment commander</span>
            <h2 class="font-heading text-2xl lg:text-3xl font-bold text-foreground mt-2">Trois façons de manger chez nous</h2>

            <div class="grid grid-cols-1 md:grid-cols-3 gap-5 mt-8">
                <div class="bg-surface border border-primary/15 rounded-2xl p-6 text-center">
                    <div class="text-3xl mb-4">🍴</div>
                    <h3 class="font-heading font-bold text-foreground mb-2">Sur place</h3>
                    <p class="text-foreground/55 text-sm leading-relaxed">Installez-vous, scannez le QR code de la table et commandez sans faire la queue.</p>
                </div>
                <div class="bg-surface border border-primary/15 rounded-2xl p-6 text-center">
                    <div class="text-3xl mb-4">🛍️</div>
                    <h3 class="font-heading font-bold text-foreground mb-2">À emporter</h3>
                    <p class="text-foreground/55 text-sm leading-relaxed">Commandez depuis le site, passez récupérer au comptoir quand c'est prêt.</p>
                </div>
                <div class="bg-surface border border-primary/15 rounded-2xl p-6 text-center">
                    <div class="text-3xl mb-4">🚗</div>
                    <h3 class="font-heading font-bold text-foreground mb-2">Livraison</h3>
                    <p class="text-foreground/55 text-sm leading-relaxed">Disponible autour de Kouba et de Chéraga. Les frais dépendent de l'adresse et vous sont confirmés avant l'envoi.</p>
                </div>
            </div>
        </div>
    </section>

    {{-- Contact CTA --}}
    <section class="py-12 lg:py-16 px-6 lg:px-12">
        <div class="max-w-2xl mx-auto">
            <div class="bg-surface-2 border border-primary/15 rounded-3xl p-8 lg:p-10 text-center">
                <h2 class="font-heading text-2xl lg:text-3xl font-bold text-foreground">Une question, une remarque ?</h2>
                <p class="text-foreground/55 mt-3">Appelez l'adresse la plus proche, ou écrivez-nous en message privé — on répond tous les jours pendant le service.</p>
                <div class="flex flex-wrap items-center justify-center gap-3 mt-6">
                    @if($settings['instagram_url'] ?? null)
                    <a href="{{ $settings['instagram_url'] }}" target="_blank" class="inline-flex items-center gap-2 px-5 py-2.5 bg-rose-500 text-white text-sm font-semibold rounded-full hover:bg-rose-600 transition-colors">
                        <svg class="w-4 h-4" fill="currentColor" viewBox="0 0 24 24"><path d="M12 2.163c3.204 0 3.584.012 4.85.07 3.252.148 4.771 1.691 4.919 4.919.058 1.265.069 1.645.069 4.849 0 3.205-.012 3.584-.069 4.849-.149 3.225-1.664 4.771-4.919 4.919-1.266.058-1.644.07-4.85.07-3.204 0-3.584-.012-4.849-.07-3.26-.149-4.771-1.699-4.919-4.92-.058-1.265-.07-1.644-.07-4.849 0-3.204.013-3.583.07-4.849.149-3.227 1.664-4.771 4.919-4.919 1.266-.057 1.645-.069 4.849-.069zM12 0C8.741 0 8.333.014 7.053.072 2.695.272.273 2.69.073 7.052.014 8.333 0 8.741 0 12c0 3.259.014 3.668.072 4.948.2 4.358 2.618 6.78 6.98 6.98C8.333 23.986 8.741 24 12 24c3.259 0 3.668-.014 4.948-.072 4.354-.2 6.782-2.618 6.979-6.98.059-1.28.073-1.689.073-4.948 0-3.259-.014-3.667-.072-4.947-.196-4.354-2.617-6.78-6.979-6.98C15.668.014 15.259 0 12 0zm0 5.838a6.162 6.162 0 100 12.324 6.162 6.162 0 000-12.324zM12 16a4 4 0 110-8 4 4 0 010 8zm6.406-11.845a1.44 1.44 0 100 2.881 1.44 1.44 0 000-2.881z"/></svg>
                        Instagram
                    </a>
                    @endif
                    @if($settings['facebook_url'] ?? null)
                    <a href="{{ $settings['facebook_url'] }}" target="_blank" class="inline-flex items-center gap-2 px-5 py-2.5 bg-blue-700 text-white text-sm font-semibold rounded-full hover:bg-blue-800 transition-colors">
                        <svg class="w-4 h-4" fill="currentColor" viewBox="0 0 24 24"><path d="M24 12.073c0-6.627-5.373-12-12-12s-12 5.373-12 12c0 5.99 4.388 10.954 10.125 11.854v-8.385H7.078v-3.47h3.047V9.43c0-3.007 1.792-4.669 4.533-4.669 1.312 0 2.686.235 2.686.235v2.953H15.83c-1.491 0-1.956.925-1.956 1.874v2.25h3.328l-.532 3.47h-2.796v8.385C19.612 23.027 24 18.062 24 12.073z"/></svg>
                        Facebook
                    </a>
                    @endif
                    @if($settings['tiktok_url'] ?? null)
                    <a href="{{ $settings['tiktok_url'] }}" target="_blank" class="inline-flex items-center gap-2 px-5 py-2.5 bg-foreground text-on-primary text-sm font-semibold rounded-full hover:bg-foreground/90 transition-colors">
                        <svg class="w-4 h-4" fill="currentColor" viewBox="0 0 24 24"><path d="M12.525.02c1.31-.02 2.61-.01 3.91-.02.08 1.53.63 3.09 1.75 4.17 1.12 1.11 2.7 1.62 4.24 1.79v4.03c-1.44-.05-2.89-.35-4.2-.97-.57-.26-1.1-.59-1.62-.93-.01 2.92.01 5.84-.02 8.75-.08 1.4-.54 2.79-1.35 3.94-1.31 1.92-3.58 3.17-5.91 3.21-1.43.08-2.86-.31-4.08-1.03-2.02-1.19-3.44-3.37-3.65-5.71-.02-.5-.03-1-.01-1.49.18-1.9 1.12-3.72 2.58-4.96 1.66-1.44 3.98-2.13 6.15-1.72.02 1.48-.04 2.96-.04 4.44-.99-.32-2.15-.23-3.02.37-.63.41-1.11 1.04-1.36 1.75-.21.51-.15 1.07-.14 1.61.24 1.64 1.82 3.02 3.5 2.87 1.12-.01 2.19-.66 2.77-1.61.19-.33.4-.67.41-1.06.1-1.79.06-3.57.07-5.36.01-4.03-.01-8.05.02-12.07z"/></svg>
                        TikTok
                    </a>
                    @endif
                </div>
                <div class="mt-5">
                    <a href="{{ route('menu') }}" class="btn-primary-warm text-sm">
                        Passer commande
                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 8l4 4m0 0l-4 4m4-4H3"/></svg>
                    </a>
                </div>
            </div>
        </div>
    </section>

@endsection
