<?php

namespace App\Services;

use App\Models\DeviceAccess;
use Illuminate\Support\Facades\Validator;
use Illuminate\Database\Eloquent\Model;

class DeviceAccessServices
{
    /**
     * @var DeviceAccess
     */
    private DeviceAccess $deviceAccess;

    /**
     * DeviceAccessServices constructor.
     *
     * @param DeviceAccess $deviceAccess
     */
    public function __construct(DeviceAccess $deviceAccess)
    {
        $this->deviceAccess = $deviceAccess;
    }

    /**
     * Verify whether a device has access to a feature/resource.
     *
     * @param array $request
     * @return array|Model|null
     */
    public function verify(array $request)
    {
        $validator = Validator::make($request, [
            'device_id' => 'required',
            'access_name' => 'required',
        ]);

        if ($validator->fails()) {
            return ['error' => $validator->errors()];
        }

        return $this->deviceAccess
            ->where('device_id', $request['device_id'])
            ->where('access_name', $request['access_name'])
            ->first();
    }
}
