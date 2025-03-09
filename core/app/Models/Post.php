<?php

namespace App\Models;

use App\Constants\Status;
use App\Traits\Searchable;
use Illuminate\Database\Eloquent\Model;

class Post extends Model
{
    use Searchable;

    public function user()
    {
        return $this->belongsTo(User::class);
    }
    public function likes()
    {
        return $this->hasMany(Like::class);
    }
    public function comments()
    {
        return $this->hasMany(Comment::class);
    }
    public function isLikedBy($user)
    {
        return $this->likes->contains('user_id', $user->id);
    }

    public function isCommentedBy($user)
    {
        return $this->comments->contains('user_id', $user->id);
    }

    //Scope
    public function scopePublished($query)
    {
        return $query->where('status', Status::PUBLISH);
    }
    public function scopeDrafted($query)
    {
        return $query->where('status', Status::DRAFT);
    }
    public function scopePublic($query)
    {
        return $query->where('visible', Status::VISIBLE_PUBLIC);
    }
    public function scopeSupporter($query)
    {
        return $query->where('visible', Status::VISIBLE_SUPPORTER);
    }
    public function scopeMember($query)
    {
        return $query->where('visible', Status::VISIBLE_MEMBER);
    }
}
