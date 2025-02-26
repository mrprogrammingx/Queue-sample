<?php

namespace App\Http\Controllers;

use App\Jobs\SendOrderInvoicePDF;
use App\Models\Order;
use Barryvdh\DomPDF\Facade\Pdf;
use Illuminate\Support\Benchmark;
use Illuminate\Support\Facades\DB;

class QueueController extends Controller
{

    public function getAllSimple()
    {
        $orders = [];

        $result = [

            Benchmark::measure(function () use (&$orders) {

                $orders = DB::table('orders')->limit(5000)
                    ->select(['orders.id as id', 'orders.user_id', 'orders.order_number', 'orders.total_amount', 'orders.created_at', 'users.id as userId', 'users.name', 'users.email', 'items.id  as itemId', 'items.product_id', 'items.order_id', 'items.quantity', 'items.price', 'products.id as pro_id', 'products.name', 'products.price'])
                    ->join('items', 'orders.id', '=', 'items.order_id')
                    ->join('products', 'items.product_id', '=', 'products.id')
                    ->join('users', 'orders.user_id', '=', 'users.id')
                    ->get();

                return $orders;

            }, 1),

            Benchmark::dd(function () use ($orders) {

                $imageData = base64_encode(file_get_contents(public_path('simple-logo.png')));
                $logo = 'data:image/png;base64,'.$imageData;

                foreach ($orders as $order) {

                    $pdf = PDF::loadView('orders.invoicePDF', ['order' => $order, 'logo' => $logo]);
                    $pdf->save(storage_path('app/public/'.$order->user_id.'-'.$order->id.'.pdf'));
                }
            }, 1)
        ];

        return view('orders.index', compact('result'));
    }

    public function getOrders($limit = 5000)
    {

        return DB::table('orders')->limit($limit)
            ->select(['orders.id as id', 'orders.user_id', 'orders.order_number', 'orders.total_amount', 'orders.created_at', 'users.id as userId', 'users.name', 'users.email', 'items.id  as itemId', 'items.product_id', 'items.order_id', 'items.quantity', 'items.price', 'products.id as pro_id', 'products.name', 'products.price'])//])
            ->join('items', 'orders.id', '=', 'items.order_id')
            ->join('products', 'items.product_id', '=', 'products.id')
            ->join('users', 'orders.user_id', '=', 'users.id')
            ->get();

    }

    public function getAllWithQueue()
    {
        $orders = Order::query()
            ->limit(5000)
            ->pluck('id');

        Benchmark::dd(function () use ($orders) {
            
            foreach ($orders as $order) {
                SendOrderInvoicePDF::dispatch($order);
            }
        }, 1);

        return $orders;
    }

}
