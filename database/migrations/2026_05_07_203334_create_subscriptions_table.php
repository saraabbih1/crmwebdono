<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('subscriptions', function (Blueprint $table) {
            $table->id();

            $table->foreignId('client_id')->constrained()->onDelete('cascade');

            $table->string('service_type');

            $table->unsignedSmallInteger('duration_months');
            $table->date('start_date');
            $table->date('end_date');

            $table->string('status')->default('active');

            $table->decimal('price', 8, 2)->nullable();

            $table->string('payment_status')->default('unpaid');

            $table->text('message_reminder')->nullable();

            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('subscriptions');
    }
};
