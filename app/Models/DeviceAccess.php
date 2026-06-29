<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class DeviceAccess extends Model
{
    /**
     * The table associated with the model.
     *
     * @var string
     */
    protected $table = 'device_accesses';

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'device_id',
        'access_name',
        'status',
    ];
}

