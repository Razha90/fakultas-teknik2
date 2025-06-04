<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Concerns\HasUuids;


class Category_news extends Model
{
    use HasUuids;
    
    protected $table = 'category_news';
    protected $fillable = [
        'id',
        'news_id',
        'category_id',
    ];

    public function news()
    {
        return $this->belongsTo(News::class, 'news_id');
    }
    public function category()
    {
        return $this->belongsTo(Categories::class, 'category_id');
    }
}
