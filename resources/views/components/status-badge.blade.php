@props(['status'])

@php
    $classes = [
        'active' => 'text-bg-success',
        'expired' => 'text-bg-danger',
        'cancelled' => 'text-bg-secondary',
        'pending' => 'text-bg-warning',
        'sent' => 'text-bg-success',
        'failed' => 'text-bg-danger',
        'paid' => 'text-bg-success',
        'unpaid' => 'text-bg-danger',
    ];
@endphp

<span {{ $attributes->class(['badge', $classes[$status] ?? 'text-bg-secondary']) }}>
    {{ ucfirst((string) $status) }}
</span>
