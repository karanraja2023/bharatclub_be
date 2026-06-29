<?php

namespace App\Http\Controllers\V1;

use Exception;
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Http\JsonResponse;
use Illuminate\Support\Facades\Log;
use Tymon\JWTAuth\Facades\JWTAuth;
use App\Services\JoinClubsServices;

class JoinClubsController extends Controller
{
    /**
     * @var ClubsServices
     */
    private JoinClubsServices $JoinClubsServices;
    /**
     * ContactController constructor
     * @param ClubsServices $ClubsServices
     */
    public function __construct(
        JoinClubsServices $JoinClubsServices
    )
    {
        $this->JoinClubsServices = $JoinClubsServices;
    }

    /**
     * Create a club member
     * 
     * @param Request
     * @return JsonResponse
     */
    public function submit(Request $request): JsonResponse
    {
        try {
            $params = $this->getRequest($request);            
            $response = $this->JoinClubsServices->submit($params);
            if(isset($response['error'])) {
                return $this->validationError($response['error']);
            }
            // return $this->sendSuccess($response);
	    if($response == true){
                $data = array('message' => 'Your data has been successfully submitted');
                return $this->sendSuccess($data);

            }
        } catch (Exception $e) {
            Log::error('Error - ' . print_r($e->getMessage(), true));
            return $this->sendError(['message' => 'Failed to create a Club Member'], 400);
        }
    }



}
