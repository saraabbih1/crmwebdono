<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('subscriptions', function (Blueprint $table) {
            if (! Schema::hasColumn('subscriptions', 'duration_months')) {
                $table->unsignedSmallInteger('duration_months')->default(1)->after('service_type');
            }
        });

        Schema::table('notifications', function (Blueprint $table) {
            if (! Schema::hasColumn('notifications', 'reminder_date')) {
                $table->date('reminder_date')->nullable()->after('status');
            }

            if (! Schema::hasColumn('notifications', 'sent_at')) {
                $table->timestamp('sent_at')->nullable()->after('reminder_date');
            }
        });
    }

    public function down(): void
    {
        Schema::table('notifications', function (Blueprint $table) {
            if (Schema::hasColumn('notifications', 'sent_at')) {
                $table->dropColumn('sent_at');
            }

            if (Schema::hasColumn('notifications', 'reminder_date')) {
                $table->dropColumn('reminder_date');
            }
        });

        Schema::table('subscriptions', function (Blueprint $table) {
            if (Schema::hasColumn('subscriptions', 'duration_months')) {
                $table->dropColumn('duration_months');
            }
        });
    }
};
