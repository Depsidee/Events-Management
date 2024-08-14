<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\Models\Hall;
use App\Models\Report;
use App\Models\User;

class BlockReportedHalls extends Command
{
//     protected $signature = 'block:reported-halls';

//     protected $description = 'Block halls that have been reported twice by different users';

//     public function __construct()
//     {
//         parent::__construct();
//     }

//     public function handle()
//     {
//         $halls = Hall::withCount(['reports' => function ($query) {
//             $query->select(\DB::raw('count(distinct user_id)'));
//         }])->get();

//         foreach ($halls as $hall) {
//             if ($hall->reports_count >= 2) {
//                 // Soft delete the hall
//                 $hall->delete();

//                 // Soft delete the owner
//                 $owner = User::find($hall->user_id);
//                 if ($owner) {
//                     $owner->delete();
//                 }

//                 $this->info("Hall ID {$hall->id} and its owner have been blocked.");
//             }
//         }

//         $this->info('Finished checking halls.');
//     }
}
