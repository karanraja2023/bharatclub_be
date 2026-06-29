<?php

namespace App\Http\Controllers\V1;

use Exception;
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Http\JsonResponse;
use Illuminate\Support\Facades\Log;
use App\Services\DeviceAccessServices;

class DeviceAccessController extends Controller
{
    /**
     * @var DeviceAccessServices
     */
    private DeviceAccessServices $deviceAccessServices;

    /**
     * DeviceAccessController constructor.
     *
     * @param DeviceAccessServices $deviceAccessServices
     */
    public function __construct(DeviceAccessServices $deviceAccessServices)
    {
        $this->deviceAccessServices = $deviceAccessServices;
    }

    /**
     * Verify device access based on device_id and access_name.
     *
     * @param Request $request
     * @return JsonResponse
     */
    public function verify(Request $request): JsonResponse
    {
        try {
            $params = $this->getRequest($request);

            $response = $this->deviceAccessServices->verify($params);

            if (isset($response['error'])) {
                return $this->validationError($response['error']);
            }

            if (!$response) {
                return $this->sendError(['message' => 'Access Denied'], 403);
            }

            $data = [
                'error' => false,
                'response' => $response,
                'message' => 'Access Granted'
            ];

            return $this->sendSuccess($data);
        } catch (Exception $e) {
            Log::error('Error verifying device access - ' . $e->getMessage());

            return $this->sendError(['message' => 'Failed to verify device access'], 400);
        }
    }
}

