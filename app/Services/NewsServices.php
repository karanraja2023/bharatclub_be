<?php

namespace App\Services;

use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\Config;
use Illuminate\Support\Facades\Storage;
use Tymon\JWTAuth\Facades\JWTAuth;
use Illuminate\Http\Request;
use App\Models\News;

class NewsServices
{
    public const STATUS_ACTIVE = 1;

    /**
     * @var News
     */
    private News $news;

    /**
     * NewsServices constructor
     * @param News $news
     */
    public function __construct(
        News $news
    ) 
    {
        $this->news = $news;
    }
    
    /**
     * Returns a list of banner.
     * 
     * @param array $request.
     * @return mixed Returns a list of banner.
     */
    public function getList($request): mixed
    {
        $news = $this->news
            ->with(['newsAttachments' => function ($query) {
                $query->select(['id', 'news_id', 'file_name', 'file_type', 'file_url']);
            }])
            ->where(function ($query) use ($request) {
                if (isset($request['keyword']) && !empty($request['keyword'])) {
                    $query->where('title', 'like', '%' . $request['keyword'] . '%');
                }
                $query->where('news.status', self::STATUS_ACTIVE);
            })
            ->select('news.id', 'news.title', 'news.description', 'news.start_date', 'news.end_date', 'news.status')
            ->distinct()
            ->orderBy('news.id', 'ASC');
        
        if (isset($request['limit']) && !empty($request['limit'])) {
            $news = $news->take($request['limit']);
        }

        return $news->get();
    }
}