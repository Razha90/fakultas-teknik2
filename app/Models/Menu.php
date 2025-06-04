<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Menu extends Model
{
    use \Illuminate\Database\Eloquent\Concerns\HasUuids;

    protected $fillable = [
        'user_id',
        'name',
        'position',
        'isActive',
        'path',
    ];

    protected $table = 'menus';

    public function pages()
    {
        return $this->hasMany(Page::class, 'menu_id', 'id');
    }

    public function user()
    {
        return $this->belongsTo(User::class, 'user_id', 'id');
    }
}
