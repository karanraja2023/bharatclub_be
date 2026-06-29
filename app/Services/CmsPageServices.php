<?php

namespace App\Services;

use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\Config;
use Illuminate\Support\Facades\Storage;
use Tymon\JWTAuth\Facades\JWTAuth;
use Illuminate\Http\Request;
use App\Services\EventServices;
use App\Services\NewsServices;
use App\Services\GalleryServices;
use App\Services\ContactAddressServices;
use App\Services\MembershipTypeServices;
use App\Models\CmsPage;

class CmsPageServices
{
    public const STATUS_ACTIVE = 1;
    
    public const ERROR_INVALID_PAGE_NAME = ['InvalidPageName' => true];

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
     * @var ContactAddressServices
     */
    private ContactAddressServices $contactAddressServices;

    /**
     * @var MembershipTypeServices
     */
    private MembershipTypeServices $membershipTypeServices;

    /**
     * @var CmsPage
     */
    private CmsPage $cmsPage;

    /**
     * CmsPageServices constructor
     * @param EventServices $eventServices
     * @param NewsServices $newsServices
     * @param GalleryServices $galleryServices
     * @param ContactAddressServices $contactAddressServices
     * @param MembershipTypeServices $membershipTypeServices
     * @param CmsPage $cmsPage
     */
    public function __construct(
        EventServices $eventServices,
        NewsServices $newsServices,
        GalleryServices $galleryServices,
        ContactAddressServices $contactAddressServices,
        MembershipTypeServices $membershipTypeServices,
        CmsPage $cmsPage
    ) 
    {
        $this->eventServices = $eventServices;
        $this->newsServices = $newsServices;
        $this->galleryServices = $galleryServices;
        $this->contactAddressServices = $contactAddressServices;
        $this->membershipTypeServices = $membershipTypeServices;
        $this->cmsPage = $cmsPage;
    }
    
    /**
     * Returns a list of cms page with attachments.
     * 
     * @param array $request.
     * @return mixed Returns a list of cms page with attachments.
     */
    public function index($request): mixed
    {
        $cmsPage = $this->showCmsPage($request);
        if (is_null($cmsPage)) {
            return self::ERROR_INVALID_PAGE_NAME;
        }

        $cmsPage['module'] = $this->getModule($request);
        return $cmsPage;
    }

    public function showCmsPage($request): mixed
    {
        return $this->cmsPage
            ->with(['cmsPageAttachments' => function ($query) {
                $query->select(['id', 'cms_page_id', 'title', 'small_text', 'file_name', 'file_type', 'file_url']);
            }])
            ->where(function ($query) use ($request) {
                $query->where('cms_page.page_name', $request['page_name'])
                    ->where('cms_page.status', self::STATUS_ACTIVE);
            })
            ->select('cms_page.id', 'cms_page.page_name', 'cms_page.title', 'cms_page.content', 'cms_page.status')
            ->first();
    }

    public function getModule($request)
    {   
        $module = [];

        if ($request['page_name'] == 'EVENTS')
        {
            $module = $this->eventServices->getList($request);
        }
        else if ($request['page_name'] == 'NEWS')
        {
            $module = $this->newsServices->getList($request);
        }
        else if ($request['page_name'] == 'GALLERY')
        {
            $module = $this->galleryServices->getList($request);
        }
        else if ($request['page_name'] == 'CONTACT')
        {
            $module = $this->contactAddressServices->getList($request);
        }
        else if ($request['page_name'] == 'JOIN_CLUB')
        {
            $module = Config::get('services.JOIN_CLUB');
        }
        else if ($request['page_name'] == 'JOIN_CLUB_SUBMIT')
        {
            $module = $this->membershipTypeServices->getList($request);
        }

        return $module;
    }
}