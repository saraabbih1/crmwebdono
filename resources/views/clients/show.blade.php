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
                    <h2 class="h5">Contact</h2>
                    <div class="text-secondary">Phone</div>
                    <div class="mb-3">{{ $client->phone }}</div>
                    <div class="text-secondary">Email</div>
                    <div>{{ $client->email ?? '-' }}</div>
                </div>
            </div>
        </div>
        <div class="col-lg-8">
            <div class="card content-card">
                <div class="card-header bg-transparent fw-semibold">Subscriptions</div>
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
                                <tr><td colspan="4" class="text-center text-secondary py-4">No subscriptions.</td></tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>
@endsection
