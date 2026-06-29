<?php

namespace App\Services;

use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\Config;
use Illuminate\Support\Facades\Storage;
use Tymon\JWTAuth\Facades\JWTAuth;
use Illuminate\Http\Request;
use App\Models\Event;

class EventServices
{
    public const STATUS_ACTIVE = 1;

    /**
     * @var Event
     */
    private Event $event;

    /**
     * EventServices constructor
     * @param Event $event
     */
    public function __construct(
        Event $event
    ) 
    {
        $this->event = $event;
    }
    
    /**
     * Returns a list of event.
     * 
     * @param array $request.
     * @return mixed Returns a list of event.
     */
    public function getList($request): mixed
    {
        $event = $this->event
            ->with(['eventAttachments' => function ($query) {
                $query->select(['id', 'event_id', 'file_name', 'file_type', 'file_url']);
            }])
            ->where(function ($query) use ($request) {
                if (isset($request['keyword']) && !empty($request['keyword'])) {
                    $query->where('title', 'like', '%' . $request['keyword'] . '%');
                }
                $query->where('event.status', self::STATUS_ACTIVE);
            })
            //->select('event.id', 'event.title', 'event.description', 'event.start_date', 'event.end_date', 'event.status')
	    ->select('event.id', 'event.title', 'event.description', 'event.start_date', 'event.end_date', 'event.status', 'event.created_by', 'event.modified_by', 'event.created_at', 'event.updated_at', 'event.deleted_at', 'event.member_adult_age', 'event.member_adult_amount', 'event.member_child_status', 'event.member_child_age', 'event.member_child_amount', 'event.guest_adult_age', 'event.guest_adult_amount', 'event.guest_child_status', 'event.guest_child_age', 'event.guest_child_amount', 'event.food_status', 'event.subscription_status')	
            ->distinct()
            ->orderBy('event.id', 'ASC');
        
        if (isset($request['limit']) && !empty($request['limit'])) {
            $event = $event->take($request['limit']);
        }

        return $event->get();
    }
}