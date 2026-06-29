<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class EventParticipants extends Model
{
    use SoftDeletes;

    /**
     * The table associated with the model.
     *
     * @var string
     */
    protected $table = 'event_participants';

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
     protected $fillable = ['id', 'event_id', 'participant_name', 'email_address', 'no_of_participants', 'status', 'member_no_of_adults', 'member_no_of_child', 'member_no_of_child_free', 'guest_no_of_adults', 'guest_no_of_child', 'guest_no_of_child_free', 'vegetarian', 'non_vegetarian', 'jain', 'subscription_included', 'total_amount_paid',  'membership_id', 'remarks', 'created_by', 'modified_by'];
    /**
     * @return HasMany
     */
    public function EventParticipantsAttachments()
    {
        return $this->hasMany(EventAttachments::class, 'event_part_id');
    }
}
