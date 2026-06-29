<?php

namespace App\Services;

use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\Config;
use Illuminate\Support\Facades\Storage;
use Tymon\JWTAuth\Facades\JWTAuth;
use Illuminate\Http\Request;
use App\Models\Sponsor;

class SponsorServices
{
    public const STATUS_ACTIVE = 1;

    /**
     * @var Sponsor
     */
    private Sponsor $sponsor;

    /**
     * sponsorServices constructor
     * @param Sponsor $sponsor
     */
    public function __construct(
        Sponsor $sponsor
    ) 
    {
        $this->sponsor = $sponsor;
    }
    
    /**
     * Returns a list of sponsor.
     * 
     * @param array $request.
     * @return mixed Returns a list of Sponsor.
     */
    public function getList(): mixed
    {
        return $this->sponsor
            ->where(function ($query) {
                $query->where('status', self::STATUS_ACTIVE);
            })
            ->select('id', 'company_name', 'website', 'file_name', 'file_type', 'file_url', 'status')
            ->orderBy('company_name','ASC')
            ->get();
    }

    public function getListid($request): mixed
    {        
        return $this->sponsor
            ->where(function ($query) {
                $query->where('status', self::STATUS_ACTIVE);
                
            })
            ->select('id', 'company_name', 'website', 'file_name', 'file_type', 'file_url')
             ->where('id', $request['id'])
            ->get();
    }
	
    public function getListeventid($request): mixed
    {        
        return $this->sponsor
            ->where(function ($query) {
                $query->where('status', self::STATUS_ACTIVE);
                
            })
            ->select('id', 'company_name', 'website', 'file_name', 'file_type', 'file_url')
             ->where('event_id', $request['event_id'])
            ->get();
    }

}