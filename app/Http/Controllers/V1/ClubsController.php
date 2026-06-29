<?php

namespace App\Http\Controllers\V1;

use Exception;
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Http\JsonResponse;
use Illuminate\Support\Facades\Log;
use Tymon\JWTAuth\Facades\JWTAuth;
use App\Services\ClubsServices;

class ClubsController extends Controller
{
    /**
     * @var ClubsServices
     */
    private ClubsServices $ClubsServices;

    /**
     * ContactController constructor
     * @param ClubsServices $ClubsServices
     */
    public function __construct(
        ClubsServices $ClubsServices
    )
    {
        $this->ClubsServices = $ClubsServices;
    }

     /**
     * Clubs Page
     *
     * @param Request $request
     * @return JsonResponse
     */
    public function index(Request $request): JsonResponse
    {
        // die('Enter');
        
        try {
            $params = $this->getRequest($request);    
            $data['Clubs'] = $this->ClubsServices->getList($params);
            $data['ClubsAttachments'] = $this->ClubsServices->getListAttachments($params);
	    $data['ClubsMembership'] = $this->ClubsServices->getListMembership($params);

            if(isset($data['error'])) {
                return $this->validationError($data['error']); 
            }
            return $this->sendSuccess($data);
        } catch (Exception $e) {
            Log::error('Error - ' . print_r($e->getMessage(), true));
            return $this->sendError(['message' => 'Failed to List Dashboard'], 400);
        }
    }

    /**
     * Contact Submit
     *
     * @param Request $request
     * @return JsonResponse
     */
    public function membership(Request $request): JsonResponse
    {
        // die('Enter');
       
        try {
            $params = $this->getRequest($request);    
            $data = $this->ClubsServices->getListMembership($params);            

            if(isset($data['error'])) {
                return $this->validationError($data['error']); 
            }
            return $this->sendSuccess($data);
        } catch (Exception $e) {
            Log::error('Error - ' . print_r($e->getMessage(), true));
            return $this->sendError(['message' => 'Failed to List Dashboard'], 400);
        }
    }
}
