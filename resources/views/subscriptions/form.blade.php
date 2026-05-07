<select name="client_id" required>
    <option value="">Client</option>
    @foreach($clients as $client)
        <option value="{{ $client->id }}" @selected(old('client_id', $subscription?->client_id) == $client->id)>
            {{ $client->name }}
        </option>
    @endforeach
</select>

<select name="service_type" required>
    @foreach(['seo' => 'SEO', 'suivi' => 'Suivi', 'autre' => 'Autre'] as $value => $label)
        <option value="{{ $value }}" @selected(old('service_type', $subscription?->service_type) === $value)>
            {{ $label }}
        </option>
    @endforeach
</select>

<select name="duration_months" required>
    @foreach([1, 6, 12] as $duration)
        <option value="{{ $duration }}" @selected(old('duration_months', $subscription?->duration_months) == $duration)>
            {{ $duration }} mois
        </option>
    @endforeach
</select>

<input type="date" name="start_date" value="{{ old('start_date', $subscription?->start_date?->format('Y-m-d')) }}" required>
<input type="text" name="status" value="{{ old('status', $subscription?->status ?? 'active') }}" placeholder="Status" required>
<input type="number" step="0.01" name="price" value="{{ old('price', $subscription?->price) }}" placeholder="Prix">
<input type="text" name="payment_status" value="{{ old('payment_status', $subscription?->payment_status ?? 'unpaid') }}" placeholder="Paiement" required>
<textarea name="message_reminder" placeholder="Message de rappel">{{ old('message_reminder', $subscription?->message_reminder) }}</textarea>
