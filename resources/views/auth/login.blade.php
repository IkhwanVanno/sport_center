<x-layouts.auth title="Masuk — Sport Center">
    @push('head')
        <title>Masuk — Sport Center</title>
    @endpush

    <div x-data="authForm">
        {{-- Heading --}}
        <div class="mb-8">
            <h2 class="font-display text-3xl font-bold tracking-tight text-court-950">Selamat datang kembali</h2>
            <p class="mt-2 text-court-600">
                Belum punya akun?
                <a href="{{ Route::has('register') ? route('register') : '#' }}" class="link-base">Daftar gratis</a>
            </p>
        </div>

        {{-- Session status --}}
        @if (session('status'))
            <div class="mb-6 flex items-start gap-3 rounded-xl border border-brand-200 bg-brand-50 p-4 text-sm text-brand-800" role="status">
                <svg class="mt-0.5 h-5 w-5 flex-shrink-0 text-brand-600" fill="none" stroke="currentColor" viewBox="0 0 24 24" aria-hidden="true">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"/>
                </svg>
                {{ session('status') }}
            </div>
        @endif

        <form method="POST" action="{{ Route::has('login') ? route('login') : '#' }}" class="space-y-5" @submit.prevent="submit($el)">
            @csrf

            {{-- Email --}}
            <div>
                <label for="email" class="label-base">Email</label>
                <div class="relative">
                    <span class="pointer-events-none absolute inset-y-0 left-0 flex items-center pl-3.5 text-court-400">
                        <svg class="h-5 w-5" fill="none" stroke="currentColor" viewBox="0 0 24 24" aria-hidden="true">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 8l7.89 5.26a2 2 0 002.22 0L21 8M5 19h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z"/>
                        </svg>
                    </span>
                    <input type="email" name="email" id="email" x-ref="email"
                           value="{{ old('email') }}" required autofocus autocomplete="username"
                           @blur="validate('email', $el.value)" @input="validate('email', $el.value)"
                           :class="errors.email ? 'input-error' : ''"
                           class="input-base pl-11" placeholder="nama@email.com">
                </div>
                <p x-show="errors.email" x-text="errors.email" class="mt-1.5 text-sm text-red-600" role="alert"></p>
                @error('email')
                    <p class="mt-1.5 text-sm text-red-600" role="alert">{{ $message }}</p>
                @enderror
            </div>

            {{-- Password --}}
            <div>
                <div class="mb-2 flex items-center justify-between">
                    <label for="password" class="label-base mb-0">Password</label>
                    @if (Route::has('password.request'))
                        <a href="{{ route('password.request') }}" class="text-sm font-medium text-brand-600 hover:text-brand-700">Lupa password?</a>
                    @endif
                </div>
                <div class="relative">
                    <span class="pointer-events-none absolute inset-y-0 left-0 flex items-center pl-3.5 text-court-400">
                        <svg class="h-5 w-5" fill="none" stroke="currentColor" viewBox="0 0 24 24" aria-hidden="true">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 15v2m-6 4h12a2 2 0 002-2v-6a2 2 0 00-2-2H6a2 2 0 00-2 2v6a2 2 0 002 2zm10-10V7a4 4 0 00-8 0v4h8z"/>
                        </svg>
                    </span>
                    <input :type="showPassword ? 'text' : 'password'" name="password" id="password" x-ref="password"
                           required autocomplete="current-password" @input="validate('password', $el.value)"
                           :class="errors.password ? 'input-error' : ''"
                           class="input-base pl-11 pr-12" placeholder="••••••••">
                    <button type="button" @click="showPassword = !showPassword"
                            class="absolute inset-y-0 right-0 flex items-center pr-3.5 text-court-400 transition-colors hover:text-court-600"
                            :aria-label="showPassword ? 'Sembunyikan password' : 'Tampilkan password'">
                        <svg x-show="!showPassword" class="h-5 w-5" fill="none" stroke="currentColor" viewBox="0 0 24 24" aria-hidden="true">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"/>
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z"/>
                        </svg>
                        <svg x-show="showPassword" x-cloak class="h-5 w-5" fill="none" stroke="currentColor" viewBox="0 0 24 24" aria-hidden="true">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13.875 18.825A10.05 10.05 0 0112 19c-4.478 0-8.268-2.943-9.543-7a9.97 9.97 0 011.563-3.029m5.858.908a3 3 0 114.243 4.243M9.878 9.878l4.242 4.242M9.88 9.88l-3.29-3.29m7.532 7.532l3.29 3.29M3 3l3.59 3.59m0 0A9.953 9.953 0 0112 5c4.478 0 8.268 2.943 9.543 7a10.025 10.025 0 01-4.132 5.411m0 0L21 21"/>
                        </svg>
                    </button>
                </div>
                <p x-show="errors.password" x-text="errors.password" class="mt-1.5 text-sm text-red-600" role="alert"></p>
                @error('password')
                    <p class="mt-1.5 text-sm text-red-600" role="alert">{{ $message }}</p>
                @enderror
            </div>

            {{-- Remember --}}
            <div class="flex items-center">
                <input type="checkbox" name="remember" id="remember"
                       class="h-4 w-4 rounded border-court-300 text-brand-600 focus:ring-brand-500/40">
                <label for="remember" class="ml-2.5 text-sm text-court-600">Ingat saya di perangkat ini</label>
            </div>

            {{-- Submit --}}
            <button type="submit" :disabled="loading"
                    class="btn-primary w-full py-3.5"
                    :class="loading ? 'cursor-not-allowed opacity-70' : ''">
                <svg x-show="loading" x-cloak class="h-5 w-5 animate-spin" viewBox="0 0 24 24" fill="none" aria-hidden="true">
                    <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle>
                    <path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4z"></path>
                </svg>
                <span x-show="!loading">Masuk</span>
                <span x-show="loading" x-cloak>Memproses...</span>
            </button>
        </form>

        {{-- Divider --}}
        <div class="my-7 flex items-center gap-4">
            <span class="h-px flex-1 bg-court-200"></span>
            <span class="text-sm text-court-400">atau</span>
            <span class="h-px flex-1 bg-court-200"></span>
        </div>

        {{-- Social (placeholder) --}}
        <div class="grid grid-cols-2 gap-3">
            <button type="button" class="btn-secondary justify-center py-3" disabled title="Segera hadir">
                <svg class="h-5 w-5" viewBox="0 0 24 24" aria-hidden="true">
                    <path fill="#4285F4" d="M22.56 12.25c0-.78-.07-1.53-.2-2.25H12v4.26h5.92a5.06 5.06 0 01-2.2 3.32v2.77h3.57c2.08-1.92 3.28-4.74 3.28-8.1z"/>
                    <path fill="#34A853" d="M12 23c2.97 0 5.46-.98 7.28-2.66l-3.57-2.77c-.98.66-2.23 1.06-3.71 1.06-2.86 0-5.29-1.93-6.16-4.53H2.18v2.84A11 11 0 0012 23z"/>
                    <path fill="#FBBC05" d="M5.84 14.1a6.6 6.6 0 010-4.2V7.06H2.18a11 11 0 000 9.88l3.66-2.84z"/>
                    <path fill="#EA4335" d="M12 5.38c1.62 0 3.06.56 4.21 1.64l3.15-3.15C17.45 2.09 14.97 1 12 1a11 11 0 00-9.82 6.06l3.66 2.84C6.71 7.31 9.14 5.38 12 5.38z"/>
                </svg>
                Google
            </button>
            <button type="button" class="btn-secondary justify-center py-3" disabled title="Segera hadir">
                <svg class="h-5 w-5" fill="currentColor" viewBox="0 0 24 24" aria-hidden="true">
                    <path d="M12 .5C5.65.5.5 5.65.5 12c0 5.08 3.29 9.39 7.86 10.91.58.11.79-.25.79-.55v-1.95c-3.2.7-3.87-1.54-3.87-1.54-.52-1.33-1.28-1.68-1.28-1.68-1.05-.72.08-.7.08-.7 1.16.08 1.77 1.19 1.77 1.19 1.03 1.76 2.7 1.25 3.36.96.1-.75.4-1.26.73-1.55-2.55-.29-5.24-1.28-5.24-5.68 0-1.25.45-2.28 1.19-3.08-.12-.29-.52-1.46.11-3.05 0 0 .97-.31 3.18 1.18a11 11 0 015.8 0c2.2-1.49 3.17-1.18 3.17-1.18.63 1.59.23 2.76.12 3.05.74.8 1.18 1.83 1.18 3.08 0 4.41-2.69 5.38-5.25 5.67.41.36.78 1.06.78 2.14v3.17c0 .3.21.66.8.55A11.5 11.5 0 0023.5 12C23.5 5.65 18.35.5 12 .5z"/>
                </svg>
                GitHub
            </button>
        </div>
        <p class="mt-3 text-center text-xs text-court-400">Login sosial akan tersedia di versi berikutnya</p>
    </div>
</x-layouts.auth>