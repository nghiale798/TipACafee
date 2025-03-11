<?php

namespace App\Http\Controllers\Gateway;

use App\Constants\Status;
use App\Http\Controllers\Controller;
use App\Lib\DonationPayment;
use App\Lib\FormProcessor;
use App\Models\AdminNotification;
use App\Models\Deposit;
use App\Models\Donation;
use App\Models\GatewayCurrency;
use App\Models\GoalLog;
use App\Models\Membership;
use App\Models\MembershipLevel;
use App\Models\Transaction;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Validation\Rule;

class PaymentController extends Controller {
    public function payment(Request $request) {
        $request->validate([
            "quantity"            => "nullable|numeric|gt:0",
            "donor_identity"      => "nullable|string",
            "message"             => "nullable|string",
            "amount"              => "required|numeric|gt:0",
            "user_id"             => "required|exists:users,id",
            "goal_id"             => "nullable|exists:goals,id",
            "duration_type"       => "nullable|in:1,12",
            "membership_level_id" => ['nullable', Rule::exists('membership_levels', 'id')->where(function ($query) {
                $query->where('status', Status::ENABLE);
            })],
        ], [
            'membership_level_id' => 'Invalid membership level',
        ]);

        $auth = auth()->user();

        if ($request->user_id == @$auth?->id) {
            $notify[] = ['error', 'Inconsistency with support rules!'];
            return back()->withNotify($notify);
        }

        $donationType = @$request->duration_type;
        $levelId      = @$request->membership_level_id;
        $goalId       = @$request->goal_id;
        $takerUserId  = $request->user_id;

        if ($donationType && !$auth) {
            $notify[] = ['error', 'please login first!'];
            return to_route('user.login')->withNotify($notify);
        }

        if ($donationType && $levelId) {

            $existMember = Membership::active()->where('user_id', $takerUserId)
                ->where('member_id', $auth->id)
                ->where('membership_level_id', $levelId)
                ->whereIn('duration_type', [Status::MONTHLY_MEMBERSHIP, Status::YEARLY_MEMBERSHIP])
                ->get();

            $countOfTypeMonthly = $existMember->where('duration_type', Status::MONTHLY_MEMBERSHIP)->count();
            $countOfTypeYearly  = $existMember->where('duration_type', Status::YEARLY_MEMBERSHIP)->count();

            if ($countOfTypeMonthly > 0 && $countOfTypeYearly > 0) {
                $notify[] = ['error', 'You are already a member of this level! Choose another level.'];
                return back()->withNotify($notify);
            } else if ($countOfTypeMonthly > 0 && $request->duration_type == Status::MONTHLY_MEMBERSHIP) {
                $notify[] = ['error', 'Please join to yearly member for this level'];
                return back()->withNotify($notify);
            } else if ($countOfTypeYearly > 0 && $request->duration_type == Status::YEARLY_MEMBERSHIP) {
                $notify[] = ['error', 'Please join to monthly member for this level'];
                return back()->withNotify($notify);
            }

            //if-req-pending
            $pendingMemberLevel = Membership::where('user_id', $takerUserId)
                ->where('member_id', $auth->id)
                ->where('membership_level_id', $levelId)
                ->whereIn('duration_type', [Status::MONTHLY_MEMBERSHIP, Status::YEARLY_MEMBERSHIP])->where('status', Status::DISABLE)
                ->get();

            $countOfTypePendingMonthly = $pendingMemberLevel->where('duration_type', Status::MONTHLY_MEMBERSHIP)->count();
            $countOfTypePendingYearly  = $pendingMemberLevel->where('duration_type', Status::YEARLY_MEMBERSHIP)->count();
            $bothArePendingMembership  = $countOfTypePendingMonthly > 0 && $request->duration_type == Status::MONTHLY_MEMBERSHIP;
            $pendingReqMonthMember     = $countOfTypePendingMonthly > 0 && $request->duration_type == Status::MONTHLY_MEMBERSHIP;
            $pendingReqYearMember      = $countOfTypePendingYearly > 0 && $request->duration_type == Status::YEARLY_MEMBERSHIP;

            if ($bothArePendingMembership || $pendingReqMonthMember || $pendingReqYearMember) {
                $notify[] = ['error', 'Already requested for this level! wait for admin approval'];
                return back()->withNotify($notify);
            }
        }
        session()->forget('WELCOME_LINK');
        session()->forget('THANKS_LINK');
        session()->forget('THANKS_GOAL_LINK');
        session()->forget('DONATION_DATA');

        session()->put('DONATION_DATA', [
            'quantity'            => @$request->quantity ?? 0,
            'donor_identity'      => @$request->donor_identity,
            'message'             => @$request->message,
            'is_message_private'  => @$request->is_message_private == 'on' ? 1 : 0,
            'duration_type'       => @$donationType ?? 0,
            'membership_level_id' => @$levelId ?? 0,
            'goal_id'             => @$goalId ?? 0,
            'amount'              => $request->amount,
            'taker_user_id'       => $takerUserId,
        ]);

        session()->forget('WELCOME_LINK');
        session()->forget('THANKS_LINK');
        session()->forget('THANKS_GOAL_LINK');

        return to_route('payment.capture');

    }

    public function capture() {
        if (!session()->has('DONATION_DATA')) {
            return to_route('home');
        }
        $donationData = (object) session('DONATION_DATA');

        $takerUserId = $donationData->taker_user_id;
        $amount      = $donationData->amount;
        $user        = User::active()->where('id', $takerUserId)->firstOrFail();

        $gatewayCurrency = GatewayCurrency::whereHas('method', function ($gate) {
            $gate->where('status', Status::ENABLE);
        })->with('method')->orderby('method_code')->get();

        $pageTitle = 'Payment Method';

        if (auth()->check()) {
            $layout = "master";
        } else {
            $layout = "frontend";
        }
        return view($this->activeTemplate . 'user.payment.deposit', compact('gatewayCurrency', 'pageTitle', 'user', 'amount', 'layout'));
    }

    public function deposit(Request $request) {
        $gatewayCurrency = GatewayCurrency::whereHas('method', function ($gate) {
            $gate->where('status', Status::ENABLE);
        })->with('method')->orderby('method_code')->get();
        $pageTitle = 'Deposit Methods';
        $layout    = "master";
        return view($this->activeTemplate . 'user.payment.deposit', compact('gatewayCurrency', 'pageTitle', 'layout'));
    }

    public function depositInsert(Request $request) {
        $request->validate([
            'gateway'   => 'required',
            'currency'  => 'required',
            'user_id'   => 'required',
            'amount'    => 'required|numeric|gt:0',
            'is_donate' => 'nullable|in:1',
        ]);

        $donationData = (object) session('DONATION_DATA');
        if (isset($donationData->amount) && $donationData->amount > $request->amount) {
            $notify[] = ['error', 'Your intended amount doesn\'t match'];
            return back()->withNotify($notify);
        }
        if (isset($donationData->membership_level_id) && $donationData->membership_level_id) {
            $levelCheck = MembershipLevel::active()->where('id', $donationData->membership_level_id)->first();
            if ($levelCheck) {
                if ($donationData->duration_type == Status::YEARLY_MEMBERSHIP && $levelCheck->yearly_price != $request->amount) {
                    $notify[] = ['error', 'Invalid yearly level amount'];
                    return back()->withNotify($notify);
                } else if ($donationData->duration_type == Status::MONTHLY_MEMBERSHIP && $levelCheck->monthly_price != $request->amount) {
                    $notify[] = ['error', 'Invalid monthly level amount'];
                    return back()->withNotify($notify);
                }
            }
        }

        $user = User::active()->find($request->user_id);

        if (!$user) {
            $notify[] = ['error', 'Recipient is not found!'];
            return back()->withNotify($notify);
        }

        if ($donationData && $request->gateway == 'deposit_wallet') {
            $reqAmount = $request->amount;
            $donor     = auth()->user();
            if ($reqAmount > $donor->balance) {
                $notify[] = ['error', 'Your balance is not sufficient'];
                return back()->withNotify($notify);
            }
            $directPayment = new DonationPayment($user);
            $directPayment->donate($reqAmount, $donor, $donationData);

            $notify[] = ['success', 'Payment captured successfully'];
            return to_route('home.page', $user->profile_link)->withNotify($notify);
        }

        $gate = GatewayCurrency::whereHas('method', function ($gate) {
            $gate->where('status', Status::ENABLE);
        })->where('method_code', $request->gateway)->where('currency', $request->currency)->first();

        if (!$gate) {
            $notify[] = ['error', 'Invalid gateway'];
            return back()->withNotify($notify);
        }

        if ($gate->min_amount > $request->amount || $gate->max_amount < $request->amount) {
            $notify[] = ['error', 'Please follow deposit limit'];
            return back()->withNotify($notify);
        }

        if ($request->is_donate) {

            $donationData = (object) session('DONATION_DATA');

            $donor            = @auth()->user();
            $donorIdentity    = $donationData->donor_identity;
            $message          = $donationData->message;
            $isMessagePrivate = $donationData->is_message_private;
            $quantity         = $donationData->quantity;
            $donationType     = $donationData->duration_type;
            $levelId          = $donationData->membership_level_id;
            $goalId           = $donationData->goal_id;

            if (!$goalId) {
                if ($donationType && $levelId) { //membership
                    $membership                      = new Membership();
                    $membership->user_id             = $user->id;
                    $membership->member_id           = $donor->id;
                    $membership->membership_level_id = $levelId;
                    $membership->amount              = $request->amount;
                    $membership->duration_type       = $donationType;
                    $membership->next_date           = $donationType == Status::MONTHLY_MEMBERSHIP ? now()->addMonth() : now()->addYear();
                    $membership->save();
                }

                $donation                     = new Donation();
                $donation->amount             = @$request->amount;
                $donation->user_id            = @$user->id;
                $donation->message            = @$message;
                $donation->is_message_private = @$isMessagePrivate;

                if (!$donationType && !$levelId) { //supporter
                    $donation->quantity       = $quantity ?? 1;
                    $donation->donor_identity = $donorIdentity;
                    $donation->supporter_id   = $donor?->id ?? 0;
                }
                if ($donationType && $levelId) { //membership
                    $donation->member_id     = $donor->id;
                    $donation->membership_id = $membership->id;
                }

                $donation->save();
            }
        }

        $charge      = $gate->fixed_charge + ($request->amount * $gate->percent_charge / 100);
        $payable     = $request->amount + $charge;
        $finalAmount = $payable * $gate->rate;

        $data                  = new Deposit();
        $data->user_id         = $user->id;
        $data->donation_id     = $donation->id ?? 0;
        $data->goal_id         = $goalId ?? 0;
        $data->method_code     = $gate->method_code;
        $data->method_currency = strtoupper($gate->currency);
        $data->amount          = $request->amount;
        $data->charge          = $charge;
        $data->rate            = $gate->rate;
        $data->final_amount    = $finalAmount;
        $data->btc_amount      = 0;
        $data->btc_wallet      = "";
        $data->trx             = getTrx();

        if ($request->is_donate) {
            if ($goalId) {
                $data->donation_sign = Status::GOAL_GIFT;
            } else {
                $data->donation_sign = ($donationType && $levelId) ? Status::MEMBERSHIP : Status::DONATION;
            }
        }
        $data->save();

        session()->put('Track', $data->trx);
        return to_route('payment.confirm');
    }

    public function depositConfirm() {
        $track   = session()->get('Track');
        $deposit = Deposit::where('trx', $track)->where('status', Status::PAYMENT_INITIATE)->orderBy('id', 'DESC')->with('gateway')->firstOrFail();

        if ($deposit->method_code >= 1000) {
            return to_route('payment.manual.confirm');
        }

        $dirName = $deposit->gateway->alias;
        $new     = __NAMESPACE__ . '\\' . $dirName . '\\ProcessController';

        $data = $new::process($deposit);
        $data = json_decode($data);

        if (isset($data->error)) {
            $notify[] = ['error', $data->message];
            return to_route(gatewayRedirectUrl())->withNotify($notify);
        }
        if (isset($data->redirect)) {
            return redirect($data->redirect_url);
        }

        // for Stripe V3
        if (@$data->session) {
            $deposit->btc_wallet = $data->session->id;
            $deposit->save();
        }

        $pageTitle = 'Payment Confirm';
        $layout    = "master";

        if (!auth()->check()) {
            $layout = "frontend";
        }

        return view($this->activeTemplate . $data->view, compact('data', 'pageTitle', 'deposit', 'layout'));
    }

    public static function userDataUpdate($deposit, $isManual = null) {
        if ($deposit->status == Status::PAYMENT_INITIATE || $deposit->status == Status::PAYMENT_PENDING) {

            $deposit->status = Status::PAYMENT_SUCCESS;
            $deposit->save();

            $authUser = auth()->user();

            if (($deposit->donation_id || $deposit->goal_id) && auth()->check()) {

                $authUser->balance += $deposit->amount;
                $authUser->save();

                $transaction               = new Transaction();
                $transaction->user_id      = $authUser->id;
                $transaction->amount       = $deposit->amount;
                $transaction->post_balance = $authUser->balance;
                $transaction->charge       = $deposit->charge;
                $transaction->trx_type     = '+';
                $transaction->details      = 'Deposit Via ' . $deposit->gatewayCurrency()->name;
                $transaction->remark       = 'deposit';
                $transaction->trx          = $deposit->trx;
                $transaction->save();

                $authUser->balance -= $deposit->amount;
                $authUser->save();

                if ($deposit->goal_id) {
                    $remark = "gift";
                } else {
                    $remark = "donation";
                }

                $transaction               = new Transaction();
                $transaction->user_id      = $authUser->id;
                $transaction->amount       = $deposit->amount;
                $transaction->post_balance = $authUser->balance;
                $transaction->charge       = 0;
                $transaction->trx_type     = '-';
                $transaction->details      = 'Donation Via ' . $deposit->gatewayCurrency()->name;
                $transaction->remark       = $remark;
                $transaction->trx          = $deposit->trx;
                $transaction->save();
            }
            $user = User::find($deposit->user_id);

            $user->balance += $deposit->amount;
            $user->save();

            $transaction               = new Transaction();
            $transaction->user_id      = $deposit->user_id;
            $transaction->amount       = $deposit->amount;
            $transaction->post_balance = $user->balance;
            $transaction->charge       = $deposit->charge;
            $transaction->trx_type     = '+';

            if (auth()->id() == $deposit->user_id) {
                $remark = 'deposit';
            } else if ($deposit->goal_id) {
                $remark = "gift";
            } else {
                $remark = "donation";
            }

            $transaction->details = ucfirst($remark) . ' Via ' . $deposit->gatewayCurrency()->name;
            $transaction->remark  = $remark;
            $transaction->trx     = $deposit->trx;
            $transaction->save();

            if ($deposit->donation_id) {

                if ($deposit->donation_sign == Status::MEMBERSHIP) {
                    session()->put('WELCOME_LINK', $deposit->user->profile_link);
                } else if ($deposit->donation_sign == Status::DONATION) {
                    session()->put('THANKS_LINK', $deposit->user->profile_link);
                }
                $donation = $deposit->donation;

                if ($donation->membership_id) {
                    $membership         = Membership::where('id', $donation->membership_id)->where('status', Status::PAYMENT_INITIATE)->first();
                    $membership->status = Status::ENABLE;
                    $membership->save();

                    $mailTemplateForReceiver = "NEW_MEMBERSHIP_SUBSCRIBER";

                    $shortCodeForReceiver = [
                        'subscriber_name'    => $authUser->fullname,
                        'level_name'         => $membership->level->name,
                        'subscribe_duration' => $membership->duration_type == Status::MONTHLY_MEMBERSHIP ? "Monthly Member" : "Yearly Member",
                    ];

                    $mailTemplateForSender = "MEMBERSHIP_SUBSCRIBE";
                    $shortCodeForSender    = [
                        'subscriber_to'      => $user->fullname,
                        'level_name'         => $membership->level->name,
                        'subscribe_duration' => $membership->duration_type == Status::MONTHLY_MEMBERSHIP ? "Monthly Member" : "Yearly Member",
                    ];
                } else {
                    $mailTemplateForReceiver = "DONATION_RECEIVED";
                    $shortCodeForReceiver    = [
                        'amount' => showAmount($deposit->amount),
                        'sender' => $authUser ? $authUser->fullname : "Anonymous User",
                    ];

                    $mailTemplateForSender = "DONATION_SEND";
                    $shortCodeForSender    = [
                        'amount'   => showAmount($deposit->amount),
                        'receiver' => $user->fullname,
                    ];
                }

                $donation         = $deposit->donation;
                $donation->status = Status::DONATION_SUCCESS;
                $donation->save();

                notify($user, $mailTemplateForReceiver, $shortCodeForReceiver);

                if ($donation->supporter_id || $donation->member_id) {
                    notify($authUser, $mailTemplateForSender, $shortCodeForSender);
                }
            } else if ($deposit->goal_id) {
                $goalLog          = new GoalLog();
                $goalLog->user_id = $user->id;
                $goalLog->goal_id = $deposit->goal_id;
                $goalLog->amount  = $deposit->amount;
                $goalLog->save();

                session()->put('THANKS_GOAL_LINK', $deposit->user->profile_link);

                notify($user, 'GOAL_GIFT_RECEIVED', [
                    'sender' => $authUser ? $authUser?->fullname : 'Anonymous',
                    'amount' => showAmount($deposit->amount),
                ]);

                if ($deposit->donation_sign == Status::GOAL_GIFT && @$authUser) {
                    notify($authUser, 'GOAL_GIFT_SEND', [
                        'receiver' => $user->fullname,
                        'amount'   => showAmount($deposit->amount),
                    ]);
                }
            } else {

                notify($user, $isManual ? 'DEPOSIT_APPROVE' : 'DEPOSIT_COMPLETE', [
                    'method_name'     => $deposit->gatewayCurrency()->name,
                    'method_currency' => $deposit->method_currency,
                    'method_amount'   => showAmount($deposit->final_amount),
                    'amount'          => showAmount($deposit->amount),
                    'charge'          => showAmount($deposit->charge),
                    'rate'            => showAmount($deposit->rate),
                    'trx'             => $deposit->trx,
                    'post_balance'    => showAmount($user->balance),
                ]);
            }

            session()->forget('DONATION_DATA');

            if (!$isManual) {
                $adminNotification            = new AdminNotification();
                $adminNotification->user_id   = $user->id;
                $adminNotification->title     = ucfirst($remark) . ' successful via ' . $deposit->gatewayCurrency()->name;
                $adminNotification->click_url = urlPath('admin.deposit.successful');
                $adminNotification->save();
            }
        }
    }

    public function manualDepositConfirm() {
        $track = session()->get('Track');
        $data  = Deposit::with('gateway')->where('status', Status::PAYMENT_INITIATE)->where('trx', $track)->first();
        if (!$data) {
            return to_route(gatewayRedirectUrl());
        }
        if ($data->method_code > 999) {

            $donationData = (object) session('DONATION_DATA');
            $pageTitle    = 'Deposit Confirm';
            if (isset($donationData->duration_type) || isset($donationData->quantity) || isset($donationData->goal_id)) {
                $pageTitle = 'Payment Confirm';
            }

            $method  = $data->gatewayCurrency();
            $gateway = $method->method;
            $layout  = "master";
            if (!auth()->check()) {
                $layout = "frontend";
            }

            return view($this->activeTemplate . 'user.payment.manual', compact('data', 'pageTitle', 'method', 'gateway', 'layout'));
        }
        abort(404);
    }

    public function manualDepositUpdate(Request $request) {
        $track = session()->get('Track');
        $data  = Deposit::with('gateway')->where('status', Status::PAYMENT_INITIATE)->where('trx', $track)->first();
        if (!$data) {
            return to_route(gatewayRedirectUrl());
        }
        $gatewayCurrency = $data->gatewayCurrency();
        $gateway         = $gatewayCurrency->method;
        $formData        = $gateway->form->form_data;

        $formProcessor  = new FormProcessor();
        $validationRule = $formProcessor->valueValidation($formData);
        $request->validate($validationRule);
        $userData = $formProcessor->processFormData($request, $formData);

        $data->detail = $userData;
        $data->status = Status::PAYMENT_PENDING;
        $data->save();

        $adminNotification            = new AdminNotification();
        $adminNotification->user_id   = $data->user->id;
        $adminNotification->title     = 'Donation request from ' . $data->user->username;
        $adminNotification->click_url = urlPath('admin.deposit.details', $data->id);
        $adminNotification->save();

        notify($data->user, 'DEPOSIT_REQUEST', [
            'method_name'     => $data->gatewayCurrency()->name,
            'method_currency' => $data->method_currency,
            'method_amount'   => showAmount($data->final_amount),
            'amount'          => showAmount($data->amount),
            'charge'          => showAmount($data->charge),
            'rate'            => showAmount($data->rate),
            'trx'             => $data->trx,
        ]);
        if (session('DONATION_DATA')) {
            $notify[] = ['success', 'Your payment request has been taken'];
            return to_route('user.payment.history')->withNotify($notify);
        } else {
            $notify[] = ['success', 'Your deposit request has been taken'];
            return to_route('user.deposit.history')->withNotify($notify);
        }
    }
}
