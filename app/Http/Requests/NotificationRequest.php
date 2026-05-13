<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class NotificationRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'client_id' => ['required', 'exists:clients,id'],
            'subscription_id' => ['required', 'exists:subscriptions,id'],
            'message' => ['required', 'string', 'max:5000'],
            'type' => ['required', Rule::in(['email', 'subscription_reminder'])],
            'status' => ['required', Rule::in(['pending', 'sent', 'failed'])],
            'reminder_date' => ['nullable', 'date'],
            'sent_at' => ['nullable', 'date'],
        ];
    }
}
