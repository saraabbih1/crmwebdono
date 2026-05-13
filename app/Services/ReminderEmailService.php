<?php

namespace App\Services;

use App\Mail\SubscriptionReminderMail;
use App\Models\Notification;
use App\Models\Subscription;
use Carbon\CarbonInterface;
use Illuminate\Support\Carbon;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Mail;
use Throwable;

class ReminderEmailService
{
    public function syncReminderNotification(Subscription $subscription): Notification
    {
        $subscription->loadMissing('client');

        $message = $subscription->message_reminder ?: sprintf(
            'Reminder: your %s subscription ends on %s.',
            strtoupper($subscription->service_type),
            $subscription->end_date->format('Y-m-d')
        );

        $notification = Notification::query()
            ->where('subscription_id', $subscription->id)
            ->whereIn('type', ['subscription_reminder', 'email'])
            ->firstOrNew([
                'subscription_id' => $subscription->id,
                'type' => 'subscription_reminder',
            ]);

        if ($notification->status === 'sent') {
            return $notification;
        }

        $notification->fill([
            'client_id' => $subscription->client_id,
            'message' => $message,
            'type' => 'subscription_reminder',
            'status' => 'pending',
            'reminder_date' => $subscription->reminder_date,
        ])->save();

        return $notification;
    }

    public function sendDueReminders(?CarbonInterface $date = null): int
    {
        $date ??= Carbon::today();
        $sent = 0;

        Log::info('CRM reminder scan started.', [
            'date' => $date->toDateString(),
            'due_subscriptions' => $this->countDueSubscriptions($date),
            'mailer' => config('mail.default'),
            'mail_host' => config('mail.mailers.smtp.host'),
            'mail_port' => config('mail.mailers.smtp.port'),
            'mail_scheme' => config('mail.mailers.smtp.scheme'),
        ]);

        $this->dueSubscriptionsQuery($date)
            ->chunkById(100, function ($subscriptions) use (&$sent): void {
                foreach ($subscriptions as $subscription) {
                    $notification = $this->syncReminderNotification($subscription);

                    if ($this->sendNotification($notification)) {
                        $sent++;
                    }
                }
            });

        Log::info('CRM reminder scan finished.', [
            'sent' => $sent,
        ]);

        return $sent;
    }

    public function countDueSubscriptions(?CarbonInterface $date = null): int
    {
        $date ??= Carbon::today();

        return $this->dueSubscriptionsQuery($date)->count();
    }

    public function sendNotification(Notification $notification): bool
    {
        $notification->loadMissing(['client', 'subscription']);

        if ($notification->status === 'sent') {
            Log::info('CRM reminder skipped because notification was already sent.', [
                'notification_id' => $notification->id,
                'subscription_id' => $notification->subscription_id,
            ]);

            return false;
        }

        if (blank($notification->client?->email)) {
            $notification->update(['status' => 'failed']);

            Log::warning('CRM reminder failed because client has no email address.', [
                'notification_id' => $notification->id,
                'client_id' => $notification->client_id,
                'subscription_id' => $notification->subscription_id,
            ]);

            return false;
        }

        try {
            Log::info('CRM reminder email sending.', [
                'notification_id' => $notification->id,
                'to' => $notification->client->email,
                'subscription_id' => $notification->subscription_id,
            ]);

            Mail::to($notification->client->email)->send(new SubscriptionReminderMail($notification));

            $notification->update([
                'status' => 'sent',
                'sent_at' => now(),
            ]);

            Log::info('CRM reminder email sent.', [
                'notification_id' => $notification->id,
                'to' => $notification->client->email,
                'sent_at' => $notification->sent_at?->toDateTimeString(),
            ]);

            app(ActivityLogger::class)->log(
                'reminder.sent',
                "Reminder email sent to {$notification->client->email}.",
                $notification,
                ['subscription_id' => $notification->subscription_id]
            );

            return true;
        } catch (Throwable $exception) {
            $notification->update(['status' => 'failed']);

            Log::error('CRM reminder email failed.', [
                'notification_id' => $notification->id,
                'to' => $notification->client->email,
                'subscription_id' => $notification->subscription_id,
                'error' => $exception->getMessage(),
                'exception' => $exception::class,
            ]);

            return false;
        }
    }

    private function dueSubscriptionsQuery(CarbonInterface $date): Builder
    {
        return Subscription::query()
            ->with('client')
            ->whereDate('reminder_date', $date->toDateString())
            ->where('status', 'active')
            ->whereIn('service_type', ['seo', 'suivi']);
    }
}
