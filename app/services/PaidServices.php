<?php

namespace App\Services;

use Illuminate\Http\Request;
use App\Models\Wallet;
use Illuminate\Support\Facades\Validator;
use App\Http\Controllers\BaseController as BaseController;
use Illuminate\Support\Facades\Auth;
use App\Models\Reservation;
use App\Models\Payment;
use App\Models\User;

class PaidServices
{

    /////////////
    ///create wallet for client:
    public function create_wallet($request)
    {
        $input = $request->all();
        $valledate = Validator::make($input, [
            'balance' => ['required', 'numeric', 'min:0', 'max:99999999.99']
        ]);

        if (Auth::user()->role_name == 'client') {
            if ($valledate->fails()) {
                return ['Validate your data', $valledate->errors()];
            }

            $wallet = Wallet::create([
                'user_id' => Auth::id(),
                'balance' => $request->balance,
            ]);

            return [$wallet, 'create wallet done successfully'];
        } else {
            return ['you don\'t have permission', 403];
        }
    }

    /////////////
    //update wallet
    ///////////////////
    public function updateWalletBalance($id, $request)
    {
        $valledate = Validator::make($request->all(), [
            'balance' => ['required', 'numeric', 'min:0', 'max:99999999.99']
        ]);
        if (Auth::user()->role_name == 'client') {
            $wallet = Wallet::where('user_id', Auth::user()->id)->get();
            $wallet = Wallet::find($id);
            $newBalance = $wallet->balance + $request->balance;
            $wallet->update(['balance' => $newBalance]);
            return [$wallet, 'update done successfully',202];
        } else {
            return ['you don\'t have permission', 403];
        }
    }

    ////////
    public function showWallet($id){
    $wallet=Wallet::find($id);

    if($wallet->user_id == Auth::user()->id){
  return [$wallet ,'wallet data retrivied successfully',202];}
else
{
    return ['don\'t have permission to fetch this data' ,403];
}

    }
    ////////////
    //paid
    //////////////
    public function paid ($id)    {


        if (Auth::user()->role_name == 'client') {
        $payment = Payment::find($id);
        $wallet = Wallet::where('user_id', Auth::user()->id)->get();
        $reservation = Reservation::where('user_id', Auth::user()->id && 'payment_id', $payment->id)->first();

        if ($payment->amount > $wallet->balance) {
            return [$wallet->balance, 'the reservation cost is highter than your wallet balance, If the balance is not filled sufficiently and payment is made within a week, your reservation will be deleted'];
        } else {
            $wallet->balance = $wallet->balance - $payment->amount;
            $wallet->save();
            $HallAdminRate = $reservation->period * $reservation->hall_id->price_per_hour;
            $wallet_AdminHAll = Wallet::create([
                'user_id' => $reservation->hall_id->user_id,
                'balance' => $HallAdminRate,

            ]);
            $AdminRate = $payment->amount - ($reservation->period * $reservation->hall_id->price_per_hour);
            $user_Admin = User::Where('role_name', 'super_admin');
            $wallet_Admin = Wallet::create([
                'user_id' => $user_Admin,
                'balance' => $AdminRate,
            ]);

            $payment->update(['status' => 'Paid']);
            return [[$payment, $wallet_AdminHAll, $wallet_Admin], 'payment completed successfully'];}}
            else{
                return ['you don\'t have permission', 403];
            }
        }
    }

