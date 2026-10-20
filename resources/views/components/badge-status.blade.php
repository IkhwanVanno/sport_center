@props([
    'status' => 'pending',
    'type' => 'booking', // 'booking' or 'payment'
])

@php
    $normalized = strtolower(trim((string) $status));
    
    $labels = [
        // Booking
        'pending' => 'Menunggu Pembayaran',
        'confirmed' => 'Dikonfirmasi',
        'in_use' => 'Sedang Bermain',
        'completed' => 'Selesai',
        'cancelled' => 'Dibatalkan',
        'expired' => 'Kedaluwarsa',
        // Payment
        'unpaid' => 'Belum Lunas',
        'pending_verification' => 'Perlu Verifikasi',
        'paid' => 'Lunas',
        'refunded' => 'Dikembalikan',
        'failed' => 'Gagal',
        'success' => 'Sukses',
    ];

    $classes = [
        'pending' => 'bg-warning text-dark',
        'confirmed' => 'bg-success text-white',
        'in_use' => 'bg-primary text-white',
        'completed' => 'bg-secondary text-white',
        'cancelled' => 'bg-danger text-white',
        'expired' => 'bg-dark text-white',

        'unpaid' => 'bg-danger-subtle text-danger-emphasis border border-danger-subtle',
        'pending_verification' => 'bg-warning-subtle text-warning-emphasis border border-warning-subtle',
        'paid' => 'bg-success-subtle text-success-emphasis border border-success-subtle',
        'refunded' => 'bg-info-subtle text-info-emphasis border border-info-subtle',
        'failed' => 'bg-danger text-white',
        'success' => 'bg-success text-white',
    ];

    $label = $labels[$normalized] ?? strtoupper($status);
    $badgeClass = $classes[$normalized] ?? 'bg-secondary text-white';
@endphp

<span class="badge {{ $badgeClass }} badge-status fw-semibold" {{ $attributes }}>
    {{ $label }}
</span>
