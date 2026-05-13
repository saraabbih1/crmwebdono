<?php

namespace App\Http\Controllers;

use App\Http\Requests\NotificationRequest;
use App\Models\Client;
use App\Models\Notification;
use App\Models\Subscription;
use App\Services\ActivityLogger;

class NotificationController extends Controller
{
    public function index()
    {
        $notifications = Notification::with(['client', 'subscription'])
            ->latest()
            ->paginate(10)
            ->withQueryString();

        return view('notifications.index', compact('notifications'));
    }

    public function create()
    {
        $clients = Client::orderBy('name')->get();
        $subscriptions = Subscription::with('client')->latest()->get();

        return view('notifications.create', compact('clients', 'subscriptions'));
    }

    public function store(NotificationRequest $request, ActivityLogger $activityLogger)
    {
        $notification = Notification::create($request->validated());
        $activityLogger->log('notification.created', 'Notification was created.', $notification);

        return redirect()->route('notifications.index')->with('success', 'Notification created successfully.');
    }

    public function edit(Notification $notification)
    {
        $clients = Client::orderBy('name')->get();
        $subscriptions = Subscription::with('client')->latest()->get();

        return view('notifications.edit', compact('notification', 'clients', 'subscriptions'));
    }

    public function update(NotificationRequest $request, Notification $notification, ActivityLogger $activityLogger)
    {
        $notification->update($request->validated());
        $activityLogger->log('notification.updated', 'Notification was updated.', $notification);

        return redirect()->route('notifications.index')->with('success', 'Notification updated successfully.');
    }

    public function destroy(Notification $notification, ActivityLogger $activityLogger)
    {
        $notification->delete();
        $activityLogger->log('notification.deleted', 'Notification was deleted.');

        return redirect()->route('notifications.index')->with('success', 'Notification deleted successfully.');
    }
}
