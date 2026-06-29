<?php

namespace App\Http\Controllers\V1;

use Exception;
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Http\JsonResponse;
use Illuminate\Support\Facades\Log;
use Tymon\JWTAuth\Facades\JWTAuth;
use App\Services\SponsorServices;

class SponsorController extends Controller
{
    /**
     * @var SponsorServices
     */
    private SponsorServices $SponsorServices;

    /**
     * ContactController constructor
     * @param SponsorServices $SponsorServices
     */
    public function __construct(
        SponsorServices $SponsorServices
    )
    {
        $this->SponsorServices = $SponsorServices;
    }

     /**
     * Sponsor Page
     *
     * @param Request $request
     * @return JsonResponse
     */
    public function index(Request $request): JsonResponse
    {
        // die('Enter');
        
        try {
            $params = $this->getRequest($request);    
            $data['sponsor'] = $this->SponsorServices->getList();            

            if(isset($data['error'])) {
                return $this->validationError($data['error']); 
            }
            return $this->sendSuccess($data);
        } catch (Exception $e) {
            Log::error('Error - ' . print_r($e->getMessage(), true));
            return $this->sendError(['message' => 'Failed to List'], 400);
        }
    }

    /**
     * Sponsor filter by id
     *
     * @param Request $request
     * @return JsonResponse
     */
    public function sponsorid(Request $request): JsonResponse
    {
        // die('Enter');
        
        try {
            $params = $this->getRequest($request);    
            $data['sponsor'] = $this->SponsorServices->getListid($params);            

            if(isset($data['error'])) {
                return $this->validationError($data['error']); 
            }
            return $this->sendSuccess($data);
        } catch (Exception $e) {
            Log::error('Error - ' . print_r($e->getMessage(), true));
            return $this->sendError(['message' => 'Failed to List'], 400);
        }
    }

   /**
     * Sponsor filter by Event id
     *
     * @param Request $request
     * @return JsonResponse
     */
    public function sponsorevent(Request $request): JsonResponse
    {
        // die('Enter');
        
        try {
            $params = $this->getRequest($request);    
            $data['sponsor'] = $this->SponsorServices->getListeventid($params);            

            if(isset($data['error'])) {
                return $this->validationError($data['error']); 
            }
            return $this->sendSuccess($data);
        } catch (Exception $e) {
            Log::error('Error - ' . print_r($e->getMessage(), true));
            return $this->sendError(['message' => 'Failed to List'], 400);
        }
    }	

    
}
