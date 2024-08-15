<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;
use App\Models\User;

use Illuminate\Support\Facades\Auth;
use Spatie\Permission\Contracts\Permission;
use Spatie\Permission\Contracts\Role;
//use App\Models\Role;
//use App\Models\Permission;
//use Spatie\Permission\Contracts\Permission;

class UserSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // User::factory()->count(10)->create();


        User::create([
            'user_name' => 'superAdmin',
            'email' => 'super_admin@gmail.com',
            'phone_number'=>'0954862737',
            'role_name'=>'super_admin',
            'password' => 'password',
        ]);

// //Role Admin

// $admin_role->givePermissionTo([



// ]);
// $admin=User::create([
// 'name'=>'Admin',
// 'email'=>'admin@gmail.com',
// 'password'=>bcrypt('password')

// ]);
// $admin->assignRole($admin_role);
// $admin->givePermissionTo([

//     $user_delete,
//     $user_update,
//     $user_view

// ]);

// //Role user
// $user=User::create([
//     'name'=>'user',
//     'email'=>'user@gmail.com',
//     'password'=>bcrypt('password')

//     ]);
//     $user_role=Role::create(['name'=>'user']);
//     $user->assignRole($user_role);
//     $admin->givePermissionTo([
//         $user_list

//     ]);

}
}
