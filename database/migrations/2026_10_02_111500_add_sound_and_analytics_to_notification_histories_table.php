<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('notification_histories', function (Blueprint $table) {
            if (!Schema::hasColumn('notification_histories', 'sound_tone')) {
                $table->string('sound_tone')->nullable()->after('icon');
            }
            if (!Schema::hasColumn('notification_histories', 'priority')) {
                $table->string('priority')->default('normal')->after('sound_tone');
            }
            if (!Schema::hasColumn('notification_histories', 'action_url')) {
                $table->string('action_url')->nullable()->after('priority');
            }
            if (!Schema::hasColumn('notification_histories', 'is_clicked')) {
                $table->boolean('is_clicked')->default(false)->after('status');
            }
            if (!Schema::hasColumn('notification_histories', 'clicked_at')) {
                $table->timestamp('clicked_at')->nullable()->after('is_clicked');
            }
        });
    }

    public function down(): void
    {
        Schema::table('notification_histories', function (Blueprint $table) {
            $table->dropColumn([
                'sound_tone',
                'priority',
                'action_url',
                'is_clicked',
                'clicked_at',
            ]);
        });
    }
};
