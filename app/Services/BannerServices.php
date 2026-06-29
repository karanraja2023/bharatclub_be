<?php

namespace App\Services;

use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\Config;
use Illuminate\Support\Facades\Storage;
use Tymon\JWTAuth\Facades\JWTAuth;
use Illuminate\Http\Request;
use App\Models\Banner;

class BannerServices
{
    public const STATUS_ACTIVE = 1;

    /**
     * @var Banner
     */
    private Banner $banner;

    /**
     * BannerServices constructor
     * @param Banner $banner
     */
    public function __construct(
        Banner $banner
    ) 
    {
        $this->banner = $banner;
    }
    
    /**
     * Returns a list of banner.
     * 
     * @param array $request.
     * @return mixed Returns a list of banner.
     */
    public function getList(): mixed
    {
        return $this->banner
            ->where(function ($query) {
                $query->where('status', self::STATUS_ACTIVE);
            })
            ->select('id', 'title', 'small_text', 'file_name', 'file_type', 'file_url', 'sort_order', 'status')
            ->orderBy('sort_order', 'DESC')
            ->get();
    }
    
    public function getListid($request): mixed
    {        
        return $this->banner
            ->where(function ($query) {
                $query->where('status', self::STATUS_ACTIVE);
                
            })
            ->select('id', 'title', 'small_text', 'file_name', 'file_type', 'file_url','web_view_link')
             ->where('id', $request['id'])
            ->orderBy('sort_order', 'DESC')
            ->get();
    }

}