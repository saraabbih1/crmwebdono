<select name="client_id" required>
    <option value="">Client</option>
    @foreach($clients as $client)
        <option value="{{ $client->id }}" @selected(old('client_id', $notification?->client_id) == $client->id)>
            {{ $client->name }}
        </option>
    @endforeach
</select>

<select name="subscription_id" required>
    <option value="">Abonnement</option>
    @foreach($subscriptions as $subscription)
        <option value="{{ $subscription->id }}" @selected(old('subscription_id', $notification?->subscription_id) == $subscription->id)>
            {{ $subscription->client->name }} - {{ strtoupper($subscription->service_type) }}
        </option>
    @endforeach
</select>

<textarea name="message" placeholder="Message" required>{{ old('message', $notification?->message) }}</textarea>
<input type="text" name="type" value="{{ old('type', $notification?->type ?? 'email') }}" placeholder="Type" required>
<input type="text" name="status" value="{{ old('status', $notification?->status ?? 'pending') }}" placeholder="Status" required>
<input type="date" name="reminder_date" value="{{ old('reminder_date', $notification?->reminder_date?->format('Y-m-d')) }}">
<input type="datetime-local" name="sent_at" value="{{ old('sent_at', $notification?->sent_at?->format('Y-m-d\TH:i')) }}">
