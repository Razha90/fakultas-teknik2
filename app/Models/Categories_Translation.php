<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Concerns\HasUuids;
use Illuminate\Database\Eloquent\Model;

class Categories_Translation extends Model
{
    use HasUuids;
    protected $table = 'categories_translation';
    protected $fillable = [
        'name',
        'locale',
        'category_id',
    ];
    public function category()
    {
        return $this->belongsTo(Categories::class, 'category_id', 'id');
    }
}
