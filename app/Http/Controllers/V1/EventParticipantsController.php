<?php

namespace App\Http\Controllers\V1;

use Exception;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Config;
use Illuminate\Http\Request;
use Illuminate\Http\JsonResponse;
use Illuminate\Support\Facades\Log;
use Tymon\JWTAuth\Facades\JWTAuth;
use App\Services\EventParticipantsServices;

class EventParticipantsController extends Controller
{
    /**
     * @var EventParticipantsServices
     */
    private EventParticipantsServices $EventParticipantsServices;
    /**
     * ContactController constructor
     * @param EventParticipantsServices $EventParticipantsServices
     */
    public function __construct(
        EventParticipantsServices $EventParticipantsServices
    )
    {
        $this->EventParticipantsServices = $EventParticipantsServices;
    }

    /**
     * Create a Event Participants
     * 
     * @param Request
     * @return JsonResponse
     */
    public function submit(Request $request): JsonResponse
    {
        try {
            $params = $this->getRequest($request);    

            $response = $this->EventParticipantsServices->submit($params);

	   
            if(isset($response['error'])) {
                return $this->validationError($response['error']);
            }
            if($response == 1)
            {
		$data = array(
			  'error' => true,
                         'response' => '',
			  'message' => 'You have already registered for this event'

                        );
            //  return $this->sendError($data);

                return $this->sendError(mb_convert_encoding('You have already registered for this event', 'UTF-8', 'ISO-8859-1'));
            }
            $data = array('error' => false,
			 'response' =>$response,
                         'message' => 'Your data has been successfully submitted'
                        );
	 

            return $this->sendSuccess($data);	    
        } catch (Exception $e) {
            Log::error('Error - ' . print_r($e->getMessage(), true));
            return $this->sendError(['message' => 'Failed to create a Event Participants'], 400);
        }
    }
    
    /**
     * Get a Event Participants
     * 
     * @param Request
     * @return JsonResponse
     */
    public function qrscan(Request $request): JsonResponse
    {
        try {
            $params = $this->getRequest($request);    

            $response = $this->EventParticipantsServices->qrscan($params);
           
            if(isset($response['error'])) {
                return $this->validationError($response['error']);
            }
            
            $data = array('error' => false,
                         'response' => $response
                        );

            return $this->sendSuccess($data);       
        } catch (Exception $e) {
            Log::error('Error - ' . print_r($e->getMessage(), true));
            return $this->sendError(['message' => 'Failed to get a Event Participants'], 400);
        }
    }

    /**
     * Update a Event attendance
     * 
     * @param Request
     * @return JsonResponse
     */
    public function attendance(Request $request): JsonResponse
    {
        try {
            $params = $this->getRequest($request);    

            $response = $this->EventParticipantsServices->attendance($params);
           
            if(isset($response['error'])) {
                return $this->validationError($response['error']);
            }
            
            $data = array('error' => false,
                         'response' => $response,
                         'message' => 'Your attendance has been successfully updated'
                        );

            return $this->sendSuccess($data);       
        } catch (Exception $e) {
            Log::error('Error - ' . print_r($e->getMessage(), true));
            return $this->sendError(['message' => 'Failed to update a Event Participants attendance'], 400);
        }
    }

     /**
     * Update a Event attendance
     * 
     * @param Request
     * @return JsonResponse
     */
    public function membershiptype(): JsonResponse
    {
        try {

            $data = $this->EventParticipantsServices->membershiptype();
           
            if(isset($response['error'])) {
                return $this->validationError($response['error']);
            }
                       

            return $this->sendSuccess($data);       
        } catch (Exception $e) {
            Log::error('Error - ' . print_r($e->getMessage(), true));
            return $this->sendError(['message' => 'Failed to get the Membership Type details'], 400);
        }
    }

    /**
     * Create a Event Participants
     * 
     * @param Request
     * @return JsonResponse
     */
    public function upload(Request $request): JsonResponse
    {

        try {
        
            $response = $this->EventParticipantsServices->uploadEventparticipantsImage($request);

            if(isset($response['error'])) {
                return $this->validationError($response['error']);
            }
            // return $this->sendSuccess($response);
	    if($response == true){
                $data = array('message' => 'Your data has been successfully uploaded');
                return $this->sendSuccess($data);

            }
        } catch (Exception $e) {
            Log::error('Error - ' . print_r($e->getMessage(), true));
            return $this->sendError(['message' => 'Failed to create a Event Participants'], 400);
        }
    }



}
