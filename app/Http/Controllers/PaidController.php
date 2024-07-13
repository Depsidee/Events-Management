<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Wallet;
use Illuminate\Support\Facades\Validator;
use App\Http\Controllers\BaseController as BaseController;
use App\Models\Hall;
use Illuminate\Support\Facades\Auth;
use App\Models\Reservation;
use App\Models\Payment;
use App\Models\User;
use App\Services\PaidServices;
use Throwable;

class PaidController extends BaseController
{
    private PaidServices $paidServices;
    public function __construct(PaidServices $UserService)
    {
        $this->paidServices = $UserService;
    }
    ////////////////////
    //create update wallet
    /////////////////////////

    public function createWallet(Request $request)
    {
        $data = [];
        $data = $this->paidServices->create_wallet($request);
        return $this->sendResponse($data, 202);
        try {
        } catch (Throwable $th) {
            $message = $th->getMessage();
            return $this->sendError($data, $message);
        }
    }
    ///////////////////////////
    ///show Wallet:
    ////////////////////////////
    public function show_Wallet($id)
    {

        try {
            $data = [];
            $data = $this->paidServices->showWallet($id);
            return ($data);
        } catch (Throwable $th) {

            $message = $th->getMessage();
            return $this->sendError($data, $message);
        }
    }
    ////////////////////////////
    //update for wallet:
    ////////////////////////////

    public function updateWalletBalancee($id, Request $request)
    {
        try {
            $data = [];
            $data = $this->paidServices->updateWalletBalance($id, $request);
            return ($data);
        } catch (Throwable $th) {

            $message = $th->getMessage();
            return $this->sendError($data, $message);
        }
    }

    /////////////////////////////
    //create paid:
    /////////////

    public function paid($id)
    {
        if (Auth::user()->role_name == 'client') {
            $payment = Payment::find($id);
            $wallet = Wallet::where('user_id', Auth::user()->id)->first();

            // Correct the reservation query
            $reservation = Reservation::where('user_id', Auth::user()->id)
                ->where('payment_id', $payment->id)
                ->first();

            if (!$wallet) {
                return response(['message' => 'Wallet  not found'], 404);
            }
            if (!$reservation) {
                return response(['message' => ' Reservation not found'], 404);
            }
            if ($reservation->has_recorded == 1) {
                if ($payment->amount > $wallet->balance) {
                    // Schedule a delete time within 5 minutes

                    return response([
                        'wallet_balance' => $wallet->balance,

                        'message' => 'The reservation cost is higher than your wallet balance. If the balance is not filled sufficiently and payment is not made within one week , your reservation will be deleted', 'in' => $reservation->delete_time
                    ], 400);
                } else {
                    // Deduct amount from user's wallet
                    $wallet->balance -= $payment->amount;
                    $wallet->save();

                    // Calculate Hall Admin Rate
                    $hall = Hall::find($reservation->hall_id);
                    $HallAdminRate = $reservation->period * $hall->price_per_hour;

                    // Update Hall Admin Wallet
                    $wallet_AdminHall = Wallet::where('user_id', $hall->user_id)->first();
                    if ($wallet_AdminHall) {
                        $wallet_AdminHall->balance += $HallAdminRate;
                        $wallet_AdminHall->save();
                    } else {
                        $wallet_AdminHall = Wallet::create([
                            'user_id' => $hall->user_id,
                            'balance' => $HallAdminRate,
                        ]);
                    }

                    // Calculate Admin Rate
                    $AdminRate = $payment->amount - $HallAdminRate;

                    // Update Super Admin Wallet
                    $superAdmin = User::where('role_name', 'super_admin')->first();
                    if ($superAdmin) {
                        $wallet_Admin = Wallet::where('user_id', $superAdmin->id)->first();
                        if ($wallet_Admin) {
                            $wallet_Admin->balance += $AdminRate;
                            $wallet_Admin->save();
                        } else {
                            $wallet_Admin = Wallet::create([
                                'user_id' => $superAdmin->id,
                                'balance' => $AdminRate,
                            ]);
                        }
                    }

                    // Update payment status
                    $payment->update(['status' => 'Paid']);

                    return response([
                        'payment' => $payment,
                        'wallet_AdminHall' => $wallet_AdminHall,
                        'wallet_Admin' => $wallet_Admin,
                        'message' => 'Payment completed successfully'
                    ]);
                }
            } else {

                return response(['message' => 'This reservation has not yet been approved']);
            }
        } else {
            return response(['message' => 'You don\'t have permission'], 403);
        }
    }
}
