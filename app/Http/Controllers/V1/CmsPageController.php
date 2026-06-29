<?php

namespace App\Http\Controllers\V1;

use Exception;
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Http\JsonResponse;
use Illuminate\Support\Facades\Log;
use Tymon\JWTAuth\Facades\JWTAuth;
use App\Services\CmsPageServices;

class CmsPageController extends Controller
{
    /**
     * @var CmsPageServices
     */
    private CmsPageServices $cmsPageServices;

    /**
     * CmsPageController constructor
     * @param CmsPageServices $cmsPageServices
     */
    public function __construct(
        CmsPageServices $cmsPageServices
    )
    {
        $this->cmsPageServices = $cmsPageServices;
    }

    /**
     * CMS Page
     *
     * @param Request $request
     * @return JsonResponse
     */
    public function index(Request $request): JsonResponse
    {
        try {
            $params = $this->getRequest($request);
            $data = $this->cmsPageServices->index($params);
            if(isset($data['InvalidPageName'])) {
                return $this->sendError(['message' => 'Invalid Page Name']);
            }
            return $this->sendSuccess($data);
        } catch (Exception $e) {
            Log::error('Error - ' . print_r($e->getMessage(), true));
            return $this->sendError(['message' => 'Failed to List CMS Page'], 400);
        }
    }
}
