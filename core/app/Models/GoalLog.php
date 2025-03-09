<?php

namespace App\Models;

use App\Traits\Searchable;
use Illuminate\Database\Eloquent\Model;


class GoalLog extends Model
{
    use Searchable;
    public function goal()
    {
        return $this->belongsTo(Goal::class);
    }
}
