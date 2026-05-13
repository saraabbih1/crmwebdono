<?php

namespace App\Http\Controllers;

use App\Http\Requests\SubscriptionRequest;
use App\Models\Client;
use App\Models\Subscription;
use App\Services\ActivityLogger;
use App\Services\ReminderEmailService;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\StreamedResponse;

class SubscriptionController extends Controller
{
    public function index()
    {
        $subscriptions = $this->filteredSubscriptions()
            ->latest()
            ->paginate(10)
            ->withQueryString();

        return view('subscriptions.index', compact('subscriptions'));
    }

    public function create()
    {
        $clients = Client::orderBy('name')->get();

        return view('subscriptions.create', compact('clients'));
    }

    public function store(SubscriptionRequest $request, ReminderEmailService $reminderEmailService, ActivityLogger $activityLogger)
    {
        $validated = $request->validated();
        $validated = $this->applyCalculatedDates($validated);

        $subscription = Subscription::create($validated);

        $reminderEmailService->syncReminderNotification($subscription);
        $activityLogger->log('subscription.created', 'Subscription was created.', $subscription);

        return redirect()->route('subscriptions.index')->with('success', 'Subscription created successfully.');
    }

    public function edit(Subscription $subscription)
    {
        $clients = Client::orderBy('name')->get();

        return view('subscriptions.edit', compact('subscription', 'clients'));
    }

    public function update(SubscriptionRequest $request, Subscription $subscription, ReminderEmailService $reminderEmailService, ActivityLogger $activityLogger)
    {
        $validated = $request->validated();
        $validated = $this->applyCalculatedDates($validated);

        $subscription->update($validated);

        $reminderEmailService->syncReminderNotification($subscription->fresh('client'));
        $activityLogger->log('subscription.updated', 'Subscription was updated.', $subscription);

        return redirect()->route('subscriptions.index')->with('success', 'Subscription updated successfully.');
    }

    public function destroy(Subscription $subscription, ActivityLogger $activityLogger)
    {
        $clientName = $subscription->client?->name ?? 'Unknown client';
        $subscription->delete();
        $activityLogger->log('subscription.deleted', "Subscription for {$clientName} was deleted.");

        return redirect()->route('subscriptions.index')->with('success', 'Subscription deleted successfully.');
    }

    public function export(Request $request): StreamedResponse
    {
        $subscriptions = $this->filteredSubscriptions()->orderBy('end_date')->get();

        return response()->streamDownload(function () use ($subscriptions): void {
            $handle = fopen('php://output', 'w');

            fputcsv($handle, ['Client', 'Email', 'Service', 'Start date', 'End date', 'Reminder date', 'Status', 'Payment', 'Price']);

            foreach ($subscriptions as $subscription) {
                fputcsv($handle, [
                    $subscription->client->name,
                    $subscription->client->email,
                    strtoupper($subscription->service_type),
                    $subscription->start_date->format('Y-m-d'),
                    $subscription->end_date->format('Y-m-d'),
                    $subscription->reminder_date?->format('Y-m-d'),
                    $subscription->status,
                    $subscription->payment_status,
                    $subscription->price,
                ]);
            }

            fclose($handle);
        }, 'subscriptions.csv', ['Content-Type' => 'text/csv']);
    }

    private function applyCalculatedDates(array $data): array
    {
        $data['service_type'] = strtolower($data['service_type']);
        $data['end_date'] = Carbon::parse($data['start_date'])
            ->addMonthsNoOverflow((int) $data['duration_months'])
            ->toDateString();
        $delay = $data['service_type'] === 'suivi' ? 15 : 5;
        $data['reminder_date'] = Carbon::parse($data['end_date'])->subDays($delay)->toDateString();

        return $data;
    }

    private function filteredSubscriptions()
    {
        return Subscription::query()
            ->with('client')
            ->when(request('status'), fn ($query, string $status) => $query->where('status', $status))
            ->when(request('service_type'), fn ($query, string $serviceType) => $query->where('service_type', $serviceType));
    }
}
