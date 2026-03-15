<?php

namespace App\Models;

use App\Notifications\ResetPasswordNotification;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
// use Illuminate\Contracts\Auth\MustVerifyEmail;
use Database\Factories\UserFactory;

class User extends Authenticatable {
    use HasFactory, Notifiable;

    protected $fillable = [
        'name',
        'email',
        'password',
        'role',
    ];

    protected $hidden = [
        'password',
        'remember_token',
    ];

    protected function casts(): array {
        return [
            'email_verified_at' => 'datetime',
            'password' => 'hashed',
        ];
    }

    public function events() {
        return $this->hasMany(Event::class);
    }

    public function supportChats() {
        return $this->hasMany(supportChat::class);
    }

    public function assignedChats() {
        return $this->hasMany(SupportChat::class, 'assigned_agent_id');
    }

    public function sendPasswordResetNotification($token): void {
        $this->notify(new ResetPasswordNotification($token));
    }
}
