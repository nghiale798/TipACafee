<?php

namespace App\Models;

use App\Constants\Status;
use App\Traits\GlobalStatus;
use App\Traits\Searchable;
use Illuminate\Database\Eloquent\Casts\Attribute;
use Illuminate\Database\Eloquent\Model;


class Membership extends Model
{
    use Searchable, GlobalStatus;

    public function donations()
    {
        return $this->hasMany(Donation::class);
    }

    public function level()
    {
        return $this->belongsTo(MembershipLevel::class, 'membership_level_id');
    }
    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function member()
    {
        return $this->belongsTo(User::class, 'member_id');
    }

    public function typeBadge(): Attribute
    {
        return new Attribute(function () {
            $html = '';
            if ($this->duration_type == Status::MONTHLY_MEMBERSHIP) {
                $html = '<span class="badge badge--warning">' . trans('Monthly') . '</span>';
            } else {
                $html = '<span class="badge badge--primary">' . trans('Yearly') . '</span>';
            }
            return $html;
        });
    }
}
