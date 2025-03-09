<?php

namespace App\Models;

use App\Constants\Status;
use App\Traits\Searchable;
use Illuminate\Database\Eloquent\Model;

class Donation extends Model
{
    use Searchable;
    
    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function supporter()
    {
        return $this->belongsTo(User::class, 'supporter_id', 'id');
    }

    public function member()
    {
        return $this->belongsTo(User::class, 'member_id', 'id');
    }

    public function membership()
    {
        return $this->belongsTo(Membership::class);
    }

    public function levels()
    {
        return $this->belongsTo(MembershipLevel::class, 'membership_level_id', 'id');
    }

    public function deposit()
    {
        return $this->hasOne(Deposit::class);
    }

    public function memberDonation($query)
    {
        return $query->whereNotNull('member_id');
    }

    // SCOPES
    public function scopeSuccess($query)
    {
        return $query->where('status', Status::DONATION_SUCCESS);
    }
}
