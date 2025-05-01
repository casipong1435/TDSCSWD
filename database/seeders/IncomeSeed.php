<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use App\Models\MonthlyIncome;

class IncomeSeed extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $data = [
            [
                'income_range' => 'less than 30,000'
            ],
            [
                'income_range' => '30,000 - 60,000'
            ],
            [
                'income_range' => 'more than 60,000'
            ],
        ];

        MonthlyIncome::insert($data);
    }
}
