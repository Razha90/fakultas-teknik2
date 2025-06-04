<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Concerns\HasUlids;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Str;

class Content extends Model
{
    //

    use HasUlids;
    public $incrementing = false;
    protected $keyType = 'string';

    protected $fillable = [
        'id',
        'title',
        'slug',
        'description',
        'content_types_id',
        'categories_id',
        'users_id',
        'status',
        'image',
        'views',
        'published_at',
    ];


    protected static function booted() {
    static::creating(fn ($model) => $model->id = Str::uuid());
    }

        public function getRouteKeyName()
    {
        return 'id';
    }

    public function user() { return $this->belongsTo(User::class, 'users_id'); }
    public function type() { return $this->belongsTo(ContentType::class, 'content_types_id'); }
    public function categories() { return $this->belongsToMany(Category::class, 'category_content', 'content_id', 'category_id'); }

}
