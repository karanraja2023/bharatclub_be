<?php

namespace App\Services;

use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\Config;
use Illuminate\Support\Facades\Storage;
use Tymon\JWTAuth\Facades\JWTAuth;
use Illuminate\Http\Request;
use App\Models\Clubs;
use App\Models\User;
use App\Models\JoinClubsMember;

class JoinClubsServices
{
    public const STATUS_ACTIVE = 1;
    public const JOIN_CLUB_STATUS_ACTIVE = 1;

    /**
     * @var Clubs
     */
    private Clubs $clubs;
    private User $user;
    private JoinClubsMember $joinClubsMember;

    /**
     * ClubsServices constructor
     * @param Clubs $Clubs
     */
    public function __construct(
        Clubs $clubs,
        User $user,
        JoinClubsMember $joinClubsMember
    ) 
    {
        $this->clubs = $clubs;
        $this->user = $user;
        $this->joinClubsMember = $joinClubsMember;
    }
    

     /**
     * @return array
     */
    public function joinclubsValidation(): array
    {
        return [
            'primary_member_name' => 'required',
            'company_name' => 'required',
            'mobile_no' => 'required',
            'member_ic_passport_no' => 'required',
            'email_address' => 'required|email|max:150|unique:tbl_join_clubsmember',
            'residence_address_local' => 'required',
            'payment_toal_amount' => 'required',
            'payment_bankname' => 'required',
            'payment_date' => 'required',
            'payment_reference_number' => 'required',
            'payment_receipt_no' => 'required',           
        ];
    }

     /**
     * @param $request
     * @return mixed
     */
    public function submit($request): mixed
    {
        // $validationResult = $this->joinclubsValidation($request);
        // if (is_array($validationResult)) {
        //     return $validationResult;
        // }

         $this->joinClubsMember->create([
                'user_id' => $request['user_id'],
                'clubs_id' => $request['clubs_id'],
                'primary_member_name' => $request['primary_membername'],
                'company_name' => $request['companyname'],
                'mobile_no' => $request['mobile'],
                'member_ic_passport_no' => $request['member_icpassport'],
                'email_address' => $request['email'],
                'residence_address_local' => $request['residence_address'],
                'spouse_name' => $request['spousename'] ?? '',
                'spouse_mobile_no' => $request['spouse_mobile'] ?? '',
                'spouse_email' => $request['spouse_email'] ?? '',
                'spouse_ic_passport_no' => $request['spouse_icpassport'] ?? '',
                'spouse_children_name_age' => $request['spouse_children'] ?? '',
                'payment_toal_amount' => $request['amount'],
                'payment_bankname' => $request['bankname'],
                'payment_date' => $request['payment_date'],
                'payment_reference_number' => $request['reference_number'],
                'payment_receipt_name' => '',
                'payment_receipt_url' => '',
                'status' => self::JOIN_CLUB_STATUS_ACTIVE,
                'created_by' => $request['user_id'],
                'modified_by' => $request['user_id'],
        ]);

        $user = $this->user->find($request['user_id']);
        $user->join_member_ship = 'TRUE';
        $user->save();

         // return 'Your data has been successfully submitted';
         return true;
    }

    /**
     * Returns a list of Clubs.
     * 
     * @param array $request.
     * @return mixed Returns a list of Clubs.
     */



}