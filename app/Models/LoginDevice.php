<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class LoginDevice extends Model
{
    protected $table = 'login_devices';

    protected $fillable = [
        'user_id',
        'device_id',
    ];

    public function user()
    {
        return $this->belongsTo(User::class);
    }
}
