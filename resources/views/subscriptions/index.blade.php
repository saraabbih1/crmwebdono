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
    <div class="card content-card mb-4">
        <div class="card-body">
            <form method="GET" class="row g-3 align-items-end">
                <div class="col-md-5">
                    <label class="form-label">Status</label>
                    <select name="status" class="form-select">
                        <option value="">All statuses</option>
                        @foreach(['active', 'expired', 'cancelled'] as $status)
                            <option value="{{ $status }}" @selected(request('status') === $status)>{{ ucfirst($status) }}</option>
                        @endforeach
                    </select>
                </div>
                <div class="col-md-5">
                    <label class="form-label">Service</label>
                    <select name="service_type" class="form-select">
                        <option value="">All services</option>
                        @foreach(['seo' => 'SEO', 'suivi' => 'Suivi'] as $value => $label)
                            <option value="{{ $value }}" @selected(request('service_type') === $value)>{{ $label }}</option>
                        @endforeach
                    </select>
                </div>
                <div class="col-md-2 d-grid">
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
                            <td>
                                <div class="fw-semibold">{{ $subscription->client->name }}</div>
                                <div class="small text-secondary">{{ $subscription->client->email ?? 'No email' }}</div>
                            </td>
                            <td><span class="badge rounded-pill bg-primary-subtle text-primary-emphasis border border-primary-subtle px-3 py-2">{{ strtoupper($subscription->service_type) }}</span></td>
                            <td>
                                <div>{{ $subscription->start_date->format('Y-m-d') }}</div>
                                <div class="small text-secondary">to {{ $subscription->end_date->format('Y-m-d') }}</div>
                            </td>
                            <td>
                                <div>{{ $subscription->reminder_date?->format('Y-m-d') ?? '-' }}</div>
                                <div class="small text-secondary">auto email</div>
                            </td>
                            <td><x-status-badge :status="$subscription->status" /></td>
                            <td><x-status-badge :status="$subscription->payment_status" /></td>
                            <td>{{ $subscription->price ? number_format((float) $subscription->price, 2) : '-' }}</td>
                            <td class="text-end">
                                <div class="action-group">
                                    <a href="{{ route('subscriptions.edit', $subscription) }}" class="btn btn-sm btn-outline-primary">Edit</a>
                                    <form action="{{ route('subscriptions.destroy', $subscription) }}" method="POST" onsubmit="return confirm('Delete this subscription?')">
                                        @csrf
                                        @method('DELETE')
                                        <button class="btn btn-sm btn-outline-danger">Delete</button>
                                    </form>
                                </div>
                            </td>
                        </tr>
                    @empty
                        <tr><td colspan="8"><div class="empty-state">No subscriptions found.</div></td></tr>
                    @endforelse
                </tbody>
            </table>
        </div>
        <div class="card-footer bg-transparent">{{ $subscriptions->links() }}</div>
    </div>
@endsection
