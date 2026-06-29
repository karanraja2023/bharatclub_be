<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class CmsPage extends Model
{
    use SoftDeletes;

    /**
     * The table associated with the model.
     *
     * @var string
     */
    protected $table = 'cms_page';

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = ['id', 'page_name', 'title', 'content', 'status', 'created_by', 'modified_by'];

    /**
     * @return HasMany
     */
    public function cmsPageAttachments()
    {
        return $this->hasMany(CmsPageAttachments::class, 'cms_page_id');
    }
}
