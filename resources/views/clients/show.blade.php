@extends('layouts.app')

@section('title', 'Client Details')
@section('page-title', $client->name)
@section('page-subtitle', $client->email ?? 'No email configured')
@section('page-actions')
    <a href="{{ route('clients.edit', $client) }}" class="btn btn-primary">Edit client</a>
@endsection

@section('content')
    <div class="row g-4">
        <div class="col-lg-4">
            <div class="card content-card">
                <div class="card-body">
                    <div class="d-flex align-items-center gap-3 mb-4">
                        <div class="avatar">{{ strtoupper(substr($client->name, 0, 1)) }}</div>
                        <div>
                            <h2 class="h5 mb-1">Contact</h2>
                            <div class="small text-secondary">Primary client details</div>
                        </div>
                    </div>
                    <div class="soft-card p-3 mb-3">
                        <div class="small text-secondary">Phone</div>
                        <div class="fw-semibold">{{ $client->phone }}</div>
                    </div>
                    <div class="soft-card p-3">
                        <div class="small text-secondary">Email</div>
                        <div class="fw-semibold">{{ $client->email ?? '-' }}</div>
                    </div>
                </div>
            </div>
        </div>
        <div class="col-lg-8">
            <div class="card content-card">
                <div class="card-header bg-transparent border-0 pt-4 px-4">
                    <div class="fw-semibold">Subscriptions</div>
                    <div class="small text-secondary">All services connected to this client.</div>
                </div>
                <div class="table-responsive">
                    <table class="table mb-0">
                        <thead><tr><th>Service</th><th>End date</th><th>Status</th><th>Payment</th></tr></thead>
                        <tbody>
                            @forelse($client->subscriptions as $subscription)
                                <tr>
                                    <td>{{ strtoupper($subscription->service_type) }}</td>
                                    <td>{{ $subscription->end_date->format('Y-m-d') }}</td>
                                    <td><x-status-badge :status="$subscription->status" /></td>
                                    <td><x-status-badge :status="$subscription->payment_status" /></td>
                                </tr>
                            @empty
                                <tr><td colspan="4"><div class="empty-state">No subscriptions.</div></td></tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>
@endsection
