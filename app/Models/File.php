<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class File extends Model
{
    use \Illuminate\Database\Eloquent\Concerns\HasUuids;
    protected $fillable = ['name', 'path', 'type', 'page_id', 'news_id'];

    public function page()
    {
        return $this->belongsTo(Page::class);
    }

    public function news()
    {
        return $this->belongsTo(News::class);
    }

}
