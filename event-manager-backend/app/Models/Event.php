<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Event extends Model {
    protected $fillable = [
        'user_id',
        'title',
        'occurs_at',
        'description',
    ];

    protected function casts(): array {
        return [
            'occurs_at' => 'datetime',
            'description' => 'encrypted',
        ];
    }

    public function user() {
        return $this->belongsTo(User::class);
    }
}