@extends('layouts.public')

@section('title', ($settings['site_name'] ?? 'Restaurant') . ' - Suivi de commande')

@section('content')

    <section class="py-12 lg:py-16 px-6 lg:px-8"
        x-data="{
            restaurant: '',
            code: '',
            order: null,
            searching: false,
            notFound: false,

            async search() {
                if (!this.code || !this.restaurant) return;
                this.searching = true;
                this.notFound = false;
                this.order = null;

                try {
                    const res = await fetch('{{ route('api.orders.track') }}', {
                        method: 'POST',
                        headers: {
                            'Content-Type': 'application/json',
                            'X-CSRF-TOKEN': document.querySelector('meta[name=csrf-token]').content,
                            'Accept': 'application/json',
                        },
                        body: JSON.stringify({
                            order_number: this.code.trim(),
                            location_id: this.restaurant,
                        }),
                    });

                    if (res.status === 404) {
                        this.notFound = true;
                    } else {
                        this.order = await res.json();
                    }
                } catch (e) {
                    this.notFound = true;
                } finally {
                    this.searching = false;
                }
            },

            fmt(n) { return new Intl.NumberFormat('fr-FR').format(n); }
        }">
        <div class="max-w-2xl mx-auto">

            {{-- Header --}}
            <div class="text-center mb-8">
                <span class="text-accent-green text-xs font-bold tracking-widest uppercase">Suivi de commande</span>
                <h1 class="font-heading text-3xl lg:text-4xl font-bold text-foreground mt-3">Où en est ma commande ?</h1>
                <p class="text-foreground/55 mt-3">Entrez le numéro affiché au moment de votre commande.</p>
            </div>

            {{-- Form card --}}
            <div class="bg-surface border border-primary/15 rounded-2xl p-6 lg:p-8">
                {{-- Restaurant selector --}}
                <div class="mb-6">
                    <label class="block text-xs font-bold tracking-wider uppercase text-foreground/50 mb-3">Quel restaurant ?</label>
                    <div class="grid grid-cols-2 gap-3">
                        @foreach($locations as $location)
                            <button @click="restaurant = {{ $location->id }}"
                                    :class="restaurant === {{ $location->id }} ? 'border-accent-green bg-accent-green/5 text-foreground' : 'border-primary/20 text-foreground/60 hover:border-primary/40'"
                                    class="flex items-center justify-center gap-2 py-3.5 rounded-xl border text-sm font-medium transition-all">
                                <span class="text-accent-green">📍</span> {{ $location->name }}
                            </button>
                        @endforeach
                    </div>
                </div>

                {{-- Order number --}}
                <div class="mb-6">
                    <label class="block text-sm font-bold text-foreground mb-2">Numéro de commande</label>
                    <input x-model="code" type="text" placeholder="Ex : 1234"
                           @keydown.enter="search()"
                           class="w-full px-5 py-3.5 bg-surface-2 border border-primary/20 rounded-xl text-foreground placeholder-foreground/40 text-center text-lg focus:outline-none focus:border-accent-green transition-colors">
                    <p class="text-xs text-foreground/40 mt-2">C'est le numéro affiché après validation de votre commande.</p>
                </div>

                {{-- Submit --}}
                <button @click="search()"
                        :disabled="!code || !restaurant || searching"
                        class="btn-primary-warm w-full !py-3.5 text-base"
                        :class="{ 'opacity-50 cursor-not-allowed': !code || !restaurant || searching }">
                    <span x-show="!searching">Voir ma commande</span>
                    <span x-show="searching" x-cloak>Recherche en cours...</span>
                    <svg x-show="!searching" class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 7l5 5m0 0l-5 5m5-5H6"/></svg>
                </button>
            </div>

            {{-- Not found state --}}
            <div x-show="notFound" x-cloak x-transition class="mt-6">
                <div class="bg-surface border border-primary/15 rounded-2xl p-6 text-center">
                    <span class="text-4xl block mb-3">🔍</span>
                    <h3 class="font-heading font-bold text-lg text-foreground">Commande introuvable</h3>
                    <p class="text-foreground/55 text-sm mt-2">Vérifiez votre numéro ou contactez-nous directement.</p>
                    <div class="flex flex-col sm:flex-row items-center justify-center gap-4 mt-4">
                        <a href="tel:0552520076" class="text-accent-green font-semibold text-sm hover:underline">📞 Kouba — 0552 52 00 76</a>
                        <a href="tel:0549189727" class="text-accent-green font-semibold text-sm hover:underline">📞 Chéraga — 0549 18 97 27</a>
                    </div>
                </div>
            </div>

            {{-- Order found --}}
            <div x-show="order" x-cloak x-transition class="mt-6 space-y-4">

                {{-- Status stepper --}}
                <div class="bg-surface border border-primary/15 rounded-2xl p-6">
                    <div class="flex items-center justify-between mb-5">
                        <div>
                            <h3 class="font-heading font-bold text-lg text-foreground">Commande #<span x-text="order?.order_number"></span></h3>
                            <p class="text-xs text-foreground/50 mt-0.5"><span x-text="order?.customer_name"></span> &middot; <span x-text="order?.location"></span> &middot; <span x-text="order?.service_label"></span></p>
                        </div>
                        <span class="text-xs text-foreground/40" x-text="order?.created_at"></span>
                    </div>

                    {{-- Cancelled --}}
                    <template x-if="order?.cancelled">
                        <div class="text-center py-6">
                            <span class="text-4xl block mb-2">❌</span>
                            <p class="font-bold text-red-500">Commande annulée</p>
                            <p class="text-sm text-foreground/50 mt-1">Contactez le restaurant pour plus d'informations.</p>
                        </div>
                    </template>

                    {{-- Steps --}}
                    <template x-if="!order?.cancelled">
                        <div class="space-y-0">
                            <template x-for="(step, idx) in order?.steps" :key="step.key">
                                <div class="flex items-start gap-4">
                                    {{-- Vertical line + dot --}}
                                    <div class="flex flex-col items-center">
                                        <div class="w-10 h-10 rounded-full flex items-center justify-center text-lg shrink-0 transition-all duration-300"
                                             :class="step.done ? 'bg-accent-green/15' : 'bg-surface-2'"
                                             x-text="step.icon"></div>
                                        <div x-show="idx < order.steps.length - 1"
                                             class="w-0.5 h-8 transition-colors duration-300"
                                             :class="step.done && order.steps[idx+1]?.done ? 'bg-accent-green/40' : 'bg-primary/15'"></div>
                                    </div>
                                    {{-- Label --}}
                                    <div class="pt-2">
                                        <p class="text-sm font-bold transition-colors duration-300"
                                           :class="step.current ? 'text-accent-green' : step.done ? 'text-foreground' : 'text-foreground/30'"
                                           x-text="step.label"></p>
                                        <p x-show="step.current" class="text-xs text-accent-green/70 mt-0.5">En cours</p>
                                    </div>
                                </div>
                            </template>
                        </div>
                    </template>
                </div>

                {{-- Order items --}}
                <div class="bg-surface border border-primary/15 rounded-2xl p-6">
                    <h4 class="text-xs font-bold tracking-wider uppercase text-foreground/50 mb-4">Détail de la commande</h4>
                    <div class="space-y-2">
                        <template x-for="item in order?.items" :key="item.name + (item.variant_name || '')">
                            <div class="flex items-center justify-between py-2 border-b border-dashed border-primary/10 last:border-0">
                                <div class="flex items-center gap-2">
                                    <span class="w-6 h-6 rounded-md bg-accent-green/10 text-accent-green text-xs font-bold flex items-center justify-center shrink-0" x-text="item.quantity"></span>
                                    <div>
                                        <span class="text-sm font-bold text-foreground" x-text="item.name"></span>
                                        <span x-show="item.variant_name" class="text-xs text-foreground/50 ml-1" x-text="item.variant_name?.toLowerCase()"></span>
                                        <span x-show="item.removed_ingredients?.length > 0" class="text-xs text-red-400 ml-1">
                                            sans <span x-text="item.removed_ingredients?.join(', ')"></span>
                                        </span>
                                    </div>
                                </div>
                                <span class="text-sm font-bold text-foreground" x-text="fmt(item.price * item.quantity) + ' FCFA'"></span>
                            </div>
                        </template>
                    </div>
                    <div class="flex items-center justify-between mt-4 pt-3 border-t border-primary/15">
                        <span class="text-xs font-bold tracking-wider uppercase text-foreground/50">Total</span>
                        <span class="text-xl font-heading font-bold text-accent-green" x-text="fmt(order?.total) + ' FCFA'"></span>
                    </div>
                </div>
            </div>

            {{-- Footer text --}}
            <p class="text-center text-foreground/40 text-sm mt-8">
                <a href="tel:0552520076" class="underline hover:text-accent-green transition-colors">Un souci ? Appelez-nous</a>
            </p>
        </div>
    </section>

@endsection
