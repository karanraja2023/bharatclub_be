<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class News extends Model
{
    use SoftDeletes;

    /**
     * The table associated with the model.
     *
     * @var string
     */
    protected $table = 'news';

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = ['id', 'title', 'description', 'start_date', 'end_date', 'status', 'created_by', 'modified_by'];

    /**
     * @return HasMany
     */
    public function newsAttachments()
    {
        return $this->hasMany(NewsAttachments::class, 'news_id');
    }
}
