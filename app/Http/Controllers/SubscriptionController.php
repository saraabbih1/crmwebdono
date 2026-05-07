<?php

namespace App\Http\Controllers;

use App\Mail\SubscriptionReminderMail;
use App\Models\Client;
use App\Models\Notification;
use App\Models\Subscription;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Mail;
use Illuminate\Validation\Rule;

class SubscriptionController extends Controller
{
    public function index()
    {
        $subscriptions = Subscription::with('client')->latest()->get();

        return view('subscriptions.index', compact('subscriptions'));
    }

    public function create()
    {
        $clients = Client::orderBy('name')->get();

        return view('subscriptions.create', compact('clients'));
    }

    public function store(Request $request)
    {
        $validated = $this->validatedData($request);
        $validated = $this->applyCalculatedDates($validated);

        $subscription = Subscription::create($validated);

        $this->syncReminderNotification($subscription);

        return redirect()->route('subscriptions.index')->with('success', 'Subscription created successfully.');
    }

    public function edit(Subscription $subscription)
    {
        $clients = Client::orderBy('name')->get();

        return view('subscriptions.edit', compact('subscription', 'clients'));
    }

    public function update(Request $request, Subscription $subscription)
    {
        $validated = $this->validatedData($request);
        $validated = $this->applyCalculatedDates($validated);

        $subscription->update($validated);

        $this->syncReminderNotification($subscription->fresh('client'));

        return redirect()->route('subscriptions.index')->with('success', 'Subscription updated successfully.');
    }

    public function destroy(Subscription $subscription)
    {
        $subscription->delete();

        return redirect()->route('subscriptions.index')->with('success', 'Subscription deleted successfully.');
    }

    private function validatedData(Request $request): array
    {
        return $request->validate([
            'client_id' => ['required', 'exists:clients,id'],
            'service_type' => ['required', 'string', 'max:100'],
            'duration_months' => ['required', 'integer', Rule::in([1, 6, 12])],
            'start_date' => ['required', 'date'],
            'status' => ['required', 'string', 'max:50'],
            'price' => ['nullable', 'numeric', 'min:0', 'max:999999.99'],
            'payment_status' => ['required', 'string', 'max:50'],
            'message_reminder' => ['nullable', 'string'],
        ]);
    }

    private function applyCalculatedDates(array $data): array
    {
        $data['service_type'] = strtolower($data['service_type']);
        $data['end_date'] = Carbon::parse($data['start_date'])
            ->addMonthsNoOverflow((int) $data['duration_months'])
            ->toDateString();

        return $data;
    }

    private function syncReminderNotification(Subscription $subscription): void
    {
        if (! in_array($subscription->service_type, ['seo', 'suivi'], true)) {
            return;
        }

        $reminderDate = $subscription->end_date->copy()->subDays(5);
        $message = $subscription->message_reminder ?: sprintf(
            'Reminder: %s service for %s ends on %s.',
            strtoupper($subscription->service_type),
            $subscription->client->name,
            $subscription->end_date->format('Y-m-d')
        );

        $notification = Notification::updateOrCreate(
            [
                'client_id' => $subscription->client_id,
                'subscription_id' => $subscription->id,
                'type' => 'email',
            ],
            [
                'message' => $message,
                'status' => 'pending',
                'reminder_date' => $reminderDate->toDateString(),
            ]
        );

        // Mail-ready structure for a scheduler or queue job:
        // Mail::to($subscription->client->email)->queue(new SubscriptionReminderMail($notification));
    }
}
