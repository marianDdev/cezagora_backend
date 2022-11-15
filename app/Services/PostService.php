<?php

namespace App\Services;

use App\Models\Post;

class PostService
{
    public function getOneBy(string $column, $value)
    {
        return Post::where($column, $value)->first();
    }
}
