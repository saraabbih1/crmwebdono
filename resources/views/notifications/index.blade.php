@extends('layouts.app')

@section('title', 'Notifications')
@section('page-title', 'Notifications')
@section('page-subtitle', 'Email reminders and delivery status')
@section('page-actions')
    <a href="{{ route('notifications.create') }}" class="btn btn-primary">Add notification</a>
@endsection

@section('content')
    <div class="card content-card">
        <div class="table-responsive">
            <table class="table table-hover mb-0">
                <thead><tr><th>Client</th><th>Subscription</th><th>Message</th><th>Type</th><th>Status</th><th>Reminder</th><th>Sent</th><th class="text-end">Actions</th></tr></thead>
                <tbody>
                    @forelse($notifications as $notification)
                        <tr>
                            <td>
                                <div class="fw-semibold">{{ $notification->client->name }}</div>
                                <div class="small text-secondary">{{ $notification->client->email ?? 'No email' }}</div>
                            </td>
                            <td><span class="badge rounded-pill bg-primary-subtle text-primary-emphasis border border-primary-subtle px-3 py-2">{{ strtoupper($notification->subscription->service_type) }}</span></td>
                            <td>
                                <div>{{ \Illuminate\Support\Str::limit($notification->message, 70) }}</div>
                                <div class="small text-secondary">Notification #{{ $notification->id }}</div>
                            </td>
                            <td>{{ str_replace('_', ' ', ucfirst($notification->type)) }}</td>
                            <td><x-status-badge :status="$notification->status" /></td>
                            <td>{{ $notification->reminder_date?->format('Y-m-d') ?? '-' }}</td>
                            <td>{{ $notification->sent_at?->format('Y-m-d H:i') ?? '-' }}</td>
                            <td class="text-end">
                                <div class="action-group">
                                    <a href="{{ route('notifications.edit', $notification) }}" class="btn btn-sm btn-outline-primary">Edit</a>
                                    <form action="{{ route('notifications.destroy', $notification) }}" method="POST" onsubmit="return confirm('Delete this notification?')">
                                        @csrf
                                        @method('DELETE')
                                        <button class="btn btn-sm btn-outline-danger">Delete</button>
                                    </form>
                                </div>
                            </td>
                        </tr>
                    @empty
                        <tr><td colspan="8"><div class="empty-state">No notifications found.</div></td></tr>
                    @endforelse
                </tbody>
            </table>
        </div>
        <div class="card-footer bg-transparent">{{ $notifications->links() }}</div>
    </div>
@endsection
