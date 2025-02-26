<?php

namespace Database\Seeders;

ini_set('max_execution_time', 0); // 0 = Unlimited
ini_set('memory_limit', '5G');

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class ItemSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $orders_ids = DB::table('orders')->pluck('id')->toArray();
        $products_ids = DB::table('products')->pluck('id')->toArray();

        $now = now();

        $product_id = array_rand($products_ids);
        $order_id = array_rand($orders_ids);

        $items = [];
        for ($i = 0; $i < 1000000; $i++) {
            $randProduct = array_rand($products_ids);
            $randOrder = array_rand($orders_ids);

            $items[] = [
                'product_id' => (in_array($randProduct, $products_ids) === false) ? $product_id : $randProduct,
                'order_id' => (in_array($randOrder, $orders_ids) === false) ? $order_id : $randOrder,
                'quantity' => rand(1, 10),
                'price' => rand(100, 10000) / 100,
                'created_at' => $now,
                'updated_at' => $now,
            ];

            if ($i % 5000 === 0) {

                DB::table('items')->insert($items);
                $items = [];
                echo $i."\n";
            }
        }

        if (count($items) > 0) {
            DB::table('items')->insert($items);
        }
    }
}
