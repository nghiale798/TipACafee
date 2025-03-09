<?php

namespace App\Http\Controllers\Admin;

use App\Constants\Status;
use App\Http\Controllers\Controller;
use App\Models\Donation;
use App\Models\Membership;

class DonationHistoryController extends Controller
{
    public function index()
    {
        $pageTitle      = 'Donation History';
        $query          = Donation::searchable(['user:username', 'supporter:username', 'member:username'])->dateFilter('created_at')->where('status', Status::DONATION_SUCCESS)->with('user', 'supporter', 'member');
        $filterDonation = request()->donation;

        if ($filterDonation) {
            $query = ($filterDonation == 1)
                ? $query->where('supporter_id', '!=', 0)
                : $query->where('member_id', '!=', 0);
        }
        $donations = $query->paginate(getPaginate());
        return view('admin.donation.index', compact('pageTitle', 'donations'));
    }

    public function membership()
    {
        $pageTitle = 'Membership History';
        $memberships = Membership::active()->searchable(['user:username', 'member:username'])->dateFilter('created_at')->with('user', 'member', 'level')->paginate(getPaginate());
        return view('admin.donation.membership', compact('pageTitle', 'memberships'));
    }
}
