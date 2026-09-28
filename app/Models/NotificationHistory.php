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
        'delay',
        'status',
        'source',
        'error_message',
        'sent_at',
    ];

    protected function casts(): array
    {
        return [
            'sent_at' => 'datetime',
            'delay' => 'integer',
        ];
    }
}