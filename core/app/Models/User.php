<?php

namespace App\Models;

use App\Constants\Status;
use App\Traits\Searchable;
use App\Traits\UserNotify;
use Illuminate\Database\Eloquent\Casts\Attribute;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Laravel\Sanctum\HasApiTokens;

class User extends Authenticatable
{
    use HasApiTokens, Searchable, UserNotify;

    /**
     * The attributes that should be hidden for arrays.
     *
     * @var array
     */
    protected $hidden = [
        'password', 'remember_token', 'ver_code', 'balance', 'kyc_data'
    ];

    /**
     * The attributes that should be cast to native types.
     *
     * @var array
     */
    protected $casts = [
        'email_verified_at' => 'datetime',
        'address'           => 'object',
        'kyc_data'          => 'object',
        'ver_code_send_at'  => 'datetime',
        'social_links'      => 'object',
    ];

    protected $appends = ['image_path'];

    public function loginLogs()
    {
        return $this->hasMany(UserLogin::class);
    }

    public function transactions()
    {
        return $this->hasMany(Transaction::class)->orderBy('id', 'desc');
    }

    public function deposits()
    {
        return $this->hasMany(Deposit::class)->where('status', '!=', Status::PAYMENT_INITIATE);
    }

    public function goalLogs()
    {
        return $this->hasMany(GoalLog::class);
    }

    public function withdrawals()
    {
        return $this->hasMany(Withdrawal::class)->where('status', '!=', Status::PAYMENT_INITIATE);
    }

    public function donations()
    {
        return $this->hasMany(Donation::class)->where('status', Status::DONATION_SUCCESS)->orderBy('id', 'DESC');
    }
    public function donationSetting()
    {
        return $this->hasOne(DonationSetting::class);
    }
    public function supporter()
    {
        return $this->hasMany(Donation::class, 'supporter_id')->where('status', Status::PAYMENT_SUCCESS);
    }
    public function membership()
    {
        return $this->hasMany(Donation::class, 'member_id');
    }

    public function myMemberships()
    {
        return $this->hasMany(Membership::class, 'member_id');
    }

    public function memberships()
    {
        return $this->hasMany(Membership::class);
    }

    public function membershipLevels()
    {
        return $this->hasMany(MembershipLevel::class);
    }
    public function posts()
    {
        return $this->hasMany(Post::class);
    }
    public function galleries()
    {
        return $this->hasMany(Gallery::class);
    }

    public function membershipSetting()
    {
        return $this->hasOne(MembershipSetting::class);
    }

    public function tickets()
    {
        return $this->hasMany(SupportTicket::class);
    }


    public function following()
    {
        return $this->hasMany(Following::class, 'follower_id');
    }
    public function followers()
    {
        return $this->hasMany(Following::class, 'user_id');
    }


    public function fullname(): Attribute
    {
        return new Attribute(
            get: fn () => $this->firstname . ' ' . $this->lastname,
        );
    }

    public function getImagePathAttribute()
    {
        $path = $this->image ? getFilePath('userProfile') . '/' . $this->image : null;
        return avatar($path);
    }
    // SCOPES
    public function scopePageEnable($query)
    {
        return $query->where('account_disabled', Status::NO);
    }
    public function scopeActive($query)
    {
        return $query->where('status', Status::USER_ACTIVE)->where('ev', Status::VERIFIED)->where('sv', Status::VERIFIED);
    }

    public function scopeBanned($query)
    {
        return $query->where('status', Status::USER_BAN);
    }

    public function scopeEmailUnverified($query)
    {
        return $query->where('ev', Status::UNVERIFIED);
    }

    public function scopeMobileUnverified($query)
    {
        return $query->where('sv', Status::UNVERIFIED);
    }

    public function scopeKycUnverified($query)
    {
        return $query->where('kv', Status::KYC_UNVERIFIED);
    }

    public function scopeKycPending($query)
    {
        return $query->where('kv', Status::KYC_PENDING);
    }

    public function scopeEmailVerified($query)
    {
        return $query->where('ev', Status::VERIFIED);
    }

    public function scopeMobileVerified($query)
    {
        return $query->where('sv', Status::VERIFIED);
    }

    public function scopeWithBalance($query)
    {
        return $query->where('balance', '>', 0);
    }
}
