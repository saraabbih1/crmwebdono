<?php

namespace Tests\Feature;

use App\Models\Client;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
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
}
