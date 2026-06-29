<?php

namespace App\Http\Controllers\V1;

use Exception;
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Http\JsonResponse;
use Illuminate\Support\Facades\Log;
use Tymon\JWTAuth\Facades\JWTAuth;
use App\Services\ContactServices;

class ContactController extends Controller
{
    /**
     * @var ContactServices
     */
    private ContactServices $contactServices;

    /**
     * ContactController constructor
     * @param ContactServices $contactServices
     */
    public function __construct(
        ContactServices $contactServices
    )
    {
        $this->contactServices = $contactServices;
    }

    /**
     * Contact Submit
     *
     * @param Request $request
     * @return JsonResponse
     */
    public function submit(Request $request): JsonResponse
    {
        try {
            $params = $this->getRequest($request);
            $data = $this->contactServices->submit($params);
            return $this->sendSuccess(['message' => "Your submission has been sent."]);
        } catch (Exception $e) {
            Log::error('Error - ' . print_r($e->getMessage(), true));
            return $this->sendError(['message' => 'Failed to Submit Contact'], 400);
        }
    }
}
