<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class NotificationHistory extends Model
{
    use HasFactory;

    protected $fillable = [
        'title',
        'message',
        'type',
        'icon',
        'sound_tone',
        'priority',
        'action_url',
        'delay',
        'status',
        'source',
        'error_message',
        'is_clicked',
        'clicked_at',
        'sent_at',
    ];

    protected function casts(): array
    {
        return [
            'sent_at' => 'datetime',
            'clicked_at' => 'datetime',
            'delay' => 'integer',
            'is_clicked' => 'boolean',
        ];
    }
}