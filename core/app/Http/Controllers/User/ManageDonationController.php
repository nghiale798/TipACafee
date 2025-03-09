<?php

namespace App\Http\Controllers\User;

use App\Http\Controllers\Controller;
use App\Models\Donation;
use App\Models\DonationSetting;
use Illuminate\Http\Request;

class ManageDonationController extends Controller
{
    public function index()
    {
        $pageTitle              = "Manage Donations";
        $query                  = Donation::success()->where('user_id', auth()->id())->with('member')->orderBy('id', 'desc');
        $countDonation          = (clone $query)->count();
        $lastThirtyDaysDonation = (clone $query)->where('created_at', '>', now()->subDays(30)->endOfDay())->sum('amount');
        $totalDonation          = (clone $query)->sum('amount');
        $donations              = (clone $query)->paginate(getPaginate());
        if (request()->doantion_filter == 'week') {
            $donations              = (clone $query)->where('created_at', '>=', now()->startOfWeek())->where('created_at', '<=', now()->endOfWeek())->paginate(getPaginate());
        } elseif (request()->doantion_filter == 'month') {
            $donations              = (clone $query)->where('created_at', '>=', now()->startOfMonth())->where('created_at', '<=', now()->endOfMonth())->paginate(getPaginate());
        } elseif (request()->doantion_filter == 'year') {
            $donations              = (clone $query)->where('created_at', '>=', now()->startOfYear())->where('created_at', '<=', now()->endOfYear())->paginate(getPaginate());
        }

        return view($this->activeTemplate . 'user.donation.index', compact('pageTitle', 'donations', 'countDonation', 'lastThirtyDaysDonation', 'totalDonation'));
    }

    public function setting()
    {
        $pageTitle = "Donation Setting";
        $user      = auth()->user();
        $donation  = $user->donationSetting;
        return view($this->activeTemplate . 'user.donation.setting', compact('pageTitle', 'donation', 'user'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'donation_price' => 'required|in:1,2,3,4,5,10,15',
            'thanks_message' => 'required|string',
            'cause_percent'  => 'required_if:is_donatio_cause,on',
            'institute'      => 'required_if:is_donatio_cause,on',
        ]);

        $user = auth()->user();
        $donation = $user->donationSetting;
        if (!$donation) {
            $donation = new DonationSetting;
        }

        $donation->user_id        = $user->id;
        $donation->donation_price = $request->donation_price;
        $donation->thanks_message = $request->thanks_message;
        $donation->cause_percent  = $request->is_donatio_cause ? $request->cause_percent : 0;
        $donation->institute      = $request->is_donatio_cause ? $request->institute : null;
        $donation->save();
        $notify[] = ['success', 'Donation setting updated successfully'];
        return back()->withNotify($notify);
    }
}
