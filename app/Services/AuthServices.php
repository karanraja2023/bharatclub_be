<?php


namespace App\Services;

use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\DB;
use App\Models\User;
use Illuminate\Http\Request;
use App\Services\EmailServices;
use Illuminate\Support\Facades\Config;

class AuthServices extends Controller
{
     private EmailServices $emailServices;
    /**
     * @var User
     */
    private $user;

    /**
     * @var Request
     */
    private $request;

    /**
     * AuthServices constructor.
     * @param User $user
     * @param Request $request
     */
    public function __construct(User $user, EmailServices $emailServices, Request $request)
    {
        $this->user = $user;
	$this->emailServices = $emailServices;
        $this->request = $request;
    }

    /**
     * @return array
     */
    public function loginValidation(): array
    {
        return [
            'email' => 'required',
            'password' => 'required|string',
            'device_id' => 'nullable|string',
        ];
    }
    
    /**
     * @return array
     */
    public function passwordValidation(): array
    {
        return [
            'oldpassword' => 'required',
            'newpassword' => 'required',
        ];
    }	
   /**
     * @return array
     */
    public function forgotPasswordValidation(): array
    {
        return [           
            'email' => 'required|email',
        ];
    }

    /**
     * @return array
     */
    public function registerValidation(): array
    {
        return [
            'type' => 'required',
            'name' => 'required',
            'email' => 'required|email|max:150|unique:users',
            'password' => 'required',
        ];
    }

/**
     * @param $request
     * @return bool
     */
    public function forgotPassword($request)
    {
        $data = $this->user->where('email', $request['email'])->first(['id', 'name']);
        if (isset($data->id)) {
            // $token = Hash::make(rand(100000, 999999));
            // $this->passwordResets->create([
            //     'email' => $request['email'],
            //     'token' => $token
            // ]);
	    $startdate=strtotime("Now");
            $enddate=strtotime("24 hours", $startdate); 

            $user = $this->userShow($data->id);
            $user->reset_password_valid_until=$enddate;
            $user->save();

            $params = [
		'name' => $data->name,
                'email' => $request['email'],
                // 'token' => $token,
                'url' => Config::get('services.FRONTEND_URL')
            ];
            $this->emailServices->sendForgotPasswordMail($params);
            return true;
        } else {
            return false;
        }
    }

     /**
     * @param $request
     * @return bool
     */
    public function accountDeletion($request)
    {
        $data = $this->user->where('email', $request['email'])->first(['id', 'name']);
        if (isset($data->id)) {            
            $user = $this->userShow($data->id);
            $user->status=0;
            $user->save();
           
            return true;
        } else {
            return false;
        }
    }



    /**
     * @param $id
     * @return mixed
     */
    public function userShow($id): mixed
    {
        return $this->user
            ->with(['userAttachments' => function ($query) {
                $query->select(['id', 'user_id', 'file_name', 'file_type', 'file_url']);
            }])
            ->where('id', $id)
            ->select('user.id', 'user.name', 'user.mobile', 'user.email', 'user.organization', 'user.user_type', 'user.login_type', 'user.status', 'user.first_name', 'user.last_name', 'user.poc_first_name', 'user.poc_last_name', 'user.poc_email', 'user.poc_mobile', 'user.company_name', 'user.company_website', 'user.designation', 'user.location', 'user.linkedin_url', 'user.join_member_ship', 'user.login_status',  'user.membership_type',  'user.approval_status',  'user.payment_date',  'user.payment_reference_number','user.membership_id','user.admin_flag', 'user.membership_type_id')
            ->first();
    }
}
