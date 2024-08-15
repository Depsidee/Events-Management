<?php

namespace App\Console\Commands;

use App\Models\FoodRequest;
use App\Models\Payment;
use Illuminate\Console\Command;
use App\Models\Reservation;
use App\Models\SongRequest;
use Carbon\Carbon;

class DeleteUnpaidReservations extends Command
{
    protected $signature = 'reservations:delete-unpaid';
    protected $description = 'Delete unpaid reservations after a set period';

    public function __construct()
    {
        parent::__construct();
    }

    public function handle()
    {

        $reservations = Reservation::where('has_recorded', 1)
        ->where('created_at', '<=', Carbon::now()->subMinutes(1))
                                   ->whereHas('payment', function($query) {
                                       $query->where('status', 'unpaid');
                                   })
                                   ->get();

        foreach ($reservations as $reservation) {
            FoodRequest::where('reservation_id', '=',$reservation->id)->delete();
            SongRequest::where('reservation_id', '=',$reservation->id)->delete();
            Payment::where('id', '=', $reservation->payment_id)->delete();
            $reservation->delete();


        }
        $this->info('Unpaid reservations deleted successfully.');

    }
}
