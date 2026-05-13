<?php

namespace Tests\Feature;

use App\Models\Client;
use App\Models\Notification;
use App\Models\Subscription;
use App\Models\User;
use App\Mail\SubscriptionReminderMail;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Mail;
use Tests\TestCase;

class SubscriptionTest extends TestCase
{
    use RefreshDatabase;

    public function test_subscription_creation_calculates_dates_and_notification(): void
    {
        $user = User::factory()->admin()->create();
        $client = Client::create([
            'name' => 'Acme',
            'phone' => '0600000000',
            'email' => 'client@example.com',
        ]);

        $this->actingAs($user)
            ->post(route('subscriptions.store'), [
                'client_id' => $client->id,
                'service_type' => 'seo',
                'duration_months' => 1,
                'start_date' => '2026-05-01',
                'status' => 'active',
                'price' => 100,
                'payment_status' => 'paid',
                'message_reminder' => 'Renew soon',
            ])
            ->assertRedirect(route('subscriptions.index'));

        $this->assertDatabaseHas('subscriptions', [
            'client_id' => $client->id,
            'end_date' => '2026-06-01 00:00:00',
            'reminder_date' => '2026-05-27 00:00:00',
        ]);

        $this->assertDatabaseHas('notifications', [
            'client_id' => $client->id,
            'type' => 'subscription_reminder',
            'status' => 'pending',
            'reminder_date' => '2026-05-27 00:00:00',
        ]);
    }

    public function test_suivi_subscription_reminder_is_calculated_fifteen_days_before_end_date(): void
    {
        $user = User::factory()->admin()->create();
        $client = Client::create([
            'name' => 'Acme',
            'phone' => '0600000000',
            'email' => 'client@example.com',
        ]);

        $this->actingAs($user)
            ->post(route('subscriptions.store'), [
                'client_id' => $client->id,
                'service_type' => 'suivi',
                'duration_months' => 1,
                'start_date' => '2026-05-01',
                'status' => 'active',
                'price' => 100,
                'payment_status' => 'paid',
                'message_reminder' => 'Renew soon',
            ])
            ->assertRedirect(route('subscriptions.index'));

        $this->assertDatabaseHas('subscriptions', [
            'client_id' => $client->id,
            'service_type' => 'suivi',
            'end_date' => '2026-06-01 00:00:00',
            'reminder_date' => '2026-05-17 00:00:00',
        ]);

        $this->assertDatabaseHas('notifications', [
            'client_id' => $client->id,
            'type' => 'subscription_reminder',
            'status' => 'pending',
            'reminder_date' => '2026-05-17 00:00:00',
        ]);
    }

    public function test_scheduler_sends_due_manual_notification_to_client(): void
    {
        Mail::fake();

        $user = User::factory()->admin()->create();
        $client = Client::create([
            'name' => 'Acme',
            'phone' => '0600000000',
            'email' => 'client@example.com',
        ]);
        $subscription = Subscription::create([
            'client_id' => $client->id,
            'service_type' => 'seo',
            'duration_months' => 1,
            'start_date' => '2026-05-01',
            'end_date' => '2026-06-01',
            'reminder_date' => '2026-05-27',
            'status' => 'active',
            'price' => 100,
            'payment_status' => 'paid',
            'message_reminder' => 'Renew soon',
        ]);

        $this->actingAs($user)
            ->post(route('notifications.store'), [
                'client_id' => $client->id,
                'subscription_id' => $subscription->id,
                'message' => 'Custom message',
                'type' => 'email',
                'status' => 'pending',
                'reminder_date' => '2026-05-27',
                'sent_at' => null,
            ])
            ->assertRedirect(route('notifications.index'));

        $notification = Notification::firstOrFail();

        Mail::assertNothingSent();
        $this->assertTrue($notification->fresh()->status === 'pending');

        $this->artisan('crm:send-subscription-reminders', ['--date' => '2026-05-27'])
            ->assertExitCode(0);

        Mail::assertSent(SubscriptionReminderMail::class, function (SubscriptionReminderMail $mail) use ($client, $notification) {
            return $mail->hasTo($client->email)
                && $mail->notification->is($notification);
        });

        $this->assertTrue($notification->fresh()->status === 'sent');
    }

    public function test_scheduler_sends_overdue_active_subscription_reminder_case_insensitively(): void
    {
        Mail::fake();

        $client = Client::create([
            'name' => 'Acme',
            'phone' => '0600000000',
            'email' => 'client@example.com',
        ]);

        Subscription::create([
            'client_id' => $client->id,
            'service_type' => 'SEO',
            'duration_months' => 1,
            'start_date' => '2026-05-01',
            'end_date' => '2026-06-01',
            'reminder_date' => '2026-05-20',
            'status' => 'ACTIVE',
            'price' => 100,
            'payment_status' => 'paid',
            'message_reminder' => 'Renew soon',
        ]);

        $this->artisan('crm:send-subscription-reminders', ['--date' => '2026-05-27'])
            ->assertExitCode(0);

        Mail::assertSent(SubscriptionReminderMail::class, function (SubscriptionReminderMail $mail) use ($client) {
            return $mail->hasTo($client->email);
        });

        $this->assertDatabaseHas('notifications', [
            'client_id' => $client->id,
            'type' => 'subscription_reminder',
            'status' => 'sent',
        ]);
    }

    public function test_scheduler_marks_due_notification_failed_when_client_has_no_email(): void
    {
        Mail::fake();

        $client = Client::create([
            'name' => 'Acme',
            'phone' => '0600000000',
            'email' => null,
        ]);
        $subscription = Subscription::create([
            'client_id' => $client->id,
            'service_type' => 'seo',
            'duration_months' => 1,
            'start_date' => '2026-05-01',
            'end_date' => '2026-06-01',
            'reminder_date' => '2026-05-27',
            'status' => 'active',
            'price' => 100,
            'payment_status' => 'paid',
            'message_reminder' => 'Renew soon',
        ]);
        $notification = Notification::create([
            'client_id' => $client->id,
            'subscription_id' => $subscription->id,
            'message' => 'Custom message',
            'type' => 'email',
            'status' => 'pending',
            'reminder_date' => '2026-05-27',
        ]);

        $this->artisan('crm:send-subscription-reminders', ['--date' => '2026-05-27'])
            ->assertExitCode(0);

        Mail::assertNothingSent();
        $this->assertTrue($notification->fresh()->status === 'failed');
    }
}
