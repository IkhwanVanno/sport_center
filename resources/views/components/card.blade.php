<!-- Card Component -->
@props([
    'variant' => 'default', // default, elevated, outlined, gradient
    'padding' => 'lg', // none, sm, md, lg, xl
    'hover' => false,
    'border' => true,
])

<?php
$variants = [
    'default' => 'bg-white',
    'elevated' => 'bg-white shadow-medium',
    'outlined' => 'bg-white border border-court-200',
    'gradient' => 'bg-gradient-to-br from-white to-court-50',
];

$paddings = [
    'none' => '',
    'sm' => 'p-4',
    'md' => 'p-6',
    'lg' => 'p-8',
    'xl' => 'p-10',
];
?>

<div class="rounded-2xl {{ $variants[$variant] }} {{ $paddings[$padding] }}
            {{ $border && $variant !== 'outlined' ? 'border border-court-100' : '' }}
            {{ $hover ? 'card-hover-lift' : '' }}"
     {{ $attributes->merge(['class' => '']) }}>
    {{ $slot }}
</div>