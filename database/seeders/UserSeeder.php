<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use App\Models\User;
use Hash;

class UserSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        /*$users = [
            //Admin
            [
                'name' => 'admin',
                'barangay_id' => 1,
                'username' => 'admin',
                //'email' => '',
                'password' => Hash::make('admin'),
                'role' => 2,
                'focal_role' => 0
            ],
            //Staff
            [
                'name' => 'staff',
                'barangay_id' => 2,
                'username' => 'staff',
                //'email' => '',
                'password' => Hash::make('staff'),
                'role' => 1,
                'focal_role' => 0
            ],
            //Focal Senior
            [
                'name' => 'Focal Senior',
                'barangay_id' => 3,
                'username' => 'focal_senior',
                //'email' => '',
                'password' => Hash::make('senior'),
                'role' => 0,
                'focal_role' => 1
            ],
        
            //Focal PWD
            [
                'name' => 'Focal PWD',
                'barangay_id' => 5,
                'username' => 'focal_pwd',
                //'email' => '',
                'password' => Hash::make('focal_pwd'),
                'role' => 2,
                'focal_role' => 2
            ],
            //Focal Women
            [
                'name' => 'Focal Women',
                'barangay_id' => 5,
                'username' => 'focal_women',
                //'email' => '',
                'password' => Hash::make('women'),
                'role' => 2,
                'focal_role' => 3
            ],
        ];

        User::insert($users);*/

        User::where('id', 13)->update(['password' => Hash::make('admin')]);
    }
}
