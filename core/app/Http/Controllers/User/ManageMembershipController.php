<?php

namespace App\Http\Controllers\User;

use App\Constants\Status;
use App\Http\Controllers\Controller;
use App\Models\Membership;
use App\Models\MembershipLevel;
use App\Models\MembershipSetting;
use App\Rules\FileTypeValidate;
use Illuminate\Http\Request;

class ManageMembershipController extends Controller
{
    public function index()
    {
        $pageTitle = "Manage Membership";
        $isMember  = true;
        $user      = auth()->user();

        if (!@$user->is_enable_membership) {
            return to_route('user.membership');
        }
        $countDonation     = Membership::active()->where('user_id', $user->id)->distinct('member_id')->count();
        $currentMonth      = now()->startOfMonth();
        $endOfCurrentMonth = now()->endOfMonth();
        $query             = $user->donations()
            ->where('member_id', '!=', Status::NOT_MEMBER_DONATION)
            ->whereBetween('created_at', [$currentMonth, $endOfCurrentMonth]);
        $filteredDonations = (clone $query)->get();

        $totalCount             = (clone $query)->count();
        $totalDonation          = (clone $query)->sum('amount');
        $lastThirtyDaysDonation = (clone $query)->sum('amount');
        $totalDonation          = $user->donations()->where('member_id', '!=', Status::NOT_MEMBER_DONATION)->sum('amount');
        $secondQuery            = Membership::active()->where('user_id', $user->id)->with('user', 'member', 'level')->orderBy('id', 'desc');
        $donations              = (clone $secondQuery)->paginate(getPaginate());

        if (request()->doantion_filter == 'week') {
            $donations              = (clone $secondQuery)->where('created_at', '>=', now()->startOfWeek())->where('created_at', '<=', now()->endOfWeek())->paginate(getPaginate());
        } elseif (request()->doantion_filter == 'month') {
            $donations              = (clone $secondQuery)->where('created_at', '>=', now()->startOfMonth())->where('created_at', '<=', now()->endOfMonth())->paginate(getPaginate());
        } elseif (request()->doantion_filter == 'year') {
            $donations              = (clone $secondQuery)->where('created_at', '>=', now()->startOfYear())->where('created_at', '<=', now()->endOfYear())->paginate(getPaginate());
        }

        return view($this->activeTemplate . 'user.membership.index', compact('pageTitle', 'donations', 'countDonation', 'lastThirtyDaysDonation', 'totalDonation', 'isMember'));
    }

    public function isEnable()
    {
        $user      = auth()->user();
        $user->is_enable_membership = Status::YES;
        $user->save();
        $notify[] = ['success', 'Membership activated successfully'];
        return to_route('user.membership.index')->withNotify($notify);
    }

    public function level()
    {
        $pageTitle = "Membership Level";
        $user      = auth()->user();

        if (!@$user->is_enable_membership) {
            return to_route('user.membership');
        }

        $donation = $user->donationSetting;
        $levels   = $user->membershipLevels;
        $levels->load('memberships');

        return view($this->activeTemplate . 'user.membership.level', compact('pageTitle', 'donation', 'levels'));
    }

    public function newLevel()
    {
        $pageTitle = "New Membership Level";
        return view($this->activeTemplate . 'user.membership.new_level', compact('pageTitle'));
    }
    public function editLevel($id)
    {
        $pageTitle = "Edit Membership Level";
        $level     = MembershipLevel::where('user_id', auth()->id())->findOrFail($id);
        return view($this->activeTemplate . 'user.membership.new_level', compact('pageTitle', 'level'));
    }

    public function levelStore(Request $request, $id = 0)
    {
        $request->validate([
            'level_name'    => 'required|string',
            'monthly_price' => 'required|numeric|gte:' . gs('monthly_level_amount'),
            'yearly_price'  => 'required|numeric|gt:monthly_price',
            'description'   => 'required|string',
            'level_image'   => ['nullable', new FileTypeValidate(['jpg', 'jpeg', 'png'])],
            'rewards.*'     => 'required|string',
            'welcome_msg'   => 'required|string',
        ],[
            'rewards.*'=>'Rewards field are required!'  
        ]);
        $user = auth()->user();

        if ($id) {
            $membership   = MembershipLevel::where('user_id', $user->id)->findOrFail($id);
            $notification = "Membership level updated successfully";
        } else {
            $membership          = new MembershipLevel();
            $membership->user_id = $user->id;
            $notification        = "Membership level created successfully";
        }

        if ($request->hasFile('level_image')) {
            try {
                $old               = $membership->image;
                $membership->image = fileUploader($request->level_image, getFilePath('membershipLevel'), getFileSize('membershipLevel'), $old);
            } catch (\Exception $exp) {
                $notify[] = ['error', 'Couldn\'t upload level image'];
                return back()->withNotify($notify);
            }
        }

        $membership->name          = $request->level_name;
        $membership->monthly_price = $request->monthly_price;
        $membership->yearly_price  = $request->yearly_price;
        $membership->description   = $request->description;

        $membership->rewards       = [
            'one'   => $request->rewards[0],
            'two'   => $request->rewards[1],
            'three' => $request->rewards[2],
        ];
        $membership->welcome_msg   = $request->welcome_msg;
        $membership->save();

        $notify[] = ['success', $notification];
        return back()->withNotify($notify);
    }

    public function membershipLevelStatus($id)
    {
        return MembershipLevel::changeStatus($id);
    }

    public function setting()
    {
        $pageTitle = "Membership Setting";
        $user      = auth()->user();

        if (!@$user->is_enable_membership) {
            return to_route('user.membership');
        }
        $donation = $user->donationSetting;

        $setting  = MembershipSetting::where('user_id', $user->id)->first();

        if (!$setting) {
            return to_route('user.membership');
        }
        return view($this->activeTemplate . 'user.membership.setting', compact('pageTitle', 'donation', 'setting', 'user'));
    }

    public function settingUpdate(Request $request, $id)
    {
        $request->validate([
            'welcome_message' => 'required|string'
        ]);

        $user    = auth()->user();
        $setting = MembershipSetting::where('user_id', $user->id)->findOrFail($id);

        $setting->accept_annual_membership = $request->accept_annual_membership ? Status::YES : Status::NO;
        $setting->is_show_count            = $request->is_show_count  ? Status::YES : Status::NO;
        $setting->is_show_earning          = $request->is_show_earning ? Status::YES : Status::NO;
        $setting->welcome_message          = $request->welcome_message;
        $setting->save();

        $notify[] = ['success', 'Membership setting updated successfully'];
        return back()->withNotify($notify);
    }

    public function membershipStatus()
    {
        $user = auth()->user();
        $user->is_enable_membership = Status::DISABLE;
        $user->save();
        $notify[] = ['success', 'Membership deactivated successfully'];
        return to_route('user.membership')->withNotify($notify);
    }

    public function myMembership()
    {
        $pageTitle = "Me as Member";
        $memberships = Membership::where('member_id', auth()->id())->where('status', Status::ENABLE)->with('user', 'level')->paginate(getPaginate());
        return view($this->activeTemplate . 'user.membership.my_membership', compact('pageTitle', 'memberships'));
    }

    public function status($id)
    {
        $membership     = Membership::where('member_id', auth()->id())->findOrFail($id);
        if ($membership->status == Status::ENABLE) {
            $membership->status = Status::DISABLE;
        } else {
            $membership->status = Status::ENABLE;
        }
        $membership->save();
        $notify[] = ['success', 'Status changed successfully'];
        return back()->withNotify($notify);
    }
}
