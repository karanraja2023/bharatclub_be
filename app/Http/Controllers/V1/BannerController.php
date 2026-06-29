<?php

namespace App\Http\Controllers\V1;

use Exception;
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Http\JsonResponse;
use Illuminate\Support\Facades\Log;
use Tymon\JWTAuth\Facades\JWTAuth;
use App\Services\BannerServices;

class BannerController extends Controller
{
    /**
     * @var ClubsServices
     */
    private BannerServices $BannerServices;

    /**
     * ContactController constructor
     * @param ClubsServices $ClubsServices
     */
    public function __construct(
        BannerServices $BannerServices
    )
    {
        $this->BannerServices = $BannerServices;
    }

     /**
     * Clubs Page
     *
     * @param Request $request
     * @return JsonResponse
     */
    public function index(): JsonResponse
    {
        // die('Enter');
        
        try {
            // $params = $this->getRequest($request);    
            $data['banner'] = $this->BannerServices->getList();            

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
