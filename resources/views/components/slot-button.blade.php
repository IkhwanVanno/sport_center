@props([
    'slot' => [],
    'name' => 'slots[]',
    'selected' => false,
])

@php
    $startTime = $slot['start_time'] ?? '08:00';
    $endTime = $slot['end_time'] ?? '09:00';
    $status = $slot['status'] ?? 'available';
    $price = (float) ($slot['price_per_hour'] ?? 0);
    $isAvailable = ($status === 'available');
    $slotId = 'slot_' . str_replace(':', '', $startTime);
@endphp

<div class="position-relative slot-button-wrapper">
    <input type="checkbox" 
           class="btn-check slot-checkbox" 
           id="{{ $slotId }}" 
           name="{{ $name }}" 
           value="{{ $startTime }}" 
           data-price="{{ $price }}"
           data-time="{{ $startTime }} - {{ $endTime }}"
           autocomplete="off" 
           {{ !$isAvailable ? 'disabled' : '' }}
           {{ $selected ? 'checked' : '' }}>
    
    <label class="btn w-100 p-2 text-center rounded-3 border transition-all slot-label {{ $isAvailable ? 'btn-outline-primary border-primary-subtle' : ($status === 'pending' ? 'btn-light border-warning text-warning-emphasis disabled opacity-75' : 'btn-light border-secondary-subtle text-muted disabled opacity-50') }}" 
           for="{{ $slotId }}"
           style="cursor: {{ $isAvailable ? 'pointer' : 'not-allowed' }};">
        <div class="fw-bold fs-6">{{ $startTime }}</div>
        <div class="text-xs text-muted" style="font-size: 0.72rem;">s/d {{ $endTime }}</div>
        
        <div class="mt-1 pt-1 border-top border-light">
            @if($isAvailable)
                <span class="fw-semibold text-emerald" style="font-size: 0.75rem;">
                    Rp {{ number_format($price, 0, ',', '.') }}
                </span>
            @elseif($status === 'pending')
                <span class="badge bg-warning text-dark px-1.5 py-0.5" style="font-size: 0.65rem;">
                    Diproses
                </span>
            @else
                <span class="badge bg-secondary text-white px-1.5 py-0.5" style="font-size: 0.65rem;">
                    Terisi
                </span>
            @endif
        </div>
    </label>
</div>
