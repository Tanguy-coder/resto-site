@extends('layouts.public')

@section('title', ($settings['site_name'] ?? 'Restaurant') . ' - Finaliser ma commande')

@section('content')

    <section class="py-8 lg:py-12 px-6 lg:px-12"
        x-data="{
            cart: JSON.parse(localStorage.getItem('restaurant_cart') || '[]'),
            restaurant: null,
            serviceType: 'sur_place',
            name: '',
            table: '',
            phone: '',
            address: '',
            note: '',
            promoCode: '',
            submitted: false,
            submitting: false,
            orderNumber: null,
            errorMsg: '',

            get total() {
                return this.cart.reduce((s, i) => s + i.price * i.quantity, 0);
            },

            get count() {
                return this.cart.reduce((s, i) => s + i.quantity, 0);
            },

            get serviceDescription() {
                switch (this.serviceType) {
                    case 'sur_place': return 'Vous êtes installé en salle, on vous apporte votre commande.';
                    case 'a_emporter': return 'Passez récupérer votre commande au comptoir quand elle est prête.';
                    case 'livraison': return 'On vous livre à l\'adresse indiquée. Les frais dépendent de la distance.';
                    default: return '';
                }
            },

            get canSubmit() {
                if (this.cart.length === 0 || this.submitting) return false;
                if (!this.restaurant) return false;
                if (!this.name.trim()) return false;
                if (this.serviceType === 'sur_place' && !this.table.trim()) return false;
                if (this.serviceType === 'livraison' && (!this.phone.trim() || !this.address.trim())) return false;
                if (this.serviceType === 'a_emporter' && !this.phone.trim()) return false;
                return true;
            },

            fmt(n) {
                return new Intl.NumberFormat('fr-FR').format(n);
            },

            async submitOrder() {
                if (!this.canSubmit) return;
                this.submitting = true;
                this.errorMsg = '';

                try {
                    const res = await fetch('{{ route('api.orders.store') }}', {
                        method: 'POST',
                        headers: {
                            'Content-Type': 'application/json',
                            'X-CSRF-TOKEN': document.querySelector('meta[name=csrf-token]').content,
                            'Accept': 'application/json',
                        },
                        body: JSON.stringify({
                            location_id: this.restaurant,
                            service_type: this.serviceType,
                            customer_name: this.name.trim(),
                            phone: this.phone.trim() || null,
                            table_number: this.table.trim() || null,
                            address: this.address.trim() || null,
                            note: this.note.trim() || null,
                            promo_code: this.promoCode.trim() || null,
                            items: this.cart.map(i => ({
                                product_id: i.productId,
                                variant_id: i.variantId || null,
                                name: i.name,
                                variant_name: i.variantName || null,
                                price: i.price,
                                quantity: i.quantity,
                                removed_ingredients: i.removedIngredients || [],
                            })),
                        }),
                    });

                    if (!res.ok) throw new Error('Erreur serveur');

                    const data = await res.json();
                    this.orderNumber = data.order_number;
                    this.submitted = true;

                    localStorage.removeItem('restaurant_cart');
                    if (window.Alpine?.store('cart')) {
                        window.Alpine.store('cart').items = [];
                        window.Alpine.store('cart')._save();
                    }
                } catch (e) {
                    this.errorMsg = 'Une erreur est survenue. Veuillez réessayer.';
                } finally {
                    this.submitting = false;
                }
            }
        }">

        <div class="max-w-2xl mx-auto">

            {{-- Empty cart redirect --}}
            <template x-if="cart.length === 0 && !submitted">
                <div class="text-center py-20">
                    <span class="text-5xl block mb-4">🛒</span>
                    <h1 class="font-heading text-2xl font-bold text-foreground mb-2">Votre panier est vide</h1>
                    <p class="text-foreground/50 mb-6">Ajoutez des articles depuis le menu pour passer commande.</p>
                    <a href="{{ route('menu') }}" class="btn-primary-warm text-sm">Voir la carte</a>
                </div>
            </template>

            {{-- Success state --}}
            <template x-if="submitted">
                <div class="text-center py-16">
                    <span class="text-5xl block mb-4">✅</span>
                    <h1 class="font-heading text-2xl font-bold text-foreground mb-2">Commande envoyée !</h1>
                    <p class="text-foreground/50 mb-6">Votre commande a été transmise en cuisine.</p>

                    {{-- Order number card --}}
                    <div class="bg-surface border border-primary/15 rounded-2xl p-6 max-w-sm mx-auto mb-8">
                        <p class="text-xs font-bold tracking-wider uppercase text-foreground/50 mb-2">Votre numéro de commande</p>
                        <span class="font-heading text-5xl font-extrabold text-accent-green block" x-text="orderNumber"></span>
                        <p class="text-sm text-foreground/50 mt-3">Conservez ce numéro pour suivre votre commande.</p>
                    </div>

                    <a href="{{ route('tracking') }}" class="btn-primary-warm text-sm inline-flex items-center gap-2">
                        Suivre ma commande
                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 7l5 5m0 0l-5 5m5-5H6"/></svg>
                    </a>

                    <div class="flex flex-col sm:flex-row items-center justify-center gap-3 mt-4">
                        <a href="{{ route('menu') }}" class="text-sm text-foreground/50 hover:text-foreground transition-colors">Nouvelle commande</a>
                        <span class="hidden sm:inline text-foreground/20">&middot;</span>
                        <a href="{{ route('home') }}" class="text-sm text-foreground/50 hover:text-foreground transition-colors">Retour à l'accueil</a>
                    </div>
                </div>
            </template>

            {{-- Checkout form --}}
            <template x-if="cart.length > 0 && !submitted">
                <div>
                    {{-- Header --}}
                    <div class="flex items-center gap-4 mb-8">
                        <a href="{{ route('menu') }}" class="w-10 h-10 rounded-full border border-primary/20 flex items-center justify-center text-foreground/50 hover:text-foreground hover:border-primary/40 transition-all shrink-0">
                            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 19l-7-7 7-7"/></svg>
                        </a>
                        <div>
                            <span class="text-accent-green text-xs font-bold tracking-widest uppercase">Dernière étape</span>
                            <h1 class="font-heading text-2xl lg:text-3xl font-bold text-foreground">Finaliser ma commande</h1>
                        </div>
                    </div>

                    {{-- Error message --}}
                    <div x-show="errorMsg" x-cloak x-transition class="mb-6 p-4 bg-red-500/10 border border-red-500/30 rounded-xl text-red-500 text-sm font-medium" x-text="errorMsg"></div>

                    {{-- Order summary --}}
                    <div class="bg-surface border border-primary/15 rounded-2xl p-5 mb-6">
                        <div class="flex items-center justify-between mb-4">
                            <div>
                                <h2 class="font-heading font-bold text-foreground">Votre commande</h2>
                                <p class="text-xs text-foreground/50" x-text="count + ' article' + (count > 1 ? 's' : '')"></p>
                            </div>
                            <a href="{{ route('menu') }}" class="text-xs text-foreground/50 hover:text-foreground flex items-center gap-1 transition-colors">
                                <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"/></svg>
                                Modifier
                            </a>
                        </div>
                        <div class="space-y-3">
                            <template x-for="item in cart" :key="item.key">
                                <div class="flex items-center gap-3 py-2 border-b border-dashed border-primary/10 last:border-0">
                                    <span class="w-6 h-6 rounded-md bg-accent-green/10 text-accent-green text-xs font-bold flex items-center justify-center shrink-0" x-text="item.quantity"></span>
                                    <div class="flex-1 min-w-0">
                                        <span class="text-sm font-bold text-foreground" x-text="item.name"></span>
                                        <span x-show="item.variantName" class="text-xs text-foreground/50 ml-1" x-text="item.variantName?.toLowerCase()"></span>
                                        <span x-show="item.removedIngredients?.length > 0" class="text-xs text-red-400 ml-1">
                                            sans <span x-text="item.removedIngredients?.join(', ')"></span>
                                        </span>
                                    </div>
                                    <span class="text-sm font-bold text-foreground shrink-0" x-text="fmt(item.price * item.quantity) + ' FCFA'"></span>
                                </div>
                            </template>
                        </div>
                        <div class="flex items-center justify-between mt-4 pt-3 border-t border-dashed border-primary/15">
                            <span class="text-xs font-bold tracking-wider uppercase text-foreground/50">Sous-total</span>
                            <span class="text-xl font-heading font-bold text-accent-green" x-text="fmt(total) + ' FCFA'"></span>
                        </div>
                    </div>

                    {{-- Promo code --}}
                    <div class="mb-8">
                        <p class="text-sm font-bold text-foreground mb-1">Code promo <span class="font-normal text-foreground/40">facultatif</span></p>
                        <div class="flex gap-3">
                            <input x-model="promoCode" type="text" placeholder="Ex : NIWA10"
                                   class="flex-1 px-4 py-3 bg-surface border border-primary/20 rounded-xl text-foreground placeholder-foreground/40 text-sm focus:outline-none focus:border-accent-green transition-colors">
                            <button type="button" class="px-5 py-3 border border-primary/20 rounded-xl text-sm text-foreground/50 hover:text-foreground hover:border-primary/40 transition-all">Appliquer</button>
                        </div>
                    </div>

                    {{-- Restaurant selection --}}
                    <div class="mb-8">
                        <p class="text-xs font-bold tracking-wider uppercase text-foreground/60 mb-3">Quel restaurant ?</p>
                        <div class="grid grid-cols-2 gap-3">
                            @foreach($locations as $location)
                                <button type="button"
                                    @click="restaurant = {{ $location->id }}"
                                    :class="restaurant === {{ $location->id }}
                                        ? 'border-accent-green bg-accent-green/5 text-foreground'
                                        : 'border-primary/20 text-foreground/60 hover:border-primary/40'"
                                    class="flex items-center justify-center gap-2 px-5 py-5 border rounded-2xl text-sm font-medium transition-all duration-200">
                                    <svg class="w-4 h-4" :class="restaurant === {{ $location->id }} ? 'text-accent-green' : 'text-foreground/40'" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17.657 16.657L13.414 20.9a1.998 1.998 0 01-2.827 0l-4.244-4.243a8 8 0 1111.314 0z"/><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 11a3 3 0 11-6 0 3 3 0 016 0z"/></svg>
                                    {{ $location->name }}
                                </button>
                            @endforeach
                        </div>
                    </div>

                    {{-- Service type --}}
                    <div class="mb-8">
                        <p class="text-xs font-bold tracking-wider uppercase text-foreground/60 mb-3">Comment souhaitez-vous être servi ?</p>
                        <div class="grid grid-cols-3 gap-3">
                            <button type="button" @click="serviceType = 'sur_place'"
                                :class="serviceType === 'sur_place'
                                    ? 'border-accent-green bg-accent-green/5 text-accent-green'
                                    : 'border-primary/20 text-foreground/60 hover:border-primary/40'"
                                class="flex flex-col items-center gap-2 px-4 py-5 border rounded-2xl text-sm font-medium transition-all duration-200">
                                <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M12 3c-1.5 0-2 1-2 2v1H8c-1 0-2 1-2 2v1h12V8c0-1-1-2-2-2h-2V5c0-1-.5-2-2-2zM6 9v2c0 4 2 7 6 9 4-2 6-5 6-9V9H6z"/></svg>
                                Sur place
                            </button>
                            <button type="button" @click="serviceType = 'a_emporter'"
                                :class="serviceType === 'a_emporter'
                                    ? 'border-accent-green bg-accent-green/5 text-accent-green'
                                    : 'border-primary/20 text-foreground/60 hover:border-primary/40'"
                                class="flex flex-col items-center gap-2 px-4 py-5 border rounded-2xl text-sm font-medium transition-all duration-200">
                                <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M16 11V7a4 4 0 00-8 0v4M5 9h14l1 12H4L5 9z"/></svg>
                                À emporter
                            </button>
                            <button type="button" @click="serviceType = 'livraison'"
                                :class="serviceType === 'livraison'
                                    ? 'border-accent-green bg-accent-green/5 text-accent-green'
                                    : 'border-primary/20 text-foreground/60 hover:border-primary/40'"
                                class="flex flex-col items-center gap-2 px-4 py-5 border rounded-2xl text-sm font-medium transition-all duration-200">
                                <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M13 16V6a1 1 0 00-1-1H4a1 1 0 00-1 1v10a1 1 0 001 1h1m8-1a1 1 0 01-1 1H9m4-1V8a1 1 0 011-1h2.586a1 1 0 01.707.293l3.414 3.414a1 1 0 01.293.707V16a1 1 0 01-1 1h-1m-6-1a1 1 0 001 1h1M5 17a2 2 0 104 0m-4 0a2 2 0 114 0m6 0a2 2 0 104 0m-4 0a2 2 0 114 0"/></svg>
                                Livraison
                            </button>
                        </div>
                        <p class="text-sm text-foreground/50 mt-3" x-text="serviceDescription"></p>
                    </div>

                    {{-- Customer info --}}
                    <div class="mb-8">
                        <p class="text-xs font-bold tracking-wider uppercase text-foreground/60 mb-4">Vos informations</p>

                        {{-- Name --}}
                        <div class="mb-4">
                            <label class="text-sm font-bold text-foreground block mb-1.5">Votre nom</label>
                            <input x-model="name" type="text" placeholder="Ex : Yacine"
                                   class="w-full px-4 py-3 bg-surface border border-primary/20 rounded-xl text-foreground placeholder-foreground/40 text-sm focus:outline-none focus:border-accent-green transition-colors">
                            <p class="text-xs text-foreground/40 mt-1">C'est le nom qui sera appelé pour votre commande.</p>
                        </div>

                        {{-- Table (sur place only) --}}
                        <div x-show="serviceType === 'sur_place'" x-transition class="mb-4">
                            <label class="text-sm font-bold text-foreground block mb-1.5">Votre table</label>
                            <template x-if="!restaurant">
                                <p class="text-sm text-foreground/40 py-3">Choisissez d'abord un restaurant.</p>
                            </template>
                            <template x-if="restaurant">
                                <input x-model="table" type="text" placeholder="Ex : 5"
                                       class="w-full px-4 py-3 bg-surface border border-primary/20 rounded-xl text-foreground placeholder-foreground/40 text-sm focus:outline-none focus:border-accent-green transition-colors">
                            </template>
                        </div>

                        {{-- Phone (à emporter + livraison) --}}
                        <div x-show="serviceType !== 'sur_place'" x-transition class="mb-4">
                            <label class="text-sm font-bold text-foreground block mb-1.5">Votre téléphone</label>
                            <input x-model="phone" type="tel" placeholder="Ex : 0552 00 00 00"
                                   class="w-full px-4 py-3 bg-surface border border-primary/20 rounded-xl text-foreground placeholder-foreground/40 text-sm focus:outline-none focus:border-accent-green transition-colors">
                        </div>

                        {{-- Address (livraison only) --}}
                        <div x-show="serviceType === 'livraison'" x-transition class="mb-4">
                            <label class="text-sm font-bold text-foreground block mb-1.5">Adresse de livraison</label>
                            <input x-model="address" type="text" placeholder="Ex : 12 rue des Frères, Kouba"
                                   class="w-full px-4 py-3 bg-surface border border-primary/20 rounded-xl text-foreground placeholder-foreground/40 text-sm focus:outline-none focus:border-accent-green transition-colors">
                        </div>

                        {{-- Note --}}
                        <div>
                            <label class="text-sm font-bold text-foreground mb-1.5 block">Une précision ? <span class="font-normal text-foreground/40">facultatif</span></label>
                            <textarea x-model="note" rows="2" placeholder="Bien cuit, sonner deux fois..."
                                      class="w-full px-4 py-3 bg-surface border border-primary/20 rounded-xl text-foreground placeholder-foreground/40 text-sm focus:outline-none focus:border-accent-green transition-colors resize-none"></textarea>
                        </div>
                    </div>

                    {{-- Divider --}}
                    <div class="h-px bg-primary/15 mb-6"></div>

                    {{-- Submit --}}
                    <button type="button" @click="submitOrder()"
                        :disabled="!canSubmit"
                        :class="canSubmit ? 'bg-accent-salmon hover:bg-accent-salmon/90 shadow-md' : 'bg-primary/30 cursor-not-allowed'"
                        class="w-full py-4 rounded-xl text-on-primary font-bold text-sm tracking-wide transition-colors flex items-center justify-center gap-2">
                        <svg x-show="submitting" x-cloak class="w-4 h-4 animate-spin" fill="none" viewBox="0 0 24 24"><circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"/><path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4z"/></svg>
                        <span x-show="!submitting">Envoyer ma commande &middot; <span x-text="fmt(total) + ' FCFA'"></span></span>
                        <span x-show="submitting" x-cloak>Envoi en cours...</span>
                    </button>
                    <p class="text-xs text-foreground/40 text-center mt-3">En envoyant, votre commande part directement en cuisine.</p>
                </div>
            </template>
        </div>
    </section>

@endsection
