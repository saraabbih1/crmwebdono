<?php

use Illuminate\Foundation\Inspiring;
use Illuminate\Support\Facades\Artisan;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\Schedule;
use App\Services\ReminderEmailService;
use Illuminate\Support\Carbon;

Artisan::command('inspire', function () {
    $this->comment(Inspiring::quote());
})->purpose('Display an inspiring quote');

Artisan::command('crm:send-subscription-reminders {--date= : Override reminder date in Y-m-d format} {--debug : Show mail and reminder diagnostics} {--dry-run : Count due reminders without sending email}', function (ReminderEmailService $reminderEmailService) {
    $date = $this->option('date')
        ? Carbon::parse($this->option('date'))
        : today();

    $due = $reminderEmailService->countDueSubscriptions($date);

    if ($this->option('debug')) {
        $this->line('Reminder date: '.$date->toDateString());
        $this->line('Due active SEO/Suivi subscriptions: '.$due);
        $this->line('Mailer: '.config('mail.default'));
        $this->line('SMTP host: '.config('mail.mailers.smtp.host'));
        $this->line('SMTP port: '.config('mail.mailers.smtp.port'));
        $this->line('SMTP scheme: '.config('mail.mailers.smtp.scheme'));
        $this->line('SMTP username: '.config('mail.mailers.smtp.username'));
        $this->line('From address: '.config('mail.from.address'));
    }

    if ($this->option('dry-run')) {
        $this->warn('Dry run enabled: no emails were sent.');
        $this->info("Subscription reminders due: {$due}");

        return self::SUCCESS;
    }

    $sent = $reminderEmailService->sendDueReminders($date);

    $this->info("Subscription reminders due: {$due}");
    $this->info("Subscription reminders sent: {$sent}");
})->purpose('Send due subscription reminder emails');

Artisan::command('crm:test-email {email=abbihsar30@gmail.com}', function () {
    $email = $this->argument('email');

    try {
        Mail::raw('CRM SMTP test email sent successfully at '.now()->format('Y-m-d H:i:s'), function ($message) use ($email): void {
            $message->to($email)
                ->subject('CRM SMTP test email');
        });

        Log::info('CRM SMTP CLI test email sent.', [
            'to' => $email,
            'mailer' => config('mail.default'),
            'host' => config('mail.mailers.smtp.host'),
            'port' => config('mail.mailers.smtp.port'),
            'scheme' => config('mail.mailers.smtp.scheme'),
        ]);

        $this->info("Test email sent to {$email}.");

        return self::SUCCESS;
    } catch (\Throwable $exception) {
        Log::error('CRM SMTP CLI test email failed.', [
            'to' => $email,
            'error' => $exception->getMessage(),
            'exception' => $exception::class,
            'mailer' => config('mail.default'),
            'host' => config('mail.mailers.smtp.host'),
            'port' => config('mail.mailers.smtp.port'),
            'scheme' => config('mail.mailers.smtp.scheme'),
        ]);

        $this->error('Test email failed: '.$exception->getMessage());
        $this->warn('Check storage/logs/laravel.log for full SMTP diagnostics.');

        return self::FAILURE;
    }
})->purpose('Send an immediate SMTP test email');

Schedule::command('crm:send-subscription-reminders')->dailyAt('08:00');
