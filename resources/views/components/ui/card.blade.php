@props([
    'title' => null,
    'subtitle' => null,
    'padding' => true,
])

<div {{ $attributes->class('content-card') }}>
    @if($title || $subtitle)
        <div class="card-header bg-transparent border-0 pt-4 px-4">
            @if($title)
                <div class="fw-semibold">{{ $title }}</div>
            @endif
            @if($subtitle)
                <div class="small text-secondary">{{ $subtitle }}</div>
            @endif
        </div>
    @endif
    <div class="{{ $padding ? 'card-body' : '' }}">
        {{ $slot }}
    </div>
</div>
