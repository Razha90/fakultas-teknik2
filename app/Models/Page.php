<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Concerns\HasUuids;
use Illuminate\Database\Eloquent\Model;

class Page extends Model
{
    use HasUuids;
    protected $fillable = ['user_id', 'html' ,'name', 'data', 'path', 'release', 'keywords', 'description', 'menu_id', 'isReleased'];

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function menu()
    {
        return $this->belongsTo(Menu::class, 'menu_id', 'id');
    }
    public function files()
    {
        return $this->hasMany(File::class);
    }
}
