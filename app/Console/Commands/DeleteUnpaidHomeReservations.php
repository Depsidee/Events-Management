<?php

namespace App\Console\Commands;

use App\Models\FoodHome;
use Illuminate\Console\Command;
use App\Models\Hall;
use App\Models\HomeReservation;
use App\Models\Report;
use App\Models\User;
use Carbon\Carbon;
use App\Models\Reservation;
use App\Models\SongRequest;
use App\Models\FoodRequest;
use App\Models\Payment;

class DeleteUnpaidHomeReservations extends Command
{
    protected $signature = 'homereservations:delete-unpaid';
    protected $description = 'Delete unpaid reservations after a set period';

    public function __construct()
    {
        parent::__construct();
    }

    public function handle()
    {

        $reservations = HomeReservation::where('has_recorded', 1)
        ->where('created_at', '<=', Carbon::now()->subMinutes(1))
                                   ->whereHas('payment', function($query) {
                                       $query->where('status', 'unpaid');
                                   })
                                   ->get();

        foreach ($reservations as $reservation) {
            FoodHome::where('home_reservation_id','=',$reservation->id)->delete();
            Payment::where('id','=',$reservation['payment_id'])->delete();
            $reservation->delete();

        }
        $this->info('Unpaid reservations deleted successfully.');

    }
}
