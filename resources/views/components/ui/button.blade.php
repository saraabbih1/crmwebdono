@props([
    'variant' => 'primary',
    'href' => null,
    'type' => 'button',
])

@php
    $classes = [
        'primary' => 'btn btn-primary',
        'secondary' => 'btn btn-outline-secondary',
        'danger' => 'btn btn-outline-danger',
        'ghost' => 'btn btn-ghost',
    ][$variant] ?? 'btn btn-primary';
@endphp

@if($href)
    <a href="{{ $href }}" {{ $attributes->class($classes) }}>{{ $slot }}</a>
@else
    <button type="{{ $type }}" {{ $attributes->class($classes) }}>{{ $slot }}</button>
@endif
