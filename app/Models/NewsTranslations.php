<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Concerns\HasUuids;
use Illuminate\Database\Eloquent\Model;

class NewsTranslations extends Model
{
    use HasUuids;

    protected $table = 'news_translations';

    protected $fillable = [
        'news_id',
        'locale',
        'title',
        'content',
        'html',
    ];

    public function news()
    {
        return $this->belongsTo(News::class, 'news_id', 'id');
    }
}
