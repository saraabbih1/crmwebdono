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
                            <td class="fw-semibold">{{ $notification->client->name }}</td>
                            <td>{{ strtoupper($notification->subscription->service_type) }}</td>
                            <td>{{ \Illuminate\Support\Str::limit($notification->message, 60) }}</td>
                            <td>{{ str_replace('_', ' ', ucfirst($notification->type)) }}</td>
                            <td><x-status-badge :status="$notification->status" /></td>
                            <td>{{ $notification->reminder_date?->format('Y-m-d') ?? '-' }}</td>
                            <td>{{ $notification->sent_at?->format('Y-m-d H:i') ?? '-' }}</td>
                            <td class="text-end">
                                <a href="{{ route('notifications.edit', $notification) }}" class="btn btn-sm btn-outline-primary">Edit</a>
                                <form action="{{ route('notifications.destroy', $notification) }}" method="POST" class="d-inline" onsubmit="return confirm('Delete this notification?')">
                                    @csrf
                                    @method('DELETE')
                                    <button class="btn btn-sm btn-outline-danger">Delete</button>
                                </form>
                            </td>
                        </tr>
                    @empty
                        <tr><td colspan="8" class="text-center text-secondary py-4">No notifications found.</td></tr>
                    @endforelse
                </tbody>
            </table>
        </div>
        <div class="card-footer bg-transparent">{{ $notifications->links() }}</div>
    </div>
@endsection
