<?php

namespace App\Services;

use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\Config;
use Illuminate\Support\Facades\Storage;
use Tymon\JWTAuth\Facades\JWTAuth;
use Illuminate\Http\Request;
use App\Models\MembershipType;

class MembershipTypeServices
{
    public const STATUS_ACTIVE = 1;

    /**
     * @var MembershipType
     */
    private MembershipType $membershipType;

    /**
     * MembershipTypeServices constructor
     * @param MembershipType $membershipType
     */
    public function __construct(
        MembershipType $membershipType
    ) 
    {
        $this->membershipType = $membershipType;
    }
    
    /**
     * Returns a list of membership type.
     * 
     * @param array $request.
     * @return mixed Returns a list of membership type.
     */
    public function getList(): mixed
    {
        return $this->membershipType
            ->where(function ($query) {
                $query->where('status', self::STATUS_ACTIVE);
            })
            ->select('id', 'name', 'amount', 'status')
            ->get();
    }
}