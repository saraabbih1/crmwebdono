<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Requests\SubscriptionRequest;
use App\Http\Resources\SubscriptionResource;
use App\Models\Subscription;
use App\Services\ActivityLogger;
use App\Services\ReminderEmailService;
use App\Services\SettingsService;
use Carbon\Carbon;

class SubscriptionApiController extends Controller
{
    public function index()
    {
        return SubscriptionResource::collection(Subscription::with('client')->latest()->paginate(15));
    }

    public function store(SubscriptionRequest $request, ReminderEmailService $reminders, ActivityLogger $activityLogger): SubscriptionResource
    {
        $subscription = Subscription::create($this->withCalculatedDates($request->validated()));
        $reminders->syncReminderNotification($subscription);
        $activityLogger->log('subscription.created', 'Subscription was created via API.', $subscription);

        return new SubscriptionResource($subscription->load('client'));
    }

    public function show(Subscription $subscription): SubscriptionResource
    {
        return new SubscriptionResource($subscription->load('client'));
    }

    public function update(SubscriptionRequest $request, Subscription $subscription, ReminderEmailService $reminders, ActivityLogger $activityLogger): SubscriptionResource
    {
        $subscription->update($this->withCalculatedDates($request->validated()));
        $reminders->syncReminderNotification($subscription->fresh('client'));
        $activityLogger->log('subscription.updated', 'Subscription was updated via API.', $subscription);

        return new SubscriptionResource($subscription->load('client'));
    }

    public function destroy(Subscription $subscription, ActivityLogger $activityLogger)
    {
        $subscription->delete();
        $activityLogger->log('subscription.deleted', 'Subscription was deleted via API.');

        return response()->json(status: 204);
    }

    private function withCalculatedDates(array $data): array
    {
        $data['service_type'] = strtolower($data['service_type']);
        $data['end_date'] = Carbon::parse($data['start_date'])->addMonthsNoOverflow((int) $data['duration_months'])->toDateString();
        $data['reminder_date'] = Carbon::parse($data['end_date'])
            ->subDays((int) app(SettingsService::class)->get('reminder_delay_days', 5))
            ->toDateString();

        return $data;
    }
}
