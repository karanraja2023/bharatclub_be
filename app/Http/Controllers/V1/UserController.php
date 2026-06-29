<?php

namespace App\Http\Controllers\V1;

use Exception;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Config;
use Illuminate\Http\Request;
use Illuminate\Http\JsonResponse;
use Illuminate\Support\Facades\Log;
use Tymon\JWTAuth\Facades\JWTAuth;
use App\Services\UserServices;

class UserController extends Controller
{
    /**
     * @var UserServices
     */
    private UserServices $userServices;

    /**
     * UserController constructor
     * @param UserServices $userServices
     */
    public function __construct(
        UserServices $userServices
    )
    {
        $this->userServices = $userServices;
    }

    /**
     * Create a user
     * 
     * @param Request
     * @return JsonResponse
     */
    public function register(Request $request): JsonResponse
    {
        try {
            $params = $this->getRequest($request);
            $response = $this->userServices->register($params);
            if(isset($response['error'])) {
                return $this->validationError($response['error']);
            }
            return $this->userServices->login([
                'email' => $params['email'],
                'password' => Config::get('services.USER_PASSWORD'),
                'device_id' => $params['device_id'] ?? ($request->header('device-id') ?? 'registration-flow'),
            ]);
        } catch (Exception $e) {
            Log::error('Error - ' . print_r($e->getMessage(), true));
            return $this->sendError(['message' => 'Failed to create a User'], 400);
        }
    }
    
    /**
     * User Login
     * 
     * @param Request
     * @return JsonResponse
     */
    public function login(Request $request): JsonResponse
    {
        try {
            $params = $this->getRequest($request);
            return $this->userServices->login($params);
        } catch (Exception $e) {
            Log::error('Error - ' . print_r($e->getMessage(), true));
            return $this->sendError(['message' => 'Failed to login a User'], 400);
        }
    }

    
    /**
     * User Change Password
     * 
     * @param Request
     * @return JsonResponse
     */
    public function changepassword(Request $request): JsonResponse
    {
        try {
            $params = $this->getRequest($request);            
            $data['user'] = $this->userServices->changepassword($params);

            if(isset($data['error'])) {
                
                return $this->validationError($data['error']); 
            }
            // if(!isset($data['error'])){
    
                // $data['message'] = 'Password changed successfully!';
                return $this->sendSuccess($data);
            // }
            

        } catch (Exception $e) {
            Log::error('Error - ' . print_r($e->getMessage(), true));
            return $this->sendError(['message' => 'Failed to change a User Password'], 400);
        }
    }

    /**
     * Update a user
     * 
     * @param Request
     * @return JsonResponse
     */
    public function update(Request $request): JsonResponse
    {
        try {
            $user = JWTAuth::parseToken()->authenticate();
            $params = $this->getRequest($request);
            $params['id'] = $user['id'];
            $response = $this->userServices->update($params);
            if(isset($response['error'])) {
                return $this->validationError($response['error']);
            }
            return $this->sendSuccess(['message' => 'User Updated Successfully']);
        } catch (Exception $e) {
            Log::error('Error - ' . print_r($e->getMessage(), true));
            return $this->sendError(['message' => 'Failed to update a User'], 400);
        }
    }

    /**
     * Update a Profile Image
     * 
     * @param Request
     * @return JsonResponse
     */
    public function uploadProfileImage(Request $request): JsonResponse
    {
	Log::error('Error - ' . print_r($request, true));
        try {
            $user = JWTAuth::parseToken()->authenticate();
            $request['id'] = $user['id'];
            $response = $this->userServices->uploadProfileImage($request);
            if(isset($response['error'])) {
		Log::error('Error - ' . print_r($response['error'], true));
                return $this->validationError(['message' => $response['error']]);
		
            }
            return $this->sendSuccess(['message' => 'Profile uploaded successfully']);
        } catch (Exception $e) {
            Log::error('Error - ' . print_r($e->getMessage(), true));
            return $this->sendError(['message' => 'Failed to update a User'], 400);
        }
    }

    /**
     * Retrieve the User
     *
     * @param Request $request
     * @return JsonResponse
     */
    public function show(Request $request): JsonResponse
    {
        try {
            $user = JWTAuth::parseToken()->authenticate();
            $response = $this->userServices->show($user['id']);
            if(is_null($response) || count($response->toArray()) == 0){
                return $this->sendError(['message' => 'Unauthorized']);
            }
            return $this->sendSuccess($response);
        } catch (Exception $e) {
            Log::error('Error - ' . print_r($e->getMessage(), true));
            return $this->sendError(['message' => 'Failed to Display User'], 400);
        }
    }

    /**
     * member list
     *
     * @param Request $request
     * @return JsonResponse
     */
    public function memberList(Request $request): JsonResponse
    {
        try {
            $user = JWTAuth::parseToken()->authenticate();
            $params = $this->getRequest($request);
            $params['id'] = $user['id'];
            $data = $this->userServices->memberList($params);
            if(isset($data['error'])) {
                return $this->validationError($data['error']); 
            }
            return $this->sendSuccess($data);
        } catch (Exception $e) {
            Log::error('Error - ' . print_r($e->getMessage(), true));
            return $this->sendError(['message' => 'Failed to List Expense'], 400);
        }
    }
}
