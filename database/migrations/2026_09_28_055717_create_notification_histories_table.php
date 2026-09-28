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
        Schema::create('notification_histories', function (Blueprint $table) {
            $table->id();

            $table->string('title');
            $table->text('message');

            $table->enum('type', [
                'success',
                'warning',
                'error',
                'info'
            ])->default('info');

            $table->string('icon')->nullable();

            $table->unsignedInteger('delay')->default(0);

            $table->enum('status', [
                'sent',
                'failed'
            ])->default('sent');

            $table->string('source')->default('browser');

            $table->text('error_message')->nullable();

            $table->timestamp('sent_at')->nullable();

            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('notification_histories');
    }
};