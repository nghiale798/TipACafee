<?php

namespace App\Lib;

use App\Constants\Status;
use App\Models\Donation;
use App\Models\GoalLog;
use App\Models\Membership;
use App\Models\Transaction;
use App\Models\User;

class DonationPayment {
    /**
     * Instance of investor user
     *
     * @var object
     */
    private $user;

    /**
     * General setting
     *
     * @var object
     */

    public function __construct($user) {
        $this->user    = $user;
        $this->setting = gs();
    }

    public function donate($amount, $donor, $donationData) {
        $user = $this->user;

        $donor->balance -= $amount;
        $donor->save();
        $user->balance += $amount;
        $user->save();

        $donorIdentity    = @$donationData->donor_identity;
        $message          = @$donationData->message;
        $isMessagePrivate = @$donationData->is_message_private;
        $quantity         = @$donationData->quantity;
        $donationType     = @$donationData->duration_type;
        $levelId          = @$donationData->membership_level_id;
        $goalId           = @$donationData->goal_id;

        if ($goalId) {

            //need //goal-log create and
            $goalLog          = new GoalLog();
            $goalLog->user_id = $user->id;
            $goalLog->goal_id = $goalId;
            $goalLog->amount  = $amount;
            $goalLog->save();

            $remark = 'gift';

            session()->put('THANKS_GOAL_LINK', $user->profile_link);

            notify($user, 'GOAL_GIFT_RECEIVED', [
                'sender' => $donor?->fullname,
                'amount' => showAmount($amount),
            ]);

            notify($donor, 'GOAL_GIFT_SEND', [
                'receiver' => $user->fullname,
                'amount'   => showAmount($amount),
            ]);
        } else {
            $donation                     = new Donation();
            $donation->amount             = @$amount;
            $donation->user_id            = @$user->id;
            $donation->message            = @$message;
            $donation->is_message_private = @$isMessagePrivate;

            if (!$donationType && !$levelId) { //supporter
                $donation->quantity       = $quantity ?? 1;
                $donation->donor_identity = $donorIdentity;
                $donation->supporter_id   = $donor?->id ?? 0;

                $mailTemplateForReceiver = "DONATION_RECEIVED";
                $shortCodeForReceiver    = [
                    'amount' => showAmount($amount),
                    'sender' => $donor ? $donor->fullname : "Anonymous User",
                ];

                $mailTemplateForSender = "DONATION_SEND";
                $shortCodeForSender    = [
                    'amount'   => showAmount($amount),
                    'receiver' => $user->fullname,
                ];

                session()->put('THANKS_LINK', $user->profile_link);

                $remark = 'donation';
                notify($user, $mailTemplateForReceiver, $shortCodeForReceiver);
                if ($donation->supporter_id || $donation->member_id) {
                    notify($donor, $mailTemplateForSender, $shortCodeForSender);
                }
            }

            if ($donationType && $levelId) { //membership
                $membership                      = new Membership();
                $membership->user_id             = $user->id;
                $membership->member_id           = $donor->id;
                $membership->membership_level_id = $levelId;
                $membership->amount              = $amount;
                $membership->duration_type       = $donationType;
                $membership->next_date           = $donationType == Status::MONTHLY_MEMBERSHIP ? now()->addMonth() : now()->addYear();
                $membership->status              = Status::ENABLE;
                $membership->save();

                $donation->member_id     = $donor->id;
                $donation->membership_id = $membership->id;

                $mailTemplateForReceiver = "NEW_MEMBERSHIP_SUBSCRIBER";

                $shortCodeForReceiver = [
                    'subscriber_name'    => @$donor->fullname,
                    'level_name'         => $membership->level->name,
                    'subscribe_duration' => $membership->duration_type == Status::MONTHLY_MEMBERSHIP ? "Monthly Member" : "Yearly Member",
                ];

                $mailTemplateForSender = "MEMBERSHIP_SUBSCRIBE";
                $shortCodeForSender    = [
                    'subscriber_to'      => @$user->fullname,
                    'level_name'         => $membership->level->name,
                    'subscribe_duration' => $membership->duration_type == Status::MONTHLY_MEMBERSHIP ? "Monthly Member" : "Yearly Member",
                ];

                session()->put('WELCOME_LINK', $user->profile_link);

                $remark = 'donation';

                notify($user, $mailTemplateForReceiver, $shortCodeForReceiver);
                if ($donation->supporter_id || $donation->member_id) {
                    notify($donor, $mailTemplateForSender, $shortCodeForSender);
                }
            }
			
			 
			$donation->status = Status::DONATION_SUCCESS;
            $donation->save();
        }

        //receiver

        $trx                       = getTrx();
        $transaction               = new Transaction();
        $transaction->user_id      = $user->id;
        $transaction->amount       = $amount;
        $transaction->post_balance = $user->balance;
        $transaction->charge       = 0;
        $transaction->trx_type     = '+';
        $transaction->details      = showAmount($amount) . ' ' . gs('cur_text') . ' ' . 'received from ' . $donor->username;
        $transaction->trx          = $trx;
        $transaction->remark       = $remark;
        $transaction->save();

        //sender
        $transaction               = new Transaction();
        $transaction->user_id      = $donor->id;
        $transaction->amount       = $amount;
        $transaction->post_balance = $donor->balance;
        $transaction->charge       = 0;
        $transaction->trx_type     = '-';
        $transaction->details      = 'Payment Via Deposit Wallet';
        $transaction->trx          = $trx;
        $transaction->remark       = $remark;
        $transaction->save();

        session()->forget('DONATION_DATA');
    }
}
