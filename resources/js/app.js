// Sport Center - Alpine.js Interactions
import Alpine from 'alpinejs';
import intersect from '@alpinejs/intersect';
import focus from '@alpinejs/focus';
import persist from '@alpinejs/persist';

Alpine.plugin(intersect);
Alpine.plugin(focus);
Alpine.plugin(persist);

// Global store for UI state
Alpine.store('ui', {
    mobileMenuOpen: false,
    scrollY: 0,
    isScrolled: false,

    init() {
        window.addEventListener('scroll', () => {
            this.scrollY = window.scrollY;
            this.isScrolled = window.scrollY > 20;
        }, { passive: true });
    },

    toggleMobileMenu() {
        this.mobileMenuOpen = !this.mobileMenuOpen;
        document.body.style.overflow = this.mobileMenuOpen ? 'hidden' : '';
    },

    closeMobileMenu() {
        this.mobileMenuOpen = false;
        document.body.style.overflow = '';
    }
});

// Navbar component
Alpine.data('navbar', () => ({
    init() {
        this.$store.ui.init();
    }
}));

// Hero parallax/mouse follow effect
Alpine.data('hero', () => ({
    mouseX: 0,
    mouseY: 0,

    handleMouseMove(e) {
        const rect = this.$el.getBoundingClientRect();
        this.mouseX = (e.clientX - rect.left) / rect.width - 0.5;
        this.mouseY = (e.clientY - rect.top) / rect.height - 0.5;
    }
}));

// Counter animation for stats
Alpine.data('counter', () => ({
    count: 0,
    target: 0,
    duration: 2000,
    start: null,

    init() {
        this.target = parseInt(this.$el.dataset.target) || 0;
        this.$watch('count', () => {
            this.$el.textContent = this.formatNumber(Math.floor(this.count));
        });
    },

    startCounting() {
        this.start = performance.now();
        this.animate();
    },

    animate() {
        const elapsed = performance.now() - this.start;
        const progress = Math.min(elapsed / this.duration, 1);
        const eased = this.easeOutCubic(progress);
        this.count = this.target * eased;

        if (progress < 1) {
            requestAnimationFrame(() => this.animate());
        } else {
            this.count = this.target;
        }
    },

    easeOutCubic(t) {
        return 1 - Math.pow(1 - t, 3);
    },

    formatNumber(n) {
        if (n >= 1000000) return (n / 1000000).toFixed(1) + 'M+';
        if (n >= 1000) return (n / 1000).toFixed(0) + 'K+';
        return n.toLocaleString();
    }
}));

// Intersection observer trigger for counter
Alpine.data('counterTrigger', () => ({
    init() {
        this.$el['x-intersect'](() => {
            this.$nextTick(() => {
                const counter = this.$el.querySelector('[x-data*="counter"]');
                if (counter && counter.__x) {
                    counter.__x.getUnobservedData().startCounting();
                }
            });
        }, { threshold: 0.5, rootMargin: '0px 0px -100px 0px' });
    }
}));

// Sport card hover effects
Alpine.data('sportCard', () => ({
    isHovered: false,
    tiltX: 0,
    tiltY: 0,

    handleMouseMove(e) {
        if (!this.isHovered) return;
        const rect = this.$el.getBoundingClientRect();
        const centerX = rect.left + rect.width / 2;
        const centerY = rect.top + rect.height / 2;
        this.tiltX = (e.clientY - centerY) / 20;
        this.tiltY = (e.clientX - centerX) / 20;
    },

    handleMouseLeave() {
        this.tiltX = 0;
        this.tiltY = 0;
    }
}));

// Form validation & enhancement
Alpine.data('authForm', () => ({
    errors: {},
    loading: false,
    showPassword: false,
    showConfirmPassword: false,

    validate(field, value) {
        this.errors[field] = '';

        switch (field) {
            case 'email':
                if (!value) this.errors[field] = 'Email wajib diisi';
                else if (!/^[^\s@]+@[^\s@]+\.[^\s@]+$/.test(value)) this.errors[field] = 'Format email tidak valid';
                break;
            case 'password':
                if (!value) this.errors[field] = 'Password wajib diisi';
                else if (value.length < 8) this.errors[field] = 'Minimal 8 karakter';
                break;
            case 'password_confirmation':
                if (!value) this.errors[field] = 'Konfirmasi password wajib diisi';
                else if (value !== this.$refs.password?.value) this.errors[field] = 'Password tidak cocok';
                break;
            case 'name':
                if (!value) this.errors[field] = 'Nama wajib diisi';
                else if (value.length < 2) this.errors[field] = 'Nama terlalu pendek';
                break;
            case 'phone':
                if (value && !/^(\+62|62|0)8[1-9][0-9]{7,9}$/.test(value.replace(/\s/g, ''))) {
                    this.errors[field] = 'Format nomor HP Indonesia tidak valid';
                }
                break;
        }
        return !this.errors[field];
    },

    validateAll() {
        let valid = true;
        Object.keys(this.$refs).forEach(key => {
            if (this.$refs[key]?.value !== undefined) {
                if (!this.validate(key, this.$refs[key].value)) valid = false;
            }
        });
        return valid;
    },

    async submit(formEl) {
        if (!this.validateAll()) return;
        this.loading = true;
        formEl.submit();
    },

    togglePassword(field) {
        this[field] = !this[field];
        this.$nextTick(() => {
            this.$refs[field]?.focus();
        });
    }
}));

// Password strength meter
Alpine.data('passwordStrength', () => ({
    strength: 0,
    label: '',

    check(value) {
        let score = 0;
        if (value.length >= 8) score++;
        if (/[A-Z]/.test(value)) score++;
        if (/[a-z]/.test(value)) score++;
        if (/[0-9]/.test(value)) score++;
        if (/[^A-Za-z0-9]/.test(value)) score++;

        this.strength = Math.min((score / 5) * 100, 100);

        const labels = ['Sangat Lemah', 'Lemah', 'Sedang', 'Kuat', 'Sangat Kuat'];
        this.label = labels[Math.floor(score / 1.25)] || labels[0];
    }
}));

// Toast notifications
Alpine.store('toast', {
    toasts: [],

    show(message, type = 'info', duration = 5000) {
        const id = Date.now();
        this.toasts.push({ id, message, type });
        setTimeout(() => this.dismiss(id), duration);
    },

    dismiss(id) {
        this.toasts = this.toasts.filter(t => t.id !== id);
    },

    success(message) { this.show(message, 'success'); },
    error(message) { this.show(message, 'error'); },
    warning(message) { this.show(message, 'warning'); },
    info(message) { this.show(message, 'info'); }
});

// Lazy image loading
Alpine.data('lazyImage', () => ({
    loaded: false,
    error: false,

    init() {
        this.$el['x-intersect'](() => {
            this.loaded = true;
        }, { rootMargin: '100px' });
    }
}));

// Smooth scroll for anchor links
document.addEventListener('click', (e) => {
    const link = e.target.closest('a[href^="#"]');
    if (link) {
        const href = link.getAttribute('href');
        if (href === '#') return;
        const target = document.querySelector(href);
        if (target) {
            e.preventDefault();
            target.scrollIntoView({ behavior: 'smooth', block: 'start' });
            history.pushState(null, '', href);
        }
    }
});

// Initialize Alpine
document.addEventListener('alpine:init', () => {
    // Global utilities
    Alpine.magic('formatCurrency', () => (amount) => {
        return new Intl.NumberFormat('id-ID', {
            style: 'currency',
            currency: 'IDR',
            minimumFractionDigits: 0,
            maximumFractionDigits: 0
        }).format(amount);
    });
});

window.Alpine = Alpine;
Alpine.start();