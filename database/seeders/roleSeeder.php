<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use App\Models\Permission;
use App\Models\User;
use App\Models\Role;
//use Spatie\Permission\Contracts\Permission;
//use Spatie\Permission\Contracts\Role;
class roleSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $admin_role=Role::create(['name'=>'super_admin']);
        $admin_hall=Role::create(['name'=>'admin_hall']);
        $user_Role=Role::create(['name'=>'client']);
    }
}
