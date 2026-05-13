<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class SubscriptionRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'client_id' => ['required', 'exists:clients,id'],
            'service_type' => ['required', Rule::in(['seo', 'suivi'])],
            'duration_months' => ['required', 'integer', 'min:1', 'max:60'],
            'start_date' => ['required', 'date'],
            'status' => ['required', Rule::in(['active', 'expired', 'cancelled'])],
            'price' => ['nullable', 'numeric', 'min:0', 'max:999999.99'],
            'payment_status' => ['required', Rule::in(['unpaid', 'pending', 'paid'])],
            'message_reminder' => ['nullable', 'string', 'max:5000'],
        ];
    }
}
