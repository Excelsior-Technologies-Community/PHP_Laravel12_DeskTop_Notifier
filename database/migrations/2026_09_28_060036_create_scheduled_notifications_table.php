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
        Schema::create('scheduled_notifications', function (Blueprint $table) {
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

            $table->timestamp('scheduled_at');

            $table->boolean('is_processed')->default(false);

            $table->timestamp('processed_at')->nullable();

            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('scheduled_notifications');
    }
};