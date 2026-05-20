<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Support\Facades\DB;

return new class extends Migration
{
    public function up(): void
    {
        foreach ([
            'smtp_scheme' => env('MAIL_SCHEME', 'smtp'),
            'smtp_password' => env('MAIL_PASSWORD'),
        ] as $key => $value) {
            DB::table('settings')->updateOrInsert(
                ['key' => $key],
                ['value' => $value]
            );
        }
    }

    public function down(): void
    {
        DB::table('settings')
            ->whereIn('key', ['smtp_scheme', 'smtp_password'])
            ->delete();
    }
};
