<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use PayOS\PayOS;
use App\Models\Transaction;
use App\Models\User;

class PayOSController extends Controller
{
    public function createPayment(Request $request)
    {
        $amount = $request->amount ?? 25000;
        $userId = $request->user_id;
        $donorName = $request->donor_name ?? 'Ẩn danh';
        $donorMessage = $request->donor_message ?? '';

        $payos = new PayOS([
            'clientId' => env('PAYOS_CLIENT_ID'),
            'apiKey' => env('PAYOS_API_KEY'),
            'checksumKey' => env('PAYOS_CHECKSUM_KEY'),
        ]);

        $transaction = Transaction::create([
            'user_id' => $userId,
            'amount' => $amount,
            'currency' => 'VND',
            'status' => 'pending',
            'transaction_id' => 'PAYOS_' . time(),
            'donor_name' => $donorName,
            'donor_message' => $donorMessage,
            'payment_gateway' => 'PayOS',
        ]);

        $orderData = [
            'amount' => $amount,
            'orderCode' => $transaction->transaction_id,
            'description' => 'Ủng hộ TipACafe',
            'returnUrl' => route('payos.callback', ['transaction_id' => $transaction->transaction_id]),
            'cancelUrl' => route('payos.cancel'),
        ];

        $paymentUrl = $payos->createPaymentLink($orderData);

        return redirect($paymentUrl);
    }

    public function paymentCallback(Request $request)
    {
        $transactionId = $request->input('transaction_id');
        $status = $request->input('status');

        $transaction = Transaction::where('transaction_id', $transactionId)->first();

        if ($transaction) {
            if ($status == 'success') {
                $transaction->status = 'completed';
                $transaction->save();

                $user = User::find($transaction->user_id);
                if ($user) {
                    $user->wallet += $transaction->amount;
                    $user->save();
                }

                return redirect()->route('home')->with('success', 'Thanh toán thành công!');
            } else {
                $transaction->status = 'failed';
                $transaction->save();
                return redirect()->route('home')->with('error', 'Thanh toán thất bại!');
            }
        }

        return redirect()->route('home')->with('error', 'Không tìm thấy giao dịch!');
    }

    public function createPayment(Request $request)
{
    \Log::info("Yêu cầu thanh toán PayOS", $request->all());
    return response()->json(['status' => 'OK']);
}

}
