<?php

namespace App\Http\Controllers;

use Illuminate\Http\JsonResponse;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Mail;
use Throwable;

class EmailDebugController extends Controller
{
    public function sendTestEmail(): JsonResponse
    {
        abort_unless(auth()->user()?->isAdmin() || app()->environment(['local', 'testing']) || config('app.debug'), 403);

        $to = 'abbihsar30@gmail.com';

        try {
            Mail::raw('CRM test email sent successfully at '.now()->format('Y-m-d H:i:s'), function ($message) use ($to): void {
                $message->to($to)
                    ->subject('CRM SMTP test email');
            });

            Log::info('CRM SMTP test email sent.', [
                'to' => $to,
                'mailer' => config('mail.default'),
                'host' => config('mail.mailers.smtp.host'),
                'port' => config('mail.mailers.smtp.port'),
                'scheme' => config('mail.mailers.smtp.scheme'),
            ]);

            return response()->json([
                'status' => 'sent',
                'message' => "Test email sent to {$to}.",
                'mail' => $this->safeMailConfig(),
            ]);
        } catch (Throwable $exception) {
            Log::error('CRM SMTP test email failed.', [
                'to' => $to,
                'error' => $exception->getMessage(),
                'exception' => $exception::class,
                'mail' => $this->safeMailConfig(),
            ]);

            return response()->json([
                'status' => 'failed',
                'message' => $exception->getMessage(),
                'exception' => $exception::class,
                'mail' => $this->safeMailConfig(),
                'hint' => 'Check storage/logs/laravel.log and confirm Gmail App Password, MAIL_USERNAME, and cleared config cache.',
            ], 500);
        }
    }

    private function safeMailConfig(): array
    {
        return [
            'default' => config('mail.default'),
            'host' => config('mail.mailers.smtp.host'),
            'port' => config('mail.mailers.smtp.port'),
            'scheme' => config('mail.mailers.smtp.scheme'),
            'username' => config('mail.mailers.smtp.username'),
            'from' => config('mail.from.address'),
        ];
    }
}
