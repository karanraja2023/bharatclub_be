<?php

namespace App\Services;

use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\Config;
use Illuminate\Support\Facades\Storage;
use Tymon\JWTAuth\Facades\JWTAuth;
use Illuminate\Http\Request;
use App\Models\MembershipType;
use App\Models\EventParticipants;
use App\Models\EventParticipantsAttachments;

class EventParticipantsServices
{
    public const STATUS_ACTIVE = 1;

    /**
     * @var Clubs
     */
    private EventParticipants $eventparticipants;
    private EventParticipantsAttachments $eventparticipantsattachments;
    private MembershipType $membershiptype;
    private Storage $storage;

    /**
     * EventParticipantsServices constructor
     * @param Clubs $Clubs
     */
    public function __construct(
        EventParticipants $eventparticipants,
        EventParticipantsAttachments $eventparticipantsattachments,
	MembershipType $membershiptype,
        Storage $storage
    ) 
    {
        $this->eventparticipants = $eventparticipants;
        $this->eventparticipantsattachments = $eventparticipantsattachments;
 	$this->membershiptype = $membershiptype;
        $this->storage = $storage;
    }
    

     /**
     * @return array
     */
    public function eventparticipantsValidation(): array
    {
        return [
            'event_id' => 'required',
            'participant_name' => 'required',
            'email_address' => 'required|email|max:150',
            'no_of_participants' => 'required',
            'no_of_adult' => 'required',
            'no_of_child' => 'required',
            'no_of_free_child' => 'required',
            'membership_id'  => 'required',         
        ];
    }
   
    public function eventparticipantsexistsValidation(): array
    {
        return [
            'event_id' => 'required',            
            'membership_id'  => 'required',
         
        ];
    }

    public function eventparticipantsattendanceValidation(): array
    {
        return [
            'event_id' => 'required',            
            'membership_id'  => 'required',
            'adult_attend'  => 'required',
            'child_attend'  => 'required',
            'guest_attend'  => 'required',
            'guest_child_attend'  => 'required',
         
        ];
    }

     /**
     * @param $request
     * @return mixed
     */
    public function submit($request): mixed
    {
       
        $STATUS_EXISTS = 1;        

        $validator = Validator::make($request, $this->eventparticipantsValidation());
        if($validator->fails()) {
            return [
                'error' => $validator->errors()
            ];
        }
	
	$count_part = $this->eventparticipants
                    ->where('event_id', $request['event_id'])
                    ->where('email_address',$request['email_address'])
                    ->count('id');

        if($count_part >0){
           /* return [
                'error' => 'You have already registered for this event'
            ]; */
	   return 1;

        }
        
        $eventpartresults = $this->eventparticipants->create([
                            'event_id' => $request['event_id'] ?? '',
                            'participant_name' => $request['participant_name'] ?? '',
                            'email_address' => $request['email_address'] ?? '',
                            'no_of_participants' => $request['no_of_participants'] ?? '',
                            // 'remarks' => $request['remarks'] ?? '',               
                            'status' => self::STATUS_ACTIVE ?? 1, 
                            'member_no_of_adults' => $request['no_of_adult'] ?? 0,
                            'member_no_of_child' => $request['no_of_child'] ?? 0,
                            'member_no_of_child_free' => $request['no_of_free_child'] ?? 0,
                            'guest_no_of_adults' => $request['no_of_guest'] ?? 0,
                            'guest_no_of_child' => $request['no_of_guest_child'] ?? 0,
                            'guest_no_of_child_free' => $request['no_of_guest_free_child'] ?? 0,
                            'vegetarian' => $request['veg'] ?? 0,
                            'non_vegetarian' => $request['non_veg'] ?? 0,
                            'jain' => $request['jain'] ?? 0,
                            'subscription_included' => $request['subs_include'] ?? 0,
                            'total_amount_paid' => $request['total_amount_paid'] ?? 0,   
                            'membership_id' => $request['membership_id'] ?? ''  
                    ]);

         return  mb_convert_encoding($eventpartresults, 'UTF-8', 'UTF-8');
    }

    /**
     * @param $request
     * @return mixed
     */
     public function qrscan($request): mixed
    {
        $STATUS_EXISTS =1;
        $data = array();
        $validator = Validator::make($request, $this->eventparticipantsexistsValidation());
        if($validator->fails()) {
            return [
                'error' => $validator->errors()
            ];
        }
        $partdetails = $this->eventparticipants->leftJoin('event', 'event.id', 'event_participants.event_id') 
                    ->where('event_participants.event_id', $request['event_id'])
                    ->where('event_participants.membership_id',$request['membership_id'])
                    ->select('event_participants.participant_name','event_participants.member_no_of_adults','event_participants.member_no_of_child','event_participants.member_no_of_child_free','event_participants.guest_no_of_adults','event_participants.guest_no_of_child','event_participants.guest_no_of_child_free','event_participants.status','event_participants.membership_id','event.member_child_status','event.guest_child_status')
                    ->first();
        
        if(is_null($partdetails)){
            return false;
        }

         return $partdetails;
    }

     /**
     * @param $request
     * @return mixed
     */
     public function attendance($request): mixed
    {
        $STATUS_EXISTS =1;
        $data = 1;
        $validator = Validator::make($request, $this->eventparticipantsattendanceValidation());
        if($validator->fails()) {
            return [
                'error' => $validator->errors()
            ];
        }
        $partdetails = $this->eventparticipants
                    ->where('event_id', $request['event_id'])
                    ->where('membership_id',$request['membership_id'])
                    ->first();
        
        if(is_null($partdetails)){
            return true;
        }

       $partdetails->member_adults_attended =$request['adult_attend'] ?? $request['adult_attend'];
        $partdetails->member_child_attended =$request['child_attend'] ?? $request['child_attend'];
        $partdetails->guest_adults_attended =$request['guest_attend'] ?? $request['guest_attend'];
        $partdetails->guest_child_attended =$request['guest_child_attend'] ?? $request['guest_child_attend'];
	$partdetails->remarks =$request['remarks'] ?? $request['remarks'];
        $partdetails->save();

       // $data = $partdetails->update([
       //      'member_adults_attended' => $request['adult_attend'] ?? $request['adult_attend'],
       //      'member_child_attended' => $request['child_attend'] ?? $request['child_attend'],
       //      'guest_adults_attended' => $request['guest_attend'] ?? $request['guest_attend'],
       //      'guest_child_attended' => $request['guest_child_attend'] ?? $request['guest_child_attend']            
       //  ]);
        
         return $data;
    }

     /**
     * @param $request
     * @return mixed
     */
     public function membershiptype(): mixed
    {
        $STATUS_EXISTS =1;
        $data = array();       
        $membertypedetails = $this->membershiptype                    
                            ->select('id','type','amount as subscription_amount','renewal_status')
                            ->get();
        
        if(is_null($membertypedetails)){
            return false;
        }

         return $membertypedetails;
    }

    /**
     * @param $request
     * @return bool|array
     */
    public function uploadEventparticipantsImage($request): bool|array
    {
        // $user = $this->show($request['id']);
        if (request()->hasFile('attachment')) {
            $file = $request->file('attachment');
            $fileName = $file->getClientOriginalName();                 
            $filePath = '/club_portal/user/eventparticipants/'. $fileName; 
            $linode = $this->storage::disk('linode');
            $linode->put($filePath, file_get_contents($file));
            $fileUrl = $this->storage::disk('linode')->url($filePath);

            $this->eventparticipantsattachments->updateOrCreate(
                [
                    "event_part_id" => $request['id']
                ],
                [
                "file_name" => $fileName,
                "file_type" => 'Event Participants',
                "file_url" =>  $fileUrl                
            ]);
        }
        else
        {
            return [
                'error' => 'The attachment is required'
            ];
        } 

        return true;
    }

    private function show($request)
    {
        return $this->eventparticipants
            ->where('event_id', $request['event_id'])->first();
    }


}