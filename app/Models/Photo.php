<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Photo extends Model
{
    use HasFactory;
    
    protected $hidden = ['pivot'];

    public function tags()
    {
        return $this->hasMany(PhotoTag::class);
    }

    public function likes()
    {
        return $this->belongsToMany(User::class, 'photo_likes', 'photo_id', 'user_id');
    }
}
