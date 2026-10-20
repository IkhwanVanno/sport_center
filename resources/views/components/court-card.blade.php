@props(['court'])

@php
    $sportSlug = strtolower($court->sport->slug ?? 'sport');
    $imageMap = [
        'futsal' => 'https://images.unsplash.com/photo-1574629810360-7efbbe195018?auto=format&fit=crop&w=600&q=80',
        'badminton' => 'https://images.unsplash.com/photo-1626224583764-f87db24ac4ea?auto=format&fit=crop&w=600&q=80',
        'basket' => 'https://images.unsplash.com/photo-1546519638-68e109498ffc?auto=format&fit=crop&w=600&q=80',
    ];
    $imageUrl = $court->photo_url ?? ($imageMap[$sportSlug] ?? 'https://images.unsplash.com/photo-1534438327276-14e5300c3a48?auto=format&fit=crop&w=600&q=80');

    $badgeColor = match ($sportSlug) {
        'futsal' => 'bg-emerald text-white',
        'badminton' => 'bg-primary text-white',
        'basket' => 'bg-warning text-dark',
        default => 'bg-secondary text-white'
    };
@endphp

<div class="card card-custom card-custom-hover h-100 overflow-hidden border-0 shadow-sm">
    <div class="position-relative" style="height: 200px; background-color: #cbd5e1;">
        <img src="{{ $imageUrl }}" 
             alt="{{ $court->name }}" 
             class="w-100 h-100 object-fit-cover">
        
        <span class="position-absolute top-0 end-0 m-3 badge {{ $badgeColor }} px-3 py-2 rounded-pill fw-bold text-uppercase fs-7 shadow-sm">
            {{ $court->sport->name ?? 'Olahraga' }}
        </span>

        @if(!$court->is_active)
            <div class="position-absolute top-0 start-0 w-100 h-100 bg-dark bg-opacity-50 d-flex align-items-center justify-content-center text-white fw-bold">
                <span class="badge bg-danger px-3 py-2">Tidak Aktif</span>
            </div>
        @endif
    </div>

    <div class="card-body p-4 d-flex flex-column justify-content-between">
        <div>
            <div class="d-flex align-items-center text-muted small mb-1">
                <i class="bi bi-geo-alt-fill text-danger me-1"></i>
                <span>{{ $court->branch->name ?? 'Cabang Sport Center' }}</span>
            </div>
            
            <h5 class="card-title fw-bold text-dark mb-2 line-clamp-1">
                {{ $court->name }}
            </h5>

            @if($court->facilities && $court->facilities->isNotEmpty())
                <div class="d-flex flex-wrap gap-1 mb-3">
                    @foreach($court->facilities->take(3) as $facility)
                        <span class="badge bg-light text-secondary border small px-2 py-1">
                            <i class="bi bi-check2 text-emerald me-1"></i>{{ $facility->name }}
                        </span>
                    @endforeach
                </div>
            @endif
        </div>

        <div class="pt-3 border-top d-flex justify-content-between align-items-center mt-3">
            <div>
                <span class="text-muted text-uppercase fw-semibold" style="font-size: 0.65rem; display: block;">Mulai dari</span>
                <div class="d-flex align-items-baseline gap-1">
                    <span class="fs-5 fw-extrabold text-emerald fw-bold">Rp {{ number_format($court->base_price_per_hour, 0, ',', '.') }}</span>
                    <span class="text-muted small">/jam</span>
                </div>
            </div>

            <a href="{{ route('courts.show', $court->slug) }}" class="btn btn-indigo btn-sm px-3 py-2 rounded-3 shadow-sm d-flex align-items-center gap-1">
                <span>Pesan Slot</span>
                <i class="bi bi-arrow-right"></i>
            </a>
        </div>
    </div>
</div>
