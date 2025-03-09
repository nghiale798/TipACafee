<?php

namespace App\Models;

use App\Traits\GlobalStatus;
use Illuminate\Database\Eloquent\Model;


class MembershipLevel extends Model
{

    use GlobalStatus;
    
    protected $casts = [
        'rewards' => 'object',
    ];

    public function memberships()
    {
        return $this->hasMany(Membership::class);
    }
}
