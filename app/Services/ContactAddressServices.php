<?php

namespace App\Services;

use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\Config;
use Illuminate\Support\Facades\Storage;
use Tymon\JWTAuth\Facades\JWTAuth;
use Illuminate\Http\Request;
use App\Models\ContactAddress;

class ContactAddressServices
{
    public const STATUS_ACTIVE = 1;

    /**
     * @var ContactAddress
     */
    private ContactAddress $contactAddress;

    /**
     * ContactAddress constructor
     * @param ContactAddress $contactAddress
     */
    public function __construct(
        ContactAddress $contactAddress
    ) 
    {
        $this->contactAddress = $contactAddress;
    }
    
    /**
     * Returns a list of gallery.
     * 
     * @param array $request.
     * @return mixed Returns a list of gallery.
     */
    public function getList(): mixed
    {
        return $this->contactAddress
            ->where(function ($query) {
                $query->where('status', self::STATUS_ACTIVE);
            })
            ->select('id', 'name', 'latitude', 'longitude', 'location', 'primary_mobile', 'secondary_mobile', 'email', 'website', 'address', 'status')
            ->orderBy('id', 'ASC')
            ->get();
    }
}