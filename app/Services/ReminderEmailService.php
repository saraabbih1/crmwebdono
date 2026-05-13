<?php

namespace App\Services;

use App\Mail\SubscriptionReminderMail;
use App\Models\Notification;
use App\Models\Subscription;
use Carbon\CarbonInterface;
use Illuminate\Support\Carbon;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Support\Facades\DB;
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

        $sentNotification = Notification::query()
            ->where('subscription_id', $subscription->id)
            ->where('type', 'subscription_reminder')
            ->where('status', 'sent')
            ->whereDate('reminder_date', $subscription->reminder_date)
            ->first();

        if ($sentNotification) {
            Log::info('CRM reminder notification skipped.', [
                'notification_id' => $sentNotification->id,
                'subscription_id' => $subscription->id,
                'client_id' => $subscription->client_id,
                'reminder_date' => $subscription->reminder_date?->toDateString(),
                'reason' => 'reminder_already_sent',
            ]);

            return $sentNotification;
        }

        $notification = Notification::firstOrCreate(
            [
                'subscription_id' => $subscription->id,
                'type' => 'subscription_reminder',
                'reminder_date' => $subscription->reminder_date,
            ],
            [
                'client_id' => $subscription->client_id,
                'message' => $message,
                'status' => 'pending',
            ]
        );

        if ($notification->wasRecentlyCreated) {
            Log::info('CRM reminder notification created.', [
                'notification_id' => $notification->id,
                'subscription_id' => $subscription->id,
                'client_id' => $subscription->client_id,
                'status' => $notification->status,
                'reminder_date' => $notification->reminder_date?->toDateString(),
            ]);
        } else {
            $notification->fill([
                'client_id' => $subscription->client_id,
                'message' => $message,
                'status' => 'pending',
            ])->save();

            Log::info('CRM reminder notification updated.', [
                'notification_id' => $notification->id,
                'subscription_id' => $subscription->id,
                'client_id' => $subscription->client_id,
                'status' => $notification->status,
                'reminder_date' => $notification->reminder_date?->toDateString(),
            ]);
        }

        return $notification;
    }

    public function sendDueReminders(?CarbonInterface $date = null, bool $syncFirst = true): int
    {
        $date ??= Carbon::today();
        $sent = 0;

        Log::info('CRM reminder scan started.', [
            'date' => $date->toDateString(),
            'due_subscriptions' => $this->countDueSubscriptions($date),
            'found_subscriptions' => $this->countFoundSubscriptions($date),
            'due_notifications' => $this->countDueNotifications($date),
            'mailer' => config('mail.default'),
            'mail_host' => config('mail.mailers.smtp.host'),
            'mail_port' => config('mail.mailers.smtp.port'),
            'mail_scheme' => config('mail.mailers.smtp.scheme'),
        ]);

        if ($syncFirst) {
            $this->syncDueReminderNotifications($date);
        }

        Log::info('CRM reminder notifications ready.', [
            'date' => $date->toDateString(),
            'due_notifications' => $this->countDueNotifications($date),
        ]);

        $this->dueNotificationsQuery($date)
            ->chunkById(100, function ($notifications) use (&$sent): void {
                foreach ($notifications as $notification) {
                    $sent += $this->sendNotification($notification) ? 1 : 0;
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

    public function countFoundSubscriptions(?CarbonInterface $date = null): int
    {
        $date ??= Carbon::today();

        return $this->foundSubscriptionsQuery($date)->count();
    }

    public function countDueNotifications(?CarbonInterface $date = null): int
    {
        $date ??= Carbon::today();

        return $this->dueNotificationsQuery($date)->count();
    }

    public function syncDueReminderNotifications(?CarbonInterface $date = null): int
    {
        $date ??= Carbon::today();
        $synced = 0;

        $this->foundSubscriptionsQuery($date)
            ->chunkById(100, function ($subscriptions) use (&$synced): void {
                foreach ($subscriptions as $subscription) {
                    Log::info('CRM reminder subscription found.', [
                        'subscription_id' => $subscription->id,
                        'client_id' => $subscription->client_id,
                        'client_email' => $subscription->client?->email,
                        'status' => $subscription->status,
                        'service_type' => $subscription->service_type,
                        'reminder_date' => $subscription->reminder_date?->toDateString(),
                    ]);

                    if (! $this->subscriptionCanSendReminder($subscription)) {
                        Log::info('CRM reminder subscription skipped.', [
                            'subscription_id' => $subscription->id,
                            'client_id' => $subscription->client_id,
                            'status' => $subscription->status,
                            'service_type' => $subscription->service_type,
                            'reason' => 'subscription_status_or_service_type_not_allowed',
                        ]);

                        continue;
                    }

                    $notification = $this->syncReminderNotification($subscription);

                    if (strtolower((string) $notification->status) === 'pending') {
                        $synced++;
                    }
                }
            });

        return $synced;
    }

    public function sendNotification(Notification $notification): bool
    {
        $notification->loadMissing(['client', 'subscription']);

        if ($notification->status === 'sent') {
            Log::info('CRM reminder email skipped.', [
                'notification_id' => $notification->id,
                'subscription_id' => $notification->subscription_id,
                'reason' => 'notification_already_sent',
            ]);

            return false;
        }

        if (! $notification->subscription) {
            $notification->update(['status' => 'failed']);

            Log::warning('CRM reminder email failed.', [
                'notification_id' => $notification->id,
                'client_id' => $notification->client_id,
                'subscription_id' => $notification->subscription_id,
                'reason' => 'missing_subscription',
            ]);

            return false;
        }

        if (! $this->subscriptionCanSendReminder($notification->subscription)) {
            Log::info('CRM reminder email skipped.', [
                'notification_id' => $notification->id,
                'client_id' => $notification->client_id,
                'subscription_id' => $notification->subscription_id,
                'subscription_status' => $notification->subscription->status,
                'service_type' => $notification->subscription->service_type,
                'reason' => 'subscription_status_or_service_type_not_allowed',
            ]);

            return false;
        }

        if (blank($notification->client?->email)) {
            $notification->update(['status' => 'failed']);

            Log::warning('CRM reminder failed because client has no email address.', [
                'notification_id' => $notification->id,
                'client_id' => $notification->client_id,
                'subscription_id' => $notification->subscription_id,
                'reason' => 'missing_client_email',
            ]);

            return false;
        }

        try {
            Log::info('CRM reminder email sending.', [
                'notification_id' => $notification->id,
                'to' => $notification->client->email,
                'subscription_id' => $notification->subscription_id,
                'type' => $notification->type,
            ]);

            Mail::to($notification->client->email)->send(new SubscriptionReminderMail($notification));

            $notification->update([
                'status' => 'sent',
                'sent_at' => now(),
            ]);

            Log::info('CRM reminder email sent.', [
                'notification_id' => $notification->id,
                'to' => $notification->client->email,
                'subscription_id' => $notification->subscription_id,
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
        return $this->foundSubscriptionsQuery($date)
            ->whereRaw('LOWER(status) = ?', ['active'])
            ->whereIn(DB::raw('LOWER(service_type)'), ['seo', 'suivi']);
    }

    private function foundSubscriptionsQuery(CarbonInterface $date): Builder
    {
        return Subscription::query()
            ->with('client')
            ->whereNotNull('reminder_date')
            ->whereDate('reminder_date', '<=', $date->toDateString());
    }

    private function dueNotificationsQuery(CarbonInterface $date): Builder
    {
        return Notification::query()
            ->with(['client', 'subscription'])
            ->whereRaw('LOWER(status) = ?', ['pending'])
            ->whereIn(DB::raw('LOWER(type)'), ['email', 'subscription_reminder'])
            ->where(function (Builder $query) use ($date): void {
                $query->whereNull('reminder_date')
                    ->orWhereDate('reminder_date', '<=', $date->toDateString());
            });
    }

    private function subscriptionCanSendReminder(Subscription $subscription): bool
    {
        return strtolower((string) $subscription->status) === 'active'
            && in_array(strtolower((string) $subscription->service_type), ['seo', 'suivi'], true);
    }
}
