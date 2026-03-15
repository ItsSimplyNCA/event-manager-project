<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class SupportChat extends Model {
    protected $fillable = [
        'user_id',
        'assigned_agent_id',
        'status',
        'last_message_at',
    ];

    protected function casts(): array {
        return [
            'last_message_at' => 'datetime',
        ];
    }

    public function user() {
        return $this->belongsTo(User::class);
    }

    public function assignedAgent() {
        return $this->belongsTo(User::class, 'assigned_agent_id');
    }

    public function messages() {
        return $this->hasMany(SupportMessage::class);
    }
}