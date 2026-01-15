<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Post extends Model
{
    protected $fillable = [
        'post_category_id',
        'title',
        'slug',
        'excerpt',
        'content',
        'status',
        'user_id'
    ];

    public function category()
    {
        return $this->belongsTo(PostCategory::class,'post_category_id');
    }

    public function author()
    {
        return $this->belongsTo(User::class,'user_id');
    }
}
