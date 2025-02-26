<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class OrderSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $orders = [];
        for ($i = 0; $i < 200000; $i++) {
            $orders[] = [
                'user_id' => 1,
                'order_number' => 'ORD-'.str_pad($i, 6, '0', STR_PAD_LEFT),
                'total_amount' => rand(100, 10000) / 100,
                'created_at' => now(),
                'updated_at' => now(),
            ];

            if ($i % 5000 === 0) {
                DB::table('orders')->insert($orders);
                $orders = [];
            }
        }

        if (count($orders) > 0) {
            DB::table('orders')->insert($orders);
        }
    }
}
