<?php

namespace App\Services;

use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\Config;
use Illuminate\Support\Facades\Storage;
use Tymon\JWTAuth\Facades\JWTAuth;
use Illuminate\Http\Request;
use App\Models\Gallery;

class GalleryServices
{
    public const STATUS_ACTIVE = 1;

    /**
     * @var Gallery
     */
    private Gallery $gallery;

    /**
     * GalleryServices constructor
     * @param Gallery $gallery
     */
    public function __construct(
        Gallery $gallery
    ) 
    {
        $this->gallery = $gallery;
    }
    
    /**
     * Returns a list of gallery.
     * 
     * @param array $request.
     * @return mixed Returns a list of gallery.
     */
    public function getList($request): mixed
    {
        $gallery = $this->gallery
            ->where(function ($query) {
                $query->where('status', self::STATUS_ACTIVE);
            })
            ->select('id', 'file_name', 'file_type', 'file_url', 'video_url', 'status')
            ->orderBy('id', 'DESC');

            if (isset($request['limit']) && !empty($request['limit'])) {
                $gallery = $gallery->take($request['limit']);
            }

        return $gallery->get();
    }
}