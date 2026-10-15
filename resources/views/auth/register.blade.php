<x-layouts.auth title="Daftar — Sport Center">
    @push('head')
        <title>Daftar — Sport Center</title>
    @endpush

    <div x-data="authForm">
        {{-- Heading --}}
        <div class="mb-8">
            <h2 class="font-display text-3xl font-bold tracking-tight text-court-950">Buat akun gratis</h2>
            <p class="mt-2 text-court-600">
                Sudah punya akun?
                <a href="{{ Route::has('login') ? route('login') : '#' }}" class="link-base">Masuk di sini</a>
            </p>
        </div>

        <form method="POST" action="{{ Route::has('register') ? route('register') : '#' }}" class="space-y-5" @submit.prevent="submit($el)">
            @csrf

            {{-- Name --}}
            <div>
                <label for="name" class="label-base">Nama lengkap</label>
                <div class="relative">
                    <span class="pointer-events-none absolute inset-y-0 left-0 flex items-center pl-3.5 text-court-400">
                        <svg class="h-5 w-5" fill="none" stroke="currentColor" viewBox="0 0 24 24" aria-hidden="true">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"/>
                        </svg>
                    </span>
                    <input type="text" name="name" id="name" x-ref="name"
                           value="{{ old('name') }}" required autofocus autocomplete="name"
                           @blur="validate('name', $el.value)" @input="validate('name', $el.value)"
                           :class="errors.name ? 'input-error' : ''"
                           class="input-base pl-11" placeholder="Nama kamu">
                </div>
                <p x-show="errors.name" x-text="errors.name" class="mt-1.5 text-sm text-red-600" role="alert"></p>
                @error('name')
                    <p class="mt-1.5 text-sm text-red-600" role="alert">{{ $message }}</p>
                @enderror
            </div>

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
                           value="{{ old('email') }}" required autocomplete="username"
                           @blur="validate('email', $el.value)" @input="validate('email', $el.value)"
                           :class="errors.email ? 'input-error' : ''"
                           class="input-base pl-11" placeholder="nama@email.com">
                </div>
                <p x-show="errors.email" x-text="errors.email" class="mt-1.5 text-sm text-red-600" role="alert"></p>
                @error('email')
                    <p class="mt-1.5 text-sm text-red-600" role="alert">{{ $message }}</p>
                @enderror
            </div>

            {{-- Phone --}}
            <div>
                <label for="phone_number" class="label-base">Nomor WhatsApp</label>
                <div class="relative">
                    <span class="pointer-events-none absolute inset-y-0 left-0 flex items-center pl-3.5 text-court-400">
                        <svg class="h-5 w-5" fill="none" stroke="currentColor" viewBox="0 0 24 24" aria-hidden="true">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 5a2 2 0 012-2h3.28a1 1 0 01.948.684l1.498 4.493a1 1 0 01-.502 1.21l-2.257 1.13a11.042 11.042 0 005.516 5.516l1.13-2.257a1 1 0 011.21-.502l4.493 1.498a1 1 0 01.684.949V19a2 2 0 01-2 2h-1C9.716 21 3 14.284 3 6V5z"/>
                        </svg>
                    </span>
                    <input type="tel" name="phone_number" id="phone_number" x-ref="phone"
                           value="{{ old('phone_number') }}" autocomplete="tel"
                           @blur="validate('phone', $el.value)" @input="validate('phone', $el.value)"
                           :class="errors.phone ? 'input-error' : ''"
                           class="input-base pl-11" placeholder="0812 3456 7890">
                </div>
                <p x-show="errors.phone" x-text="errors.phone" class="mt-1.5 text-sm text-red-600" role="alert"></p>
                <p x-show="!errors.phone" class="mt-1.5 text-sm text-court-400">Untuk konfirmasi booking & notifikasi</p>
                @error('phone_number')
                    <p class="mt-1.5 text-sm text-red-600" role="alert">{{ $message }}</p>
                @enderror
            </div>

            {{-- Password with strength --}}
            <div x-data="passwordStrength">
                <label for="password" class="label-base">Password</label>
                <div class="relative">
                    <span class="pointer-events-none absolute inset-y-0 left-0 flex items-center pl-3.5 text-court-400">
                        <svg class="h-5 w-5" fill="none" stroke="currentColor" viewBox="0 0 24 24" aria-hidden="true">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 15v2m-6 4h12a2 2 0 002-2v-6a2 2 0 00-2-2H6a2 2 0 00-2 2v6a2 2 0 002 2zm10-10V7a4 4 0 00-8 0v4h8z"/>
                        </svg>
                    </span>
                    <input :type="showPassword ? 'text' : 'password'" name="password" id="password" x-ref="password"
                           required autocomplete="new-password"
                           @input="validate('password', $el.value); check($el.value)"
                           :class="errors.password ? 'input-error' : ''"
                           class="input-base pl-11 pr-12" placeholder="Minimal 8 karakter">
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

                {{-- strength bar --}}
                <div class="mt-2.5 flex items-center gap-2" x-show="strength > 0" x-cloak>
                    <div class="h-1.5 flex-1 overflow-hidden rounded-full bg-court-100">
                        <div class="h-full rounded-full transition-all duration-300"
                             :class="strength < 40 ? 'bg-red-500' : (strength < 70 ? 'bg-energy-500' : 'bg-brand-500')"
                             :style="`width: ${strength}%`"></div>
                    </div>
                    <span class="w-24 text-right text-xs font-medium"
                          :class="strength < 40 ? 'text-red-600' : (strength < 70 ? 'text-energy-600' : 'text-brand-600')"
                          x-text="label"></span>
                </div>

                <p x-show="errors.password" x-text="errors.password" class="mt-1.5 text-sm text-red-600" role="alert"></p>
                @error('password')
                    <p class="mt-1.5 text-sm text-red-600" role="alert">{{ $message }}</p>
                @enderror
            </div>

            {{-- Confirm Password --}}
            <div>
                <label for="password_confirmation" class="label-base">Konfirmasi password</label>
                <div class="relative">
                    <span class="pointer-events-none absolute inset-y-0 left-0 flex items-center pl-3.5 text-court-400">
                        <svg class="h-5 w-5" fill="none" stroke="currentColor" viewBox="0 0 24 24" aria-hidden="true">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m5.618-4.016A11.955 11.955 0 0112 2.944a11.955 11.955 0 01-8.618 3.04A12.02 12.02 0 003 9c0 5.591 3.824 10.29 9 11.622 5.176-1.332 9-6.03 9-11.622 0-1.042-.133-2.052-.382-3.016z"/>
                        </svg>
                    </span>
                    <input :type="showConfirmPassword ? 'text' : 'password'" name="password_confirmation" id="password_confirmation"
                           x-ref="password_confirmation" required autocomplete="new-password"
                           @input="validate('password_confirmation', $el.value)"
                           :class="errors.password_confirmation ? 'input-error' : ''"
                           class="input-base pl-11 pr-12" placeholder="Ulangi password">
                    <button type="button" @click="showConfirmPassword = !showConfirmPassword"
                            class="absolute inset-y-0 right-0 flex items-center pr-3.5 text-court-400 transition-colors hover:text-court-600"
                            :aria-label="showConfirmPassword ? 'Sembunyikan password' : 'Tampilkan password'">
                        <svg x-show="!showConfirmPassword" class="h-5 w-5" fill="none" stroke="currentColor" viewBox="0 0 24 24" aria-hidden="true">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"/>
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z"/>
                        </svg>
                        <svg x-show="showConfirmPassword" x-cloak class="h-5 w-5" fill="none" stroke="currentColor" viewBox="0 0 24 24" aria-hidden="true">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13.875 18.825A10.05 10.05 0 0112 19c-4.478 0-8.268-2.943-9.543-7a9.97 9.97 0 011.563-3.029m5.858.908a3 3 0 114.243 4.243M9.878 9.878l4.242 4.242M9.88 9.88l-3.29-3.29m7.532 7.532l3.29 3.29M3 3l3.59 3.59m0 0A9.953 9.953 0 0112 5c4.478 0 8.268 2.943 9.543 7a10.025 10.025 0 01-4.132 5.411m0 0L21 21"/>
                        </svg>
                    </button>
                </div>
                <p x-show="errors.password_confirmation" x-text="errors.password_confirmation" class="mt-1.5 text-sm text-red-600" role="alert"></p>
            </div>

            {{-- Terms --}}
            <div>
                <div class="flex items-start gap-2.5">
                    <input type="checkbox" name="terms" id="terms" required
                           class="mt-0.5 h-4 w-4 rounded border-court-300 text-brand-600 focus:ring-brand-500/40">
                    <label for="terms" class="text-sm text-court-600">
                        Saya setuju dengan
                        <a href="#" class="link-base">Syarat & Ketentuan</a>
                        serta
                        <a href="#" class="link-base">Kebijakan Privasi</a> Sport Center.
                    </label>
                </div>
                @error('terms')
                    <p class="mt-1.5 text-sm text-red-600" role="alert">{{ $message }}</p>
                @enderror
            </div>

            {{-- Submit --}}
            <button type="submit" :disabled="loading"
                    class="btn-primary w-full py-3.5"
                    :class="loading ? 'cursor-not-allowed opacity-70' : ''">
                <svg x-show="loading" x-cloak class="h-5 w-5 animate-spin" viewBox="0 0 24 24" fill="none" aria-hidden="true">
                    <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle>
                    <path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4z"></path>
                </svg>
                <span x-show="!loading">Daftar Sekarang</span>
                <span x-show="loading" x-cloak>Membuat akun...</span>
            </button>
        </form>
    </div>
</x-layouts.auth>