@props([
    'variant' => 'success',
])

@php
    $class = $variant === 'danger' ? 'text-bg-danger' : 'text-bg-success';
@endphp

<div {{ $attributes->class("toast show crm-toast {$class}") }} role="alert">
    <div class="toast-body fw-semibold">{{ $slot }}</div>
</div>
