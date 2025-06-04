<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Concerns\HasUuids;
use Illuminate\Database\Eloquent\Model;

class Categories extends Model
{
    use HasUuids;
    
    protected $table = 'categories';

    protected $fillable = [
        'id',
    ];

    public function news()
    {
        return $this->belongsToMany(News::class, 'category_news', 'category_id', 'news_id');
    }

    public function translations()
    {
        return $this->hasMany(Categories_Translation::class, 'category_id', 'id');
    }

}
