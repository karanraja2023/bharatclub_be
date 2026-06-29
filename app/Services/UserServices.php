<?php

namespace App\Services;

use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\Config;
use Illuminate\Support\Facades\DB;
use Tymon\JWTAuth\Facades\JWTAuth;
use Illuminate\Http\Request;
use App\Http\Controllers\V1\AuthController;
use App\Models\User;
use App\Models\UserAttachments;

class UserServices
{   
    public const USER_STATUS_ACTIVE = 1;

    /**
     * @var Storage
     */
    private Storage $storage;

    /**
     * @var AuthController
     */
    private $authController;

    /**
     * @var User
     */
    private User $user;

    /**
     * @var UserAttachments
     */
    private UserAttachments $userAttachments;

    /**
     * UserServices constructor
     * @param Storage $storage
     * @param AuthController $authController
     * @param User $user
     * @param UserAttachments $userAttachments
     */
    public function __construct(
        Storage $storage,
        AuthController $authController,
        User $user,
        UserAttachments $userAttachments,
    ) 
    {
        $this->storage = $storage;
        $this->authController = $authController;
        $this->user = $user;
        $this->userAttachments = $userAttachments;
    }

    /**
     * @return array
     */
    public function updateValidation($request): array
    {
        $id = $request['id'];

        return [
            'name' => 'required',
            'mobile' => 'required',
            'email' => 'required|email|unique:user,email,'.$id.',id,deleted_at,NULL'
        ];
    }
    
    /**
     * @return array
     */
    public function uploadProfileImageValidation(): array
    {
        return [
            'attachment' => 'required',
        ];
    }

    /**
     * @param $request
     * @return mixed
     */
    public function register($request): mixed
    {
        $user = $this->showUserEmail($request);
        if (is_null($user)) {
            $user = $this->createGoogleLogin($request);
        }
        else
        {
            $this->createNormalLogin($user, $request);
        }

        $this->updateProfileImage($user, $request);

        return true;
    }

    private function showUserEmail($request)
    {
        return $this->user
            ->where('email', $request['email'])->first();
    }

    private function createGoogleLogin($request)
    {
        return $this->user->create([
            'name' => $request['name'] ?? '',
            'mobile' => $request['mobile'] ?? '',
            'email' => $request['email'],
            'password' => Config::get('services.USER_PASSWORD'),
            'user_type' => Config::get('services.USER_ROLE'),
            'login_type' => $request['type'] ?? '',
            'status' => self::USER_STATUS_ACTIVE
        ]);
    }

    private function createNormalLogin($user, $request)
    {
        $user->login_type = $request['type'] ?? $user->login_type;
        $user->created_by = $user->id;
        $user->modified_by = $user->id;
        $user->save();
    }

    private function updateProfileImage($user, $request)
    {
        if (!empty($request['profile'])) {

            $this->userAttachments->updateOrCreate(
                [
                    "user_id" => $user['id']
                ],
                [
                "file_type" => 'Profile',
                "file_url" =>  $request['profile'],
                'created_by' => $user['id'],
                'modified_by' => $user['id'],
            ]);

        }
    }
    
    /**
     * @param $request
     * @return mixed
     */
    public function login($request): mixed
    {
        $credentials = [
            'email' => $request['email'],
            'password' => $request['password'],
            'device_id' => $request['device_id'] ?? null,
        ];

        $loginRequest = new Request($credentials);
        return $this->authController->login($loginRequest);
    }

   
    /**
     * @param $request
     * @return mixed
     */
    public function changepassword($request): mixed
    {
        $credentials = [
            'oldpassword' => $request['oldpassword'],
            'newpassword' => $request['newpassword']
        ];

        $passwordRequest = new Request($credentials);        

        return $this->authController->updatepassword($passwordRequest);
    }
    
    /**
     * @param $request
     * @return mixed
     */
    public function update($request): mixed
    {
        $user = $this->show($request['id']);
        $user->name = $request['name'] ?? $user->name;
        $user->mobile = $request['mobile'] ?? $user->mobile;
        $user->email = $request['email'] ?? $user->email;
        $user->organization = $request['organization'] ?? $user->organization;
        $user->first_name = $request['first_name'] ?? $user->first_name;
        $user->last_name = $request['last_name'] ?? $user->last_name;
        $user->poc_first_name = $request['poc_first_name'] ?? $user->poc_first_name;
        $user->poc_last_name = $request['poc_last_name'] ?? $user->poc_last_name;
        $user->poc_email = $request['poc_email'] ?? $user->poc_email;
        $user->poc_mobile = $request['poc_mobile'] ?? $user->poc_mobile;
        $user->company_name = $request['company_name'] ?? $user->company_name;
        $user->company_website = $request['company_website'] ?? $user->company_website;
        $user->designation = $request['designation'] ?? $user->designation;
        $user->location = $request['location'] ?? $user->location;
        $user->linkedin_url = $request['linkedin_url'] ?? $user->linkedin_url;
        $user->child_one = $request['child_one'] ?? $user->child_one;
        $user->child_two = $request['child_two'] ?? $user->child_two;
        $user->child_three = $request['child_three'] ?? $user->child_three;
        $user->child_four = $request['child_four'] ?? $user->child_four;
        $user->created_by = $request['id'];
        $user->modified_by = $request['id'];
        $user->save();

       // $this->updateProfileImage($user, $request);

        return true;
    }
    
    /**
     * @param $request
     * @return bool|array
     */
    public function uploadProfileImage($request): bool|array
    {
        $user = $this->show($request['id']);
        if (request()->hasFile('attachment')) {
            $file = $request->file('attachment');
            $fileName = $file->getClientOriginalName();                 
            $filePath = '/club_portal/user/profile/'. $fileName; 
            $linode = $this->storage::disk('linode');
            $linode->put($filePath, file_get_contents($file));
            $fileUrl = $this->storage::disk('linode')->url($filePath);

            $this->userAttachments->updateOrCreate(
                [
                    "user_id" => $user['id']
                ],
                [
                "file_name" => $fileName,
                "file_type" => 'Profile',
                "file_url" =>  $fileUrl,
                'modified_by' => $user['id'] ?? 0
            ]);
        }
        else
        {
            return [
                'error' => 'The attachment is required'
            ];
        } 

        return true;
    }

    /**
     * Returns a paginated list of member.
     * 
     * @param array $request.
     * @return mixed Returns a paginated list of member.
     */
    public function memberList($request): mixed
    {
        return $this->user
            ->with(['userAttachments' => function ($query) {
                $query->select(['id', 'user_id', 'file_name', 'file_type', 'file_url']);
            }])
            ->whereNotIn('user.id', [$request['id']])
            ->select('user.id', 'user.name', 'user.mobile', 'user.email', 'user.organization', 'user.user_type', 'user.login_type', 'user.status', 'user.first_name', 'user.last_name', 'user.poc_first_name', 'user.poc_last_name', 'user.poc_email', 'user.poc_mobile', 'user.company_name', 'user.company_website', 'user.designation', 'user.location', 'user.linkedin_url', 'user.join_member_ship', 'user.login_status',  'user.membership_type',  'user.approval_status',  'user.payment_date',  'user.payment_reference_number',  'user.membership_id',  'user.child_one',  'user.child_two',  'user.child_three',  'user.child_four',  'user.membership_type_id')
            ->distinct()
            ->orderBy('user.id','DESC')
            // ->paginate(Config::get('services.PAGINATE_ROW'))
	    ->get(); 
    }
    
    /**
     * @param $id
     * @return mixed
     */
    public function show($id): mixed
    {
        return $this->user
            ->with(['userAttachments' => function ($query) {
                $query->select(['id', 'user_id', 'file_name', 'file_type', 'file_url']);
            }])
            ->where('id', $id)
            ->select('user.id', 'user.name', 'user.mobile', 'user.email', 'user.organization', 'user.user_type', 'user.login_type', 'user.status', 'user.first_name', 'user.last_name', 'user.poc_first_name', 'user.poc_last_name', 'user.poc_email', 'user.poc_mobile', 'user.company_name', 'user.company_website', 'user.designation', 'user.location', 'user.linkedin_url',  'user.join_member_ship',  'user.is_basicdetails',  'user.is_poc',  'user.is_profile_info',  'user.membership_type',  'user.membership_start_date',  'user.membership_end_date',  'user.approval_status',  'user.payment_date',  'user.payment_reference_number',  'user.membership_id',  'user.child_one',  'user.child_two',  'user.child_three',  'user.child_four',  'user.admin_flag',  'user.membership_type_id')
            ->first();
    }

    /**
     * Validate the given request data.
     *
     * @param array $request The request data to be validated.
     * @return array|bool Returns an array with 'error' as key and validation error messages as value if validation fails. | Returns true if validation passes.
     */
    private function updateValidateRequest($request): array|bool
    {
        $validator = Validator::make($request, $this->updateValidation($request));
        if ($validator->fails()) {
            return [
                'error' => $validator->errors()
            ];
        }

        return true;
    }
}
