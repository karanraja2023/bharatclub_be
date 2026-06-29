<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Event extends Model
{
    use SoftDeletes;

    /**
     * The table associated with the model.
     *
     * @var string
     */
    protected $table = 'event';

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = ['id', 'title', 'description', 'start_date', 'end_date', 'status', 'created_by', 'modified_by'];

    /**
     * @return HasMany
     */
    public function eventAttachments()
    {
        return $this->hasMany(EventAttachments::class, 'event_id');
    }
}
