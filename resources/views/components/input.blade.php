<!-- Input Component -->
@props([
    'type' => 'text',
    'label' => null,
    'error' => null,
    'hint' => null,
    'icon' => null,
    'iconPosition' => 'left', // left, right
    'showPasswordToggle' => false,
])

<?php
$inputId = $inputId ?? 'input-' . uniqid();
$errorId = $error ? $inputId . '-error' : null;
$hintId = $hint ? $inputId . '-hint' : null;
$describedBy = collect([$errorId, $hintId])->filter()->implode(' ');
?>

<div class="w-full">
    @if ($label)
        <label for="{{ $inputId }}" class="label-base">{{ $label }}</label>
    @endif

    <div class="relative">
        @if ($icon && $iconPosition === 'left')
            <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none text-court-400">
                {{ $icon }}
            </div>
        @endif

        <input type="{{ $type }}"
               id="{{ $inputId }}"
               class="input-base {{ $error ? 'input-error' : '' }} {{ $icon && $iconPosition === 'left' ? 'pl-11' : '' }} {{ $icon && $iconPosition === 'right' ? 'pr-11' : '' }} {{ $showPasswordToggle ? 'pr-12' : '' }}"
               {{ $describedBy ? "aria-describedby=\"{$describedBy}\"" : '' }}
               {{ $error ? "aria-invalid=\"true\"" : '' }}
               {{ $attributes->except(['type', 'label', 'error', 'hint', 'icon', 'iconPosition', 'showPasswordToggle', 'inputId']) }} />

        @if ($icon && $iconPosition === 'right')
            <div class="absolute inset-y-0 right-0 pr-3 flex items-center pointer-events-none text-court-400">
                {{ $icon }}
            </div>
        @endif

        @if ($showPasswordToggle)
            <button type="button"
                    @click="$dispatch('toggle-password', { inputId: '{{ $inputId }}' })"
                    class="absolute inset-y-0 right-0 pr-3 flex items-center text-court-400 hover:text-court-600 transition-colors"
                    aria-label="Tampilkan/sembunyikan password"
                    aria-controls="{{ $inputId }}">
                <svg x-show="$store.authForm?.showPassword === false" class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24" aria-hidden="true">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"/>
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z"/>
                </svg>
                <svg x-show="$store.authForm?.showPassword === true" class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24" aria-hidden="true">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13.875 18.825A10.05 10.05 0 0112 19c-4.478 0-8.268-2.943-9.543-7a9.97 9.97 0 011.563-3.029m5.858.908a3 3 0 114.243 4.243M9.878 9.878l4.242 4.242M9.88 9.88a3 3 0 114.242 4.242M12 19c-4.477 0-8.267-2.942-9.542-7 1.273-4.056 5.064-7 9.542-7M5.929 5.93L4.5 4.5M12 5v-.001M19.5 19.5l1.5 1.5"/>
                </svg>
            </button>
        @endif
    </div>

    @if ($error)
        <p id="{{ $errorId }}" class="mt-1.5 text-sm text-red-600 flex items-center gap-1" role="alert">
            <svg class="w-4 h-4 flex-shrink-0" fill="currentColor" viewBox="0 0 20 20" aria-hidden="true">
                <path fill-rule="evenodd" d="M18 10a8 8 0 11-16 0 8 8 0 0116 0zm-7 4a1 1 0 11-2 0 1 1 0 012 0zm-1-9a1 1 0 10-2 0v6a1 1 0 102 0V5z" clip-rule="evenodd"/>
            </svg>
            {{ $error }}
        </p>
    @endif

    @if ($hint)
        <p id="{{ $hintId }}" class="mt-1.5 text-sm text-court-500">{{ $hint }}</p>
    @endif
</div>