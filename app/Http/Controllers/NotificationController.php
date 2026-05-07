<?php

namespace App\Http\Controllers;

use App\Models\Client;
use App\Models\Notification;
use App\Models\Subscription;
use Illuminate\Http\Request;

class NotificationController extends Controller
{
    public function index()
    {
        $notifications = Notification::with(['client', 'subscription'])->latest()->get();

        return view('notifications.index', compact('notifications'));
    }

    public function create()
    {
        $clients = Client::orderBy('name')->get();
        $subscriptions = Subscription::with('client')->latest()->get();

        return view('notifications.create', compact('clients', 'subscriptions'));
    }

    public function store(Request $request)
    {
        Notification::create($this->validatedData($request));

        return redirect()->route('notifications.index')->with('success', 'Notification created successfully.');
    }

    public function edit(Notification $notification)
    {
        $clients = Client::orderBy('name')->get();
        $subscriptions = Subscription::with('client')->latest()->get();

        return view('notifications.edit', compact('notification', 'clients', 'subscriptions'));
    }

    public function update(Request $request, Notification $notification)
    {
        $notification->update($this->validatedData($request));

        return redirect()->route('notifications.index')->with('success', 'Notification updated successfully.');
    }

    public function destroy(Notification $notification)
    {
        $notification->delete();

        return redirect()->route('notifications.index')->with('success', 'Notification deleted successfully.');
    }

    private function validatedData(Request $request): array
    {
        return $request->validate([
            'client_id' => ['required', 'exists:clients,id'],
            'subscription_id' => ['required', 'exists:subscriptions,id'],
            'message' => ['required', 'string'],
            'type' => ['required', 'string', 'max:50'],
            'status' => ['required', 'string', 'max:50'],
            'reminder_date' => ['nullable', 'date'],
            'sent_at' => ['nullable', 'date'],
        ]);
    }
}
