@props(['status'])

@php
    $status = strtolower((string) $status);
    $classes = [
        'active' => 'bg-success-subtle text-success-emphasis border border-success-subtle',
        'expired' => 'bg-danger-subtle text-danger-emphasis border border-danger-subtle',
        'cancelled' => 'bg-secondary-subtle text-secondary-emphasis border border-secondary-subtle',
        'pending' => 'bg-warning-subtle text-warning-emphasis border border-warning-subtle',
        'sent' => 'bg-success-subtle text-success-emphasis border border-success-subtle',
        'failed' => 'bg-danger-subtle text-danger-emphasis border border-danger-subtle',
        'paid' => 'bg-success-subtle text-success-emphasis border border-success-subtle',
        'unpaid' => 'bg-danger-subtle text-danger-emphasis border border-danger-subtle',
    ];
@endphp

<span {{ $attributes->class(['badge rounded-pill px-3 py-2 fw-semibold', $classes[$status] ?? 'bg-secondary-subtle text-secondary-emphasis border border-secondary-subtle']) }}>
    {{ ucfirst($status) }}
</span>
