@extends('layouts.app')

@section('title', 'Settings')
@section('page-title', 'Settings')
@section('page-subtitle', 'Company, reminder, and SMTP configuration')

@section('content')
    <form method="POST" action="{{ route('settings.update') }}" enctype="multipart/form-data" class="card content-card">
        @csrf
        @method('PUT')
        <div class="card-header bg-transparent border-0 pt-4 px-4">
            <div class="fw-semibold">Workspace configuration</div>
            <div class="small text-secondary">Company identity and mail delivery settings.</div>
        </div>
        <div class="card-body">
            <div class="row g-4">
                <div class="col-lg-6">
                    <div class="soft-card p-3 mb-3">
                        <h2 class="h5 mb-1">Company</h2>
                        <div class="small text-secondary">Branding and reminder defaults shown across the CRM.</div>
                    </div>
                    <div class="mb-3">
                        <label class="form-label">Company name</label>
                        <input type="text" name="company_name" value="{{ old('company_name', $settings['company_name'] ?? '') }}" class="form-control @error('company_name') is-invalid @enderror" required>
                        @error('company_name') <div class="invalid-feedback">{{ $message }}</div> @enderror
                    </div>
                    <div class="mb-3">
                        <label class="form-label">Logo</label>
                        <input type="file" name="logo" class="form-control @error('logo') is-invalid @enderror" accept="image/*">
                        @error('logo') <div class="invalid-feedback">{{ $message }}</div> @enderror
                    </div>
                    <div>
                        <label class="form-label">Reminder delay days</label>
                        <input type="number" name="reminder_delay_days" min="1" max="30" value="{{ old('reminder_delay_days', $settings['reminder_delay_days'] ?? 5) }}" class="form-control @error('reminder_delay_days') is-invalid @enderror" required>
                        @error('reminder_delay_days') <div class="invalid-feedback">{{ $message }}</div> @enderror
                    </div>
                </div>
                <div class="col-lg-6">
                    <div class="soft-card p-3 mb-3">
                        <h2 class="h5 mb-1">SMTP</h2>
                        <div class="small text-secondary">Delivery configuration used by scheduled reminders.</div>
                    </div>
                    <div class="row g-3">
                        <div class="col-md-4">
                            <label class="form-label">Mailer</label>
                            <select name="smtp_mailer" class="form-select">
                                @foreach(['smtp', 'log', 'array'] as $mailer)
                                    <option value="{{ $mailer }}" @selected(old('smtp_mailer', $settings['smtp_mailer'] ?? 'smtp') === $mailer)>{{ strtoupper($mailer) }}</option>
                                @endforeach
                            </select>
                        </div>
                        <div class="col-md-8">
                            <label class="form-label">Host</label>
                            <input type="text" name="smtp_host" value="{{ old('smtp_host', $settings['smtp_host'] ?? '') }}" class="form-control">
                        </div>
                        <div class="col-md-4">
                            <label class="form-label">Port</label>
                            <input type="number" name="smtp_port" value="{{ old('smtp_port', $settings['smtp_port'] ?? 587) }}" class="form-control">
                        </div>
                        <div class="col-md-4">
                            <label class="form-label">Scheme</label>
                            <select name="smtp_scheme" class="form-select">
                                @foreach(['smtp' => 'SMTP / STARTTLS', 'smtps' => 'SMTPS / SSL'] as $scheme => $label)
                                    <option value="{{ $scheme }}" @selected(old('smtp_scheme', $settings['smtp_scheme'] ?? 'smtp') === $scheme)>{{ $label }}</option>
                                @endforeach
                            </select>
                        </div>
                        <div class="col-md-8">
                            <label class="form-label">Username</label>
                            <input type="text" name="smtp_username" value="{{ old('smtp_username', $settings['smtp_username'] ?? '') }}" class="form-control">
                        </div>
                        <div class="col-md-8">
                            <label class="form-label">Password / SMTP key</label>
                            <input type="password" name="smtp_password" value="" class="form-control" placeholder="{{ ! empty($settings['smtp_password'] ?? null) ? 'Saved. Leave blank to keep current key.' : 'Paste Brevo SMTP key' }}">
                        </div>
                        <div class="col-md-6">
                            <label class="form-label">From address</label>
                            <input type="email" name="smtp_from_address" value="{{ old('smtp_from_address', $settings['smtp_from_address'] ?? '') }}" class="form-control">
                        </div>
                        <div class="col-md-6">
                            <label class="form-label">From name</label>
                            <input type="text" name="smtp_from_name" value="{{ old('smtp_from_name', $settings['smtp_from_name'] ?? '') }}" class="form-control">
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <div class="card-footer bg-transparent border-0 px-4 pb-4">
            <button class="btn btn-primary">Save settings</button>
        </div>
    </form>
@endsection
