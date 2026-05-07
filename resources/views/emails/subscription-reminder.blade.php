<p>Hello {{ $notification->client->name }},</p>

<p>{{ $notification->message }}</p>

<p>
    Subscription end date:
    {{ $notification->subscription->end_date->format('Y-m-d') }}
</p>
