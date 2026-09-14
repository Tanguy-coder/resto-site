import './bootstrap';
import Alpine from 'alpinejs';
import intersect from '@alpinejs/intersect';
import { gsap } from 'gsap';
import { ScrollTrigger } from 'gsap/ScrollTrigger';

gsap.registerPlugin(ScrollTrigger);
Alpine.plugin(intersect);
window.Alpine = Alpine;
window.gsap = gsap;
window.ScrollTrigger = ScrollTrigger;

Alpine.store('cart', {
    items: JSON.parse(localStorage.getItem('restaurant_cart') || '[]'),

    get count() {
        return this.items.reduce((sum, i) => sum + i.quantity, 0);
    },

    get total() {
        return this.items.reduce((sum, i) => sum + i.price * i.quantity, 0);
    },

    add(item) {
        const key = `${item.productId}-${item.variantId || 0}-${(item.removedIngredients || []).sort().join(',')}`;
        const existing = this.items.find(i => i.key === key);
        if (existing) {
            existing.quantity += item.quantity;
        } else {
            this.items.push({ ...item, key });
        }
        this._save();
    },

    remove(key) {
        this.items = this.items.filter(i => i.key !== key);
        this._save();
    },

    updateQty(key, qty) {
        const item = this.items.find(i => i.key === key);
        if (item) {
            if (qty <= 0) {
                this.remove(key);
            } else {
                item.quantity = qty;
                this._save();
            }
        }
    },

    clear() {
        this.items = [];
        this._save();
    },

    countFor(productId) {
        return this.items.filter(i => i.productId === productId).reduce((s, i) => s + i.quantity, 0);
    },

    _save() {
        localStorage.setItem('restaurant_cart', JSON.stringify(this.items));
    }
});

Alpine.start();

document.addEventListener('DOMContentLoaded', () => {
    // Fade-up animations on scroll
    const fadeEls = document.querySelectorAll('.fade-up');
    if (fadeEls.length) {
        fadeEls.forEach(el => {
            ScrollTrigger.create({
                trigger: el,
                start: 'top 85%',
                onEnter: () => el.classList.add('visible'),
            });
        });
    }

    // Counter animations
    document.querySelectorAll('[data-counter]').forEach(el => {
        const target = parseInt(el.dataset.counter);
        ScrollTrigger.create({
            trigger: el,
            start: 'top 85%',
            once: true,
            onEnter: () => {
                gsap.to(el, {
                    innerText: target,
                    duration: 2,
                    snap: { innerText: 1 },
                    ease: 'power2.out',
                });
            },
        });
    });

    // Hero carousel
    const heroSlides = document.querySelectorAll('.hero-slide');
    const heroTexts = document.querySelectorAll('.hero-slide-text');
    const heroDots = document.querySelectorAll('.hero-dot');
    if (heroSlides.length > 1) {
        let currentSlide = 0;
        const totalSlides = heroSlides.length;

        function goToSlide(index) {
            heroSlides[currentSlide].classList.remove('active');
            heroTexts[currentSlide]?.classList.remove('active');
            if (heroDots[currentSlide]) {
                heroDots[currentSlide].classList.remove('bg-accent-green', 'scale-110');
                heroDots[currentSlide].classList.add('bg-primary/40');
            }
            currentSlide = ((index % totalSlides) + totalSlides) % totalSlides;
            heroSlides[currentSlide].classList.add('active');
            heroTexts[currentSlide]?.classList.add('active');
            if (heroDots[currentSlide]) {
                heroDots[currentSlide].classList.add('bg-accent-green', 'scale-110');
                heroDots[currentSlide].classList.remove('bg-primary/40');
            }
        }

        setInterval(() => goToSlide(currentSlide + 1), 4000);

        document.addEventListener('hero-prev', () => goToSlide(currentSlide - 1));
        document.addEventListener('hero-next', () => goToSlide(currentSlide + 1));
    }

    // Smooth scroll for anchor links
    document.querySelectorAll('a[href^="#"]').forEach(anchor => {
        anchor.addEventListener('click', function (e) {
            const target = document.querySelector(this.getAttribute('href'));
            if (target) {
                e.preventDefault();
                target.scrollIntoView({ behavior: 'smooth', block: 'start' });
            }
        });
    });
});
