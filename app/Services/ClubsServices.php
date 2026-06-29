<?php

namespace App\Services;

use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\Config;
use Illuminate\Support\Facades\Storage;
use Tymon\JWTAuth\Facades\JWTAuth;
use Illuminate\Http\Request;
use App\Models\Clubs;
use App\Models\ClubsAttachments;
use App\Models\ClubsMembership;

class ClubsServices
{
    public const STATUS_ACTIVE = 1;

    /**
     * @var Clubs
     */
    private Clubs $clubs;
    private ClubsAttachments $clubsAttachments;
    private ClubsMembership $clubsMembership;

    /**
     * ClubsServices constructor
     * @param Clubs $Clubs
     */
    public function __construct(
        Clubs $clubs,
        ClubsAttachments $clubsAttachments,
        ClubsMembership $clubsMembership
    ) 
    {
        $this->clubs = $clubs;
        $this->clubsAttachments = $clubsAttachments;
        $this->clubsMembership = $clubsMembership;
    }
    


    /**
     * Returns a list of Clubs.
     * 
     * @param array $request.
     * @return mixed Returns a list of Clubs.
     */

    public function getList($request): mixed
    {
        $clubs = $this->clubs
            ->where(function ($query) use ($request) {              
                $query->where('tbl_clubs.status', self::STATUS_ACTIVE);
            })
            ->select('tbl_clubs.id', 'tbl_clubs.club_title','tbl_clubs.description', 'tbl_clubs.short_description', 'tbl_clubs.address', 'tbl_clubs.pic_name', 'tbl_clubs.pic_title', 'tbl_clubs.phone', 'tbl_clubs.email', 'tbl_clubs.status')
            ->distinct()
            ->orderBy('tbl_clubs.id', 'ASC');
        
        if (isset($request['limit']) && !empty($request['limit'])) {
            $clubs = $clubs->take($request['limit']);
        }

        return $clubs->get();
    }

    public function getListAttachments($request): mixed
    {
        $clubs = $this->clubsAttachments           
            ->select('id', 'clubs_id', 'file_name', 'file_type', 'file_url')
            ->distinct()
            ->orderBy('id', 'ASC');
        
        if (isset($request['limit']) && !empty($request['limit'])) {
            $clubs = $clubs->take($request['limit']);
        }

        return $clubs->get();
    }

    public function getListMembership($request): mixed
    {
        $clubs = $this->clubsMembership           
            ->select('id', 'clubs_id', 'membership_package_title', 'membership_package_amount','validity', 'status')
            ->distinct()
            ->orderBy('id', 'ASC');
        
        if (isset($request['limit']) && !empty($request['limit'])) {
            $clubs = $clubs->take($request['limit']);
        }

        return $clubs->get();
    }

}