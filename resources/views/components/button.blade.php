<!-- Button Component -->
@props([
    'type' => 'button',
    'variant' => 'primary', // primary, secondary, accent, ghost
    'size' => 'md', // sm, md, lg
    'disabled' => false,
    'loading' => false,
    'fullWidth' => false,
    'icon' => null,
    'iconPosition' => 'left', // left, right
])

<?php
$variants = [
    'primary' => 'btn-primary',
    'secondary' => 'btn-secondary',
    'accent' => 'btn-accent',
    'ghost' => 'btn-ghost',
];

$sizes = [
    'sm' => 'px-4 py-2 text-sm gap-1.5',
    'md' => 'px-6 py-3 text-base gap-2',
    'lg' => 'px-8 py-4 text-lg gap-2.5',
];
?>

<button type="{{ $type }}"
        class="inline-flex items-center justify-center {{ $variants[$variant] }} {{ $sizes[$size] }} {{ $fullWidth ? 'w-full' : '' }}"
        {{ $disabled || $loading ? 'disabled' : '' }}
        {{ $attributes->except(['type', 'variant', 'size', 'disabled', 'loading', 'fullWidth', 'icon', 'iconPosition']) }}>
    @if ($loading)
        <svg class="animate-spin h-5 w-5" viewBox="0 0 24 24" fill="none" aria-hidden="true">
            <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle>
            <path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"></path>
        </svg>
        <span>Memproses...</span>
    @else
        @if ($icon && $iconPosition === 'left')
            <span class="flex-shrink-0">{{ $icon }}</span>
        @endif
        <span>{{ $slot }}</span>
        @if ($icon && $iconPosition === 'right')
            <span class="flex-shrink-0">{{ $icon }}</span>
        @endif
    @endif
</button>