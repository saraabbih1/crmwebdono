@extends('layouts.app')

@section('title', 'CRM Dashboard')
@section('page-title', 'Dashboard')
@section('page-subtitle', 'Business overview and reminder activity')

@section('content')
    <div class="row g-3 mb-4">
        <div class="col-md-6 col-xl-3">
            <div class="card content-card stat-card"><div class="card-body">
                <div class="stat-label">Total clients</div>
                <div class="stat-value mt-2">{{ $totalClients }}</div>
                <div class="small text-secondary mt-2">Managed accounts</div>
            </div></div>
        </div>
        <div class="col-md-6 col-xl-3">
            <div class="card content-card stat-card"><div class="card-body">
                <div class="stat-label">Active subscriptions</div>
                <div class="stat-value mt-2">{{ $activeSubscriptions }}</div>
                <div class="small text-secondary mt-2">Currently running</div>
            </div></div>
        </div>
        <div class="col-md-6 col-xl-3">
            <div class="card content-card stat-card"><div class="card-body">
                <div class="stat-label">Expired subscriptions</div>
                <div class="stat-value mt-2">{{ $expiredSubscriptions }}</div>
                <div class="small text-secondary mt-2">Need follow-up</div>
            </div></div>
        </div>
        <div class="col-md-6 col-xl-3">
            <div class="card content-card stat-card"><div class="card-body">
                <div class="stat-label">Pending reminders</div>
                <div class="stat-value mt-2">{{ $pendingReminders }}</div>
                <div class="small text-secondary mt-2">Awaiting scheduler</div>
            </div></div>
        </div>
    </div>

    <div class="row g-4">
        <div class="col-xl-7">
            <div class="card content-card mb-4">
                <div class="card-header bg-transparent border-0 pt-4 px-4">
                    <div class="fw-semibold">Monthly subscriptions</div>
                    <div class="small text-secondary">New subscription volume over the last 12 months.</div>
                </div>
                <div class="card-body">
                    <canvas id="subscriptionsChart" height="120"></canvas>
                </div>
            </div>
            <div class="card content-card">
                <div class="card-header bg-transparent border-0 pt-4 px-4">
                    <div class="fw-semibold">Recent notifications</div>
                    <div class="small text-secondary">Latest delivery activity from the reminder system.</div>
                </div>
                <div class="table-responsive">
                    <table class="table table-hover mb-0">
                        <thead><tr><th>Client</th><th>Message</th><th>Status</th><th>Sent</th></tr></thead>
                        <tbody>
                            @forelse($recentNotifications as $notification)
                                <tr>
                                    <td class="fw-semibold">{{ $notification->client->name }}</td>
                                    <td>{{ Str::limit($notification->message, 70) }}</td>
                                    <td><x-status-badge :status="$notification->status" /></td>
                                    <td>{{ $notification->sent_at?->format('Y-m-d H:i') ?? '-' }}</td>
                                </tr>
                            @empty
                                <tr><td colspan="4"><div class="empty-state">No notifications yet.</div></td></tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
        <div class="col-xl-5">
            <div class="card content-card mb-4">
                <div class="card-header bg-transparent border-0 pt-4 px-4">
                    <div class="fw-semibold">Service mix</div>
                    <div class="small text-secondary">Distribution across service lines.</div>
                </div>
                <div class="card-body">
                    <canvas id="serviceChart" height="160"></canvas>
                </div>
            </div>
            <div class="card content-card">
                <div class="card-header bg-transparent border-0 pt-4 px-4">
                    <div class="fw-semibold">Upcoming expirations</div>
                    <div class="small text-secondary">Renewals approaching soon.</div>
                </div>
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
                        <div class="list-group-item text-secondary py-4">No upcoming expirations.</div>
                    @endforelse
                </div>
            </div>
        </div>
    </div>

    <div class="card content-card mt-4">
        <div class="card-header bg-transparent border-0 pt-4 px-4">
            <div class="fw-semibold">Latest activity</div>
            <div class="small text-secondary">Operational audit trail for recent CRM changes.</div>
        </div>
        <div class="table-responsive">
            <table class="table table-hover mb-0">
                <thead><tr><th>Action</th><th>Description</th><th>User</th><th>Date</th></tr></thead>
                <tbody>
                    @forelse($latestActivities as $activity)
                        <tr>
                            <td><span class="badge rounded-pill bg-secondary-subtle text-secondary-emphasis border border-secondary-subtle px-3 py-2">{{ $activity->action }}</span></td>
                            <td>{{ $activity->description }}</td>
                            <td>{{ $activity->user?->name ?? 'System' }}</td>
                            <td>{{ $activity->created_at->format('Y-m-d H:i') }}</td>
                        </tr>
                    @empty
                        <tr><td colspan="4"><div class="empty-state">No activity yet.</div></td></tr>
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
