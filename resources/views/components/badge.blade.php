<!-- Badge Component -->
@props([
    'variant' => 'default', // default, brand, energy, court, success, warning, error
    'size' => 'md', // sm, md, lg
    'dot' => false,
    'icon' => null,
])

<?php
$variants = [
    'default' => 'bg-court-100 text-court-700 border border-court-200',
    'brand' => 'bg-brand-50 text-brand-700 border border-brand-200',
    'energy' => 'bg-energy-50 text-energy-700 border border-energy-200',
    'court' => 'bg-court-100 text-court-700 border border-court-200',
    'success' => 'bg-green-50 text-green-700 border border-green-200',
    'warning' => 'bg-yellow-50 text-yellow-700 border border-yellow-200',
    'error' => 'bg-red-50 text-red-700 border border-red-200',
];

$sizes = [
    'sm' => 'px-2 py-0.5 text-xs gap-1',
    'md' => 'px-3 py-1 text-sm gap-1.5',
    'lg' => 'px-4 py-1.5 text-base gap-2',
];
?>

<span class="inline-flex items-center font-medium rounded-full border {{ $variants[$variant] }} {{ $sizes[$size] }}">
    @if ($dot)
        <span class="w-1.5 h-1.5 rounded-full bg-current opacity-70"></span>
    @endif
    @if ($icon)
        <span class="flex-shrink-0">{{ $icon }}</span>
    @endif
    {{ $slot }}
</span>