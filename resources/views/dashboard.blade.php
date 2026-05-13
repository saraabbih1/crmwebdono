@extends('layouts.app')

@section('title', 'CRM Dashboard')
@section('page-title', 'Dashboard')
@section('page-subtitle', 'Business overview and reminder activity')

@section('content')
    <div class="row g-3 mb-4">
        <div class="col-md-6 col-xl-3">
            <div class="card content-card"><div class="card-body">
                <div class="text-secondary">Total clients</div>
                <div class="display-6 fw-semibold">{{ $totalClients }}</div>
            </div></div>
        </div>
        <div class="col-md-6 col-xl-3">
            <div class="card content-card"><div class="card-body">
                <div class="text-secondary">Active subscriptions</div>
                <div class="display-6 fw-semibold">{{ $activeSubscriptions }}</div>
            </div></div>
        </div>
        <div class="col-md-6 col-xl-3">
            <div class="card content-card"><div class="card-body">
                <div class="text-secondary">Expired subscriptions</div>
                <div class="display-6 fw-semibold">{{ $expiredSubscriptions }}</div>
            </div></div>
        </div>
        <div class="col-md-6 col-xl-3">
            <div class="card content-card"><div class="card-body">
                <div class="text-secondary">Pending reminders</div>
                <div class="display-6 fw-semibold">{{ $pendingReminders }}</div>
            </div></div>
        </div>
    </div>

    <div class="row g-4">
        <div class="col-xl-7">
            <div class="card content-card mb-4">
                <div class="card-header bg-transparent fw-semibold">Monthly subscriptions</div>
                <div class="card-body">
                    <canvas id="subscriptionsChart" height="120"></canvas>
                </div>
            </div>
            <div class="card content-card">
                <div class="card-header bg-transparent fw-semibold">Recent notifications</div>
                <div class="table-responsive">
                    <table class="table table-hover mb-0">
                        <thead><tr><th>Client</th><th>Message</th><th>Status</th><th>Sent</th></tr></thead>
                        <tbody>
                            @forelse($recentNotifications as $notification)
                                <tr>
                                    <td>{{ $notification->client->name }}</td>
                                    <td>{{ Str::limit($notification->message, 70) }}</td>
                                    <td><x-status-badge :status="$notification->status" /></td>
                                    <td>{{ $notification->sent_at?->format('Y-m-d H:i') ?? '-' }}</td>
                                </tr>
                            @empty
                                <tr><td colspan="4" class="text-center text-secondary py-4">No notifications yet.</td></tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
        <div class="col-xl-5">
            <div class="card content-card mb-4">
                <div class="card-header bg-transparent fw-semibold">Service mix</div>
                <div class="card-body">
                    <canvas id="serviceChart" height="160"></canvas>
                </div>
            </div>
            <div class="card content-card">
                <div class="card-header bg-transparent fw-semibold">Upcoming expirations</div>
                <div class="list-group list-group-flush">
                    @forelse($upcomingSubscriptions as $subscription)
                        <div class="list-group-item d-flex justify-content-between gap-3">
                            <div>
                                <div class="fw-semibold">{{ $subscription->client->name }}</div>
                                <div class="small text-secondary">{{ strtoupper($subscription->service_type) }} reminder on {{ $subscription->reminder_date?->format('Y-m-d') }}</div>
                            </div>
                            <span class="text-nowrap">{{ $subscription->end_date->format('Y-m-d') }}</span>
                        </div>
                    @empty
                        <div class="list-group-item text-secondary">No upcoming expirations.</div>
                    @endforelse
                </div>
            </div>
        </div>
    </div>

    <div class="card content-card mt-4">
        <div class="card-header bg-transparent fw-semibold">Latest activity</div>
        <div class="table-responsive">
            <table class="table table-hover mb-0">
                <thead><tr><th>Action</th><th>Description</th><th>User</th><th>Date</th></tr></thead>
                <tbody>
                    @forelse($latestActivities as $activity)
                        <tr>
                            <td><span class="badge text-bg-secondary">{{ $activity->action }}</span></td>
                            <td>{{ $activity->description }}</td>
                            <td>{{ $activity->user?->name ?? 'System' }}</td>
                            <td>{{ $activity->created_at->format('Y-m-d H:i') }}</td>
                        </tr>
                    @empty
                        <tr><td colspan="4" class="text-center text-secondary py-4">No activity yet.</td></tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>
@endsection

@push('scripts')
<script>
    new Chart(document.getElementById('subscriptionsChart'), {
        type: 'line',
        data: {
            labels: @json($chartLabels),
            datasets: [{
                label: 'Subscriptions',
                data: @json($monthlySubscriptionCounts),
                borderColor: '#2563eb',
                backgroundColor: 'rgba(37, 99, 235, .12)',
                tension: .35,
                fill: true
            }]
        },
        options: { responsive: true, plugins: { legend: { display: false } } }
    });

    new Chart(document.getElementById('serviceChart'), {
        type: 'doughnut',
        data: {
            labels: @json($serviceDistribution->keys()->map(fn ($service) => strtoupper($service))->values()),
            datasets: [{
                data: @json($serviceDistribution->values()),
                backgroundColor: ['#16a34a', '#f59e0b', '#64748b']
            }]
        },
        options: { responsive: true }
    });
</script>
@endpush
