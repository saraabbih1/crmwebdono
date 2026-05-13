<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('settings', function (Blueprint $table): void {
            $table->id();
            $table->string('key')->unique();
            $table->text('value')->nullable();
        });

        DB::table('settings')->insert([
            ['key' => 'company_name', 'value' => 'CRM System'],
            ['key' => 'reminder_delay_days', 'value' => '5'],
            ['key' => 'smtp_mailer', 'value' => env('MAIL_MAILER', 'smtp')],
            ['key' => 'smtp_host', 'value' => env('MAIL_HOST', 'smtp.gmail.com')],
            ['key' => 'smtp_port', 'value' => env('MAIL_PORT', '587')],
            ['key' => 'smtp_username', 'value' => env('MAIL_USERNAME')],
            ['key' => 'smtp_from_address', 'value' => env('MAIL_FROM_ADDRESS')],
            ['key' => 'smtp_from_name', 'value' => env('MAIL_FROM_NAME', 'CRM System')],
            ['key' => 'logo_path', 'value' => null],
        ]);
    }

    public function down(): void
    {
        Schema::dropIfExists('settings');
    }
};
