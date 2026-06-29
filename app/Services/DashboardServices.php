<?php

namespace App\Services;

use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\Config;
use Illuminate\Support\Facades\Storage;
use Tymon\JWTAuth\Facades\JWTAuth;
use Illuminate\Http\Request;
use App\Services\BannerServices;
use App\Services\EventServices;
use App\Services\NewsServices;
use App\Services\GalleryServices;

class DashboardServices
{
    /**
     * @var BannerServices
     */
    private BannerServices $bannerServices;

    /**
     * @var EventServices
     */
    private EventServices $eventServices;

    /**
     * @var NewsServices
     */
    private NewsServices $newsServices;

    /**
     * @var GalleryServices
     */
    private GalleryServices $galleryServices;

    /**
     * DashboardServices constructor
     * @param BannerServices $bannerServices
     * @param EventServices $eventServices
     * @param NewsServices $newsServices
     * @param GalleryServices $galleryServices
     */
    public function __construct(
        BannerServices $bannerServices,
        EventServices $eventServices,
        NewsServices $newsServices,
        GalleryServices $galleryServices
    ) 
    {
        $this->bannerServices = $bannerServices;
        $this->eventServices = $eventServices;
        $this->newsServices = $newsServices;
        $this->galleryServices = $galleryServices;
    }
    
    /**
     * Returns a list of banner.
     * 
     * @param array $request.
     * @return mixed Returns a list of banner.
     */
    public function getList($request): mixed
    {
        $data['bannerList'] = $this->bannerServices->getList();
        $data['eventList'] = $this->eventServices->getList(['limit' => Config::get('services.DASHBOARD_LIMIT')]);
        $data['newsList'] = $this->newsServices->getList(['limit' => Config::get('services.DASHBOARD_LIMIT')]);
        $data['galleryList'] = $this->galleryServices->getList(['limit' => Config::get('services.DASHBOARD_LIMIT')]);
        return $data;
    }
}