<div class="col-md-6">
    <label class="form-label">Client</label>
    <select name="client_id" class="form-select @error('client_id') is-invalid @enderror" required>
        <option value="">Select client</option>
        @foreach($clients as $client)
            <option value="{{ $client->id }}" @selected(old('client_id', $notification?->client_id) == $client->id)>{{ $client->name }}</option>
        @endforeach
    </select>
    @error('client_id') <div class="invalid-feedback">{{ $message }}</div> @enderror
</div>

<div class="col-md-6">
    <label class="form-label">Subscription</label>
    <select name="subscription_id" class="form-select @error('subscription_id') is-invalid @enderror" required>
        <option value="">Select subscription</option>
        @foreach($subscriptions as $subscription)
            <option value="{{ $subscription->id }}" @selected(old('subscription_id', $notification?->subscription_id) == $subscription->id)>
                {{ $subscription->client->name }} - {{ strtoupper($subscription->service_type) }}
            </option>
        @endforeach
    </select>
    @error('subscription_id') <div class="invalid-feedback">{{ $message }}</div> @enderror
</div>

<div class="col-12">
    <label class="form-label">Message</label>
    <textarea name="message" class="form-control @error('message') is-invalid @enderror" rows="4" required>{{ old('message', $notification?->message) }}</textarea>
    @error('message') <div class="invalid-feedback">{{ $message }}</div> @enderror
</div>

<div class="col-md-3">
    <label class="form-label">Type</label>
    <select name="type" class="form-select @error('type') is-invalid @enderror" required>
        @foreach(['email' => 'Email', 'subscription_reminder' => 'Subscription reminder'] as $value => $label)
            <option value="{{ $value }}" @selected(old('type', $notification?->type ?? 'email') === $value)>{{ $label }}</option>
        @endforeach
    </select>
    @error('type') <div class="invalid-feedback">{{ $message }}</div> @enderror
</div>

<div class="col-md-3">
    <label class="form-label">Status</label>
    <select name="status" class="form-select @error('status') is-invalid @enderror" required>
        @foreach(['pending', 'sent', 'failed'] as $status)
            <option value="{{ $status }}" @selected(old('status', $notification?->status ?? 'pending') === $status)>{{ ucfirst($status) }}</option>
        @endforeach
    </select>
    @error('status') <div class="invalid-feedback">{{ $message }}</div> @enderror
</div>

<div class="col-md-3">
    <label class="form-label">Reminder date</label>
    <input type="date" name="reminder_date" value="{{ old('reminder_date', $notification?->reminder_date?->format('Y-m-d')) }}" class="form-control @error('reminder_date') is-invalid @enderror">
    @error('reminder_date') <div class="invalid-feedback">{{ $message }}</div> @enderror
</div>

<div class="col-md-3">
    <label class="form-label">Sent at</label>
    <input type="datetime-local" name="sent_at" value="{{ old('sent_at', $notification?->sent_at?->format('Y-m-d\TH:i')) }}" class="form-control @error('sent_at') is-invalid @enderror">
    @error('sent_at') <div class="invalid-feedback">{{ $message }}</div> @enderror
</div>
