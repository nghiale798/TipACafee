<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Following extends Model
{
    public function user()
    {
        return $this->belongsTo(User::class, 'user_id');
    }

    // Define the follower
    public function follower()
    {
        return $this->belongsTo(User::class, 'follower_id');
    }
}
