<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Concerns\HasUuids;
use Illuminate\Support\Str;

class Category extends Model
{
    //
    use HasUuids;
    protected $table = 'categories_raffi';
    public $incrementing = false;
    protected $keyType = 'string';
    protected $fillable = ['name'];

    protected static function boot() {
        parent::boot();

        static::creating(function ($model) {
            if (empty($model->{$model->getKeyName()})) {
                $model->{$model->getKeyName()} = (string) Str::uuid();
            }
        });
    }

}
