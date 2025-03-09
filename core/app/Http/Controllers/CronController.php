<?php

namespace App\Http\Controllers;

use App\Constants\Status;
use App\Lib\CurlRequest;
use App\Models\CronJob;
use App\Models\CronJobLog;
use App\Models\Donation;
use App\Models\Membership;
use App\Models\Transaction;
use Carbon\Carbon;

class CronController extends Controller
{
    public function cron()
    {
        $general            = gs();
        $general->last_cron = now();
        $general->save();

        $crons = CronJob::with('schedule');

        if (request()->alias) {
            $crons->where('alias', request()->alias);
        } else {
            $crons->where('next_run', '<', now())->where('is_running', 1);
        }
        $crons = $crons->get();
        foreach ($crons as $cron) {
            $cronLog              = new CronJobLog();
            $cronLog->cron_job_id = $cron->id;
            $cronLog->start_at    = now();
            if ($cron->is_default) {
                $controller = new $cron->action[0];
                try {
                    $method = $cron->action[1];
                    $controller->$method();
                } catch (\Exception $e) {
                    $cronLog->error = $e->getMessage();
                }
            } else {
                try {
                    CurlRequest::curlContent($cron->url);
                } catch (\Exception $e) {
                    $cronLog->error = $e->getMessage();
                }
            }
            $cron->last_run = now();
            $cron->next_run = now()->addSeconds($cron->schedule->interval);
            $cron->save();

            $cronLog->end_at = $cron->last_run;

            $startTime         = Carbon::parse($cronLog->start_at);
            $endTime           = Carbon::parse($cronLog->end_at);
            $diffInSeconds     = $startTime->diffInSeconds($endTime);
            $cronLog->duration = $diffInSeconds;
            $cronLog->save();
        }
        if (request()->alias) {
            $notify[] = ['success', keyToTitle(request()->alias) . ' executed successfully'];
            return back()->withNotify($notify);
        }
    }

    public function takeMembershipPayment()
    {
        try {
            $today       = Carbon::now();
            $memberships = Membership::active()->where('next_date', '<=', $today)->whereHas('level', function ($query) {
                $query->where('status', Status::ENABLE);  
            })->orderBy('id', 'ASC')->take(100)->get();

            if (!$memberships->count()) return 0;

            foreach ($memberships as $membership) {

                $recipientUser = $membership->user;
                $giverUser     = $membership->member;

                if ($recipientUser->account_disabled == Status::YES || $recipientUser->status == Status::DISABLE) {
                    continue;
                }

                if ($membership->duration_type == Status::MONTHLY_MEMBERSHIP) {
                    $amount   = $membership->level->monthly_price;
                    $nextDate = now()->addMonth();
                } else {
                    $amount   = $membership->level->yearly_price;
                    $nextDate = now()->addYear();
                }

                if ($amount > $giverUser->balance) {
                    notify($giverUser, 'LOW_MEMBERSHIP_PAYMENT',  [
                        'level'         => $membership->level->name,
                        'duration_type' => $membership->duration_type == Status::MONTHLY_MEMBERSHIP ? 'Monthly' : 'Yearly',
                    ]);
                    $membership->status = Status::DISABLE;
                    $membership->save();
                    continue;
                }

                $giverUser->balance -= $amount;
                $giverUser->save();

                $trx  = getTrx();

                $transaction               = new Transaction();
                $transaction->user_id      = $giverUser->id;
                $transaction->amount       = $amount;
                $transaction->post_balance = $giverUser->balance;
                $transaction->charge       = 0;
                $transaction->trx_type     = '-';
                $transaction->details      = 'Donation Payment ' . $giverUser->username;
                $transaction->trx          =  $trx;
                $transaction->remark       = 'payment';
                $transaction->save();

                notify($giverUser, 'MEMBERSHIP_PAYMENT',  [
                    'amount'        => showAmount($amount),
                    'recipient'     => $giverUser->fullname,
                    'level'         => $membership->level->name,
                    'duration_type' => $membership->duration_type == Status::MONTHLY_MEMBERSHIP ? 'Monthly' : 'Yearly',
                ]);


                $recipientUser->balance += $amount;
                $recipientUser->save();

                $transaction               = new Transaction();
                $transaction->user_id      = $recipientUser->id;
                $transaction->amount       = $amount;
                $transaction->post_balance = $recipientUser->balance;
                $transaction->charge       = 0;
                $transaction->trx_type     = '+';
                $transaction->details      = 'Donation Received ' . $giverUser->username;
                $transaction->trx          =  $trx;
                $transaction->remark       = 'payment';
                $transaction->save();

                notify($recipientUser, 'RECEIVED_MEMBERSHIP_PAYMENT',  [
                    'amount'        => showAmount($amount),
                    'level'         => $membership->level->name,
                    'giver'         => $recipientUser->fullname,
                    'duration_type' => $membership->duration_type == Status::MONTHLY_MEMBERSHIP ? 'Monthly' : 'Yearly',
                ]);

                $donation                     = new Donation();
                $donation->amount             = $amount;
                $donation->user_id            = $recipientUser->id;
                $donation->member_id          = $giverUser->id;
                $donation->membership_id      = $membership->id;
                $donation->message            = $membership->duration_type == Status::MONTHLY_MEMBERSHIP ? "Monthly Membership Payment" : "Yearly Membership Payment";
                $donation->is_message_private = Status::YES;
                $donation->status             = Status::PAYMENT_SUCCESS;
                $donation->save();

                $membership->next_date = $nextDate;
                $membership->status    = Status::ENABLE;
                $membership->save();
            }
        } catch (\Throwable $th) {
            throw new \Exception($th->getMessage());
        }
    }
}
