<?php

namespace App\Http\Controllers\V1;

use App\Http\Controllers\Controller;
use Illuminate\Contracts\Auth\Guard;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\Hash;	
use Illuminate\Support\Facades\Config;
use Tymon\JWTAuth\Exceptions\JWTException;
use Tymon\JWTAuth\Facades\JWTAuth;
use App\Services\AuthServices;
use App\Models\User;
use App\Models\LoginDevice;


class AuthController extends Controller
{
    public const STATUS_ACTIVE = 1;

    /**
     * @var AuthServices
     */
    private AuthServices $authServices;
    private User $user;

    /**
     * AuthController constructor.
     * @param AuthServices $authServices
     */
    public function __construct(
        AuthServices $authServices,
	User $user
    )
    {
        $this->authServices = $authServices;
	$this->user = $user;
    }
    
    /**
     * Login a user.
     *
     * @param Request $request The login request object.
     *
     * @return JsonResponse The JSON response.
     */
    public function login(Request $request, string $guard = 'users')
    {
        $params = $this->getRequest($request);

        $credentials = [
            'email' => $params['email'] ?? null,
            'password' => $params['password'] ?? null,
        ];
        $loginData = $credentials;
        $loginData['device_id'] = $params['device_id'] ?? null;

        $validator = Validator::make($loginData, $this->authServices->loginValidation());
        if ($validator->fails()) {
            return $this->validationError($validator->errors());
        }

        if (!$token = Auth::guard($guard)->attempt($credentials)) {
            return $this->sendError(['message' => 'Invalid Credentials'], 400);
        }

        $user = Auth::guard($guard)->user();
        if (is_null($user)) {
            return $this->sendError(['message' => 'User not found'], 400);
        }

        if ($user['status'] != self::STATUS_ACTIVE) {
            return $this->sendError(['message' => 'User not found'], 400);
        }

        $this->storeLoginDevice($user, $params['device_id'] ?? null);

        $user = $this->authServices->userShow($user['id']);
        return $this->respondWithToken($token, $user);
    }
	
    public function updatepassword(Request $request)
    {
        # Validation        

        $credentials['oldpassword'] = $request['oldpassword'];
        $credentials['newpassword'] = $request['newpassword'];

        $validator = Validator::make($credentials, $this->authServices->passwordValidation());
        // print_r($validator);
        if ($validator->fails()) {

            return $this->validationError($validator->errors());
        }

        #Match The Old Password
        if(!Hash::check($request->oldpassword, auth()->user()->password)){
            return $this->sendError(['error' => 'Old Password Does not match!'], 400);
        }        

        #Update the new Password
        $user = $this->user->find(auth()->user()->id);
        $user->password = $request['newpassword'];
        $user->login_status = 1;
        $user->save();

        if (!is_null($user)) {
        // $usr = Auth::guard('user')->user();            
        $user = $this->authServices->userShow(auth()->user()->id);
        return $user;
        }
        // return back()->with("status", "Password changed successfully!");
     }

     /**
     * Forgot Password
     * @param Request $request
     * @return JsonResponse
     */
    public function forgotPassword(Request $request)
    {

        $credentials = $this->getRequest($request);
        // print_r($credentials);

        $validator = Validator::make($credentials, $this->authServices->forgotPasswordValidation());
        // print_r($validator);


        if ($validator->fails()) {
            return $this->validationError($validator->errors());
        }
        $response = $this->authServices->forgotPassword($request);
       
        
        if($response == true) {
            return $this->sendSuccess(['message' => 'Password reset link has been sent to your registered email']);
        } else {
            return $this->sendError(['message' => 'Email was not found'], 400);
        }
    }
	
    /**
     * Account Deletion
     * @param Request $request
     * @return JsonResponse
     */
    public function accountDeletion(Request $request)
    {

        $credentials = $this->getRequest($request);
        // print_r($credentials);

        $validator = Validator::make($credentials, $this->authServices->forgotPasswordValidation());
        // print_r($validator);


        if ($validator->fails()) {
            return $this->validationError($validator->errors());
        }
        $response = $this->authServices->accountDeletion($request);
       
        
        if($response == true) {
            return $this->sendSuccess(['message' => 'Successfully Account was deleted']);
        } else {
            return $this->sendError(['message' => 'Email was not found'], 400);
        }
    }


    private function storeLoginDevice($user, ?string $deviceId): void
    {
        $userId = is_array($user) ? ($user['id'] ?? null) : ($user->id ?? null);

        if (empty($userId) || empty($deviceId)) {
            return;
        }

        LoginDevice::firstOrCreate([
            'user_id' => $userId,
            'device_id' => $deviceId,
        ]);
    }
}
