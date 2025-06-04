<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Concerns\HasUuids;
use Illuminate\Database\Eloquent\Model;

class News extends Model
{
    use HasUuids;

    protected $table = 'news';
    protected $fillable = [
        'user_id',
        'image',
        'status',
        'views',
    ];

    public function user()
    {
        return $this->belongsTo(User::class, 'user_id');
    }

    public function categories()
    {
        return $this->belongsToMany(Categories::class, 'category_news', 'news_id', 'category_id');
    }

    public function translations()
    {
        return $this->hasMany(NewsTranslations::class, 'news_id', 'id');
    }
    public function files()
    {
        return $this->hasMany(File::class, 'news_id', 'id');
    }

}
