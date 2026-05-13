<div class="col-md-6">
    <label class="form-label">Client</label>
    <select name="client_id" class="form-select @error('client_id') is-invalid @enderror" required>
        <option value="">Select client</option>
        @foreach($clients as $client)
            <option value="{{ $client->id }}" @selected(old('client_id', $subscription?->client_id) == $client->id)>{{ $client->name }}</option>
        @endforeach
    </select>
    @error('client_id') <div class="invalid-feedback">{{ $message }}</div> @enderror
</div>

<div class="col-md-6">
    <label class="form-label">Service type</label>
    <select name="service_type" class="form-select @error('service_type') is-invalid @enderror" required>
        @foreach(['seo' => 'SEO', 'suivi' => 'Suivi'] as $value => $label)
            <option value="{{ $value }}" @selected(old('service_type', $subscription?->service_type) === $value)>{{ $label }}</option>
        @endforeach
    </select>
    @error('service_type') <div class="invalid-feedback">{{ $message }}</div> @enderror
</div>

<div class="col-md-4">
    <label class="form-label">Duration months</label>
    <input type="number" min="1" max="60" name="duration_months" value="{{ old('duration_months', $subscription?->duration_months ?? 1) }}" class="form-control @error('duration_months') is-invalid @enderror" required>
    @error('duration_months') <div class="invalid-feedback">{{ $message }}</div> @enderror
</div>

<div class="col-md-4">
    <label class="form-label">Start date</label>
    <input type="date" name="start_date" value="{{ old('start_date', $subscription?->start_date?->format('Y-m-d')) }}" class="form-control @error('start_date') is-invalid @enderror" required>
    @error('start_date') <div class="invalid-feedback">{{ $message }}</div> @enderror
</div>

<div class="col-md-4">
    <label class="form-label">Status</label>
    <select name="status" class="form-select @error('status') is-invalid @enderror" required>
        @foreach(['active', 'expired', 'cancelled'] as $status)
            <option value="{{ $status }}" @selected(old('status', $subscription?->status ?? 'active') === $status)>{{ ucfirst($status) }}</option>
        @endforeach
    </select>
    @error('status') <div class="invalid-feedback">{{ $message }}</div> @enderror
</div>

<div class="col-md-6">
    <label class="form-label">Price</label>
    <input type="number" step="0.01" min="0" name="price" value="{{ old('price', $subscription?->price) }}" class="form-control @error('price') is-invalid @enderror">
    @error('price') <div class="invalid-feedback">{{ $message }}</div> @enderror
</div>

<div class="col-md-6">
    <label class="form-label">Payment status</label>
    <select name="payment_status" class="form-select @error('payment_status') is-invalid @enderror" required>
        @foreach(['unpaid', 'pending', 'paid'] as $status)
            <option value="{{ $status }}" @selected(old('payment_status', $subscription?->payment_status ?? 'unpaid') === $status)>{{ ucfirst($status) }}</option>
        @endforeach
    </select>
    @error('payment_status') <div class="invalid-feedback">{{ $message }}</div> @enderror
</div>

<div class="col-12">
    <label class="form-label">Reminder message</label>
    <textarea name="message_reminder" class="form-control @error('message_reminder') is-invalid @enderror" rows="4">{{ old('message_reminder', $subscription?->message_reminder) }}</textarea>
    @error('message_reminder') <div class="invalid-feedback">{{ $message }}</div> @enderror
</div>
