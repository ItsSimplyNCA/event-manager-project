<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class SupportMessage extends Model {

    protected $fillable = [
        'support_chat_id',
        'sender_type',
        'sender_user_id',
        'body',
    ];

    protected function casts(): array {
        return [
            'body' => 'encrypted',
        ];
    }

    public function chat() {
        return $this->belongsTo(SupportChat::class, 'support_chat_id');
    }

    public function senderUser() {
        return $this->belongsTo(User::class, 'sender_user_id');
    }
}
