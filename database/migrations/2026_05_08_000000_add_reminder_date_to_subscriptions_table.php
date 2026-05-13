<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Carbon;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('subscriptions', function (Blueprint $table): void {
            if (! Schema::hasColumn('subscriptions', 'reminder_date')) {
                $table->date('reminder_date')->nullable()->after('end_date');
            }
        });

        DB::table('subscriptions')
            ->whereNull('reminder_date')
            ->whereNotNull('end_date')
            ->orderBy('id')
            ->eachById(function ($subscription): void {
                DB::table('subscriptions')
                    ->where('id', $subscription->id)
                    ->update([
                        'reminder_date' => Carbon::parse($subscription->end_date)->subDays(5)->toDateString(),
                    ]);
            });
    }

    public function down(): void
    {
        Schema::table('subscriptions', function (Blueprint $table): void {
            if (Schema::hasColumn('subscriptions', 'reminder_date')) {
                $table->dropColumn('reminder_date');
            }
        });
    }
};
