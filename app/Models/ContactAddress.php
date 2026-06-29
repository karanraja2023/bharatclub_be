<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class ContactAddress extends Model
{
    use SoftDeletes;

    /**
     * The table associated with the model.
     *
     * @var string
     */
    protected $table = 'contact_address';

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = ['id', 'name', 'latitude', 'longitude', 'location', 'primary_mobile', 
        'secondary_mobile', 'email', 'website', 'address', 'status', 'created_by', 'modified_by'];
}
