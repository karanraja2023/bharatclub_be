<?php

namespace App\Http\Controllers\V1;

use Exception;
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Http\JsonResponse;
use Illuminate\Support\Facades\Log;
use Tymon\JWTAuth\Facades\JWTAuth;
use App\Services\DashboardServices;

class DashboardController extends Controller
{
    /**
     * @var DashboardServices
     */
    private DashboardServices $dashboardServices;

    /**
     * DashboardController constructor
     * @param DashboardServices $dashboardServices
     */
    public function __construct(
        DashboardServices $dashboardServices
    )
    {
        $this->dashboardServices = $dashboardServices;
    }

    /**
     * Dashboard list for banner and event, news
     *
     * @param Request $request
     * @return JsonResponse
     */
    public function index(Request $request): JsonResponse
    {
        try {
            $params = $this->getRequest($request);
            $data = $this->dashboardServices->getList($params);
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
