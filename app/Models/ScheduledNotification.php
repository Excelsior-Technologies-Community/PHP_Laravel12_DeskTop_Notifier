<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ScheduledNotification extends Model
{
    use HasFactory;

    protected $fillable = [
        'title',
        'message',
        'type',
        'icon',
        'delay',
        'scheduled_at',
        'is_processed',
        'processed_at',
    ];

    protected function casts(): array
    {
        return [
            'scheduled_at' => 'datetime',
            'processed_at' => 'datetime',
            'is_processed' => 'boolean',
            'delay' => 'integer',
        ];
    }
}