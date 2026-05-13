@extends('layouts.app')

@section('title', 'Subscriptions')
@section('page-title', 'Subscriptions')
@section('page-subtitle', 'Track services, payments, and renewal reminders')
@section('page-actions')
    <div class="d-flex gap-2">
        <a href="{{ route('subscriptions.export', request()->query()) }}" class="btn btn-outline-secondary">Export CSV</a>
        <a href="{{ route('subscriptions.create') }}" class="btn btn-primary">Add subscription</a>
    </div>
@endsection

@section('content')
    <div class="card content-card mb-3">
        <div class="card-body">
            <form method="GET" class="row g-2 align-items-end">
                <div class="col-md-4">
                    <label class="form-label">Status</label>
                    <select name="status" class="form-select">
                        <option value="">All statuses</option>
                        @foreach(['active', 'expired', 'cancelled'] as $status)
                            <option value="{{ $status }}" @selected(request('status') === $status)>{{ ucfirst($status) }}</option>
                        @endforeach
                    </select>
                </div>
                <div class="col-md-4">
                    <label class="form-label">Service</label>
                    <select name="service_type" class="form-select">
                        <option value="">All services</option>
                        @foreach(['seo' => 'SEO', 'suivi' => 'Suivi'] as $value => $label)
                            <option value="{{ $value }}" @selected(request('service_type') === $value)>{{ $label }}</option>
                        @endforeach
                    </select>
                </div>
                <div class="col-md-4 d-grid">
                    <button class="btn btn-outline-primary">Apply filters</button>
                </div>
            </form>
        </div>
    </div>

    <div class="card content-card">
        <div class="table-responsive">
            <table class="table table-hover mb-0">
                <thead>
                    <tr>
                        <th>Client</th><th>Service</th><th>Period</th><th>Reminder</th><th>Status</th><th>Payment</th><th>Price</th><th class="text-end">Actions</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($subscriptions as $subscription)
                        <tr>
                            <td class="fw-semibold">{{ $subscription->client->name }}</td>
                            <td>{{ strtoupper($subscription->service_type) }}</td>
                            <td>{{ $subscription->start_date->format('Y-m-d') }} to {{ $subscription->end_date->format('Y-m-d') }}</td>
                            <td>{{ $subscription->reminder_date?->format('Y-m-d') ?? '-' }}</td>
                            <td><x-status-badge :status="$subscription->status" /></td>
                            <td><x-status-badge :status="$subscription->payment_status" /></td>
                            <td>{{ $subscription->price ? number_format((float) $subscription->price, 2) : '-' }}</td>
                            <td class="text-end">
                                <a href="{{ route('subscriptions.edit', $subscription) }}" class="btn btn-sm btn-outline-primary">Edit</a>
                                <form action="{{ route('subscriptions.destroy', $subscription) }}" method="POST" class="d-inline" onsubmit="return confirm('Delete this subscription?')">
                                    @csrf
                                    @method('DELETE')
                                    <button class="btn btn-sm btn-outline-danger">Delete</button>
                                </form>
                            </td>
                        </tr>
                    @empty
                        <tr><td colspan="8" class="text-center text-secondary py-4">No subscriptions found.</td></tr>
                    @endforelse
                </tbody>
            </table>
        </div>
        <div class="card-footer bg-transparent">{{ $subscriptions->links() }}</div>
    </div>
@endsection
