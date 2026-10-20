@props([
    'id' => 'customModal',
    'title' => 'Modal Title',
    'size' => '', // '', 'modal-sm', 'modal-lg', 'modal-xl'
    'centered' => true,
])

<div class="modal fade" id="{{ $id }}" tabindex="-1" aria-labelledby="{{ $id }}Label" aria-hidden="true" {{ $attributes }}>
    <div class="modal-dialog {{ $size }} {{ $centered ? 'modal-dialog-centered' : '' }}">
        <div class="modal-content border-0 shadow-lg rounded-4">
            <div class="modal-header border-bottom border-light px-4 py-3">
                <h5 class="modal-title fw-bold text-dark fs-6" id="{{ $id }}Label">{{ $title }}</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Tutup"></button>
            </div>
            <div class="modal-body px-4 py-3">
                {{ $slot }}
            </div>
            @if (isset($footer))
                <div class="modal-footer border-top border-light px-4 py-3 bg-light rounded-bottom-4">
                    {{ $footer }}
                </div>
            @endif
        </div>
    </div>
</div>
