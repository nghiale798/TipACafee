<?php

namespace App\Models;

use App\Constants\Status;
use App\Traits\GlobalStatus;
use App\Traits\Searchable;
use Illuminate\Database\Eloquent\Model;

class Category extends Model
{
    use Searchable, GlobalStatus;

    public function posts()
    {
        return $this->hasMany(Post::class);
    }
}
