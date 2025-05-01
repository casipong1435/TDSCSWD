<?php

namespace Database\Seeders;

// use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use App\Models\User;
use App\Models\AssistanceType;
use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        // User::factory(10)->create();
        $assistance_types = [
            [
                'assistance_type' => 'Financial assistance'
            ],
            [
                'assistance_type' => 'Material  assistance'
            ],
            [
                'assistance_type' => 'Medical  assistance'
            ],
            [
                'assistance_type' => 'Educational  assistance'
            ],
            [
                'assistance_type' => 'Psychosocial   assistance'
            ]
        ];

        AssistanceType::insert($assistance_types);
       /*$this->call([
            //BarangaySeeder::class,
            UserSeeder::class
       ]);*/
    }
}
