<?php

namespace App\Http\Controllers;

use App\Services\ActivityLogger;
use App\Services\SettingsService;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Illuminate\Validation\Rule;
use Illuminate\View\View;

class SettingsController extends Controller
{
    public function edit(SettingsService $settings): View
    {
        return view('settings.edit', ['settings' => $settings->all()]);
    }

    public function update(Request $request, SettingsService $settings, ActivityLogger $activityLogger): RedirectResponse
    {
        $validated = $request->validate([
            'company_name' => ['required', 'string', 'max:255'],
            'reminder_delay_days' => ['required', 'integer', 'min:1', 'max:30'],
            'smtp_mailer' => ['required', Rule::in(['smtp', 'log', 'array'])],
            'smtp_host' => ['nullable', 'string', 'max:255'],
            'smtp_port' => ['nullable', 'integer', 'min:1', 'max:65535'],
            'smtp_scheme' => ['nullable', Rule::in(['smtp', 'smtps'])],
            'smtp_username' => ['nullable', 'string', 'max:255'],
            'smtp_password' => ['nullable', 'string', 'max:255'],
            'smtp_from_address' => ['nullable', 'email:rfc', 'max:255'],
            'smtp_from_name' => ['nullable', 'string', 'max:255'],
            'logo' => ['nullable', 'image', 'max:2048'],
        ]);

        if ($request->hasFile('logo')) {
            $validated['logo_path'] = $request->file('logo')->store('logos', 'public');
        }

        unset($validated['logo']);

        if (blank($validated['smtp_password'] ?? null)) {
            unset($validated['smtp_password']);
        }

        $settings->setMany($validated);
        $activityLogger->log('settings.updated', 'CRM settings were updated.');

        return back()->with('success', 'Settings updated successfully.');
    }
}
