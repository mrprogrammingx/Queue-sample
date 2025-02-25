<?php

namespace App\Http\Controllers;

ini_set('max_execution_time', 120); // 0 = Unlimited
// ini_set('memory_limit','5G');

use App\Models\Item;
use App\Models\Order;
use Illuminate\Http\Request;
use DebugBar\StandardDebugBar;
use Barryvdh\DomPDF\Facade\Pdf;
use App\Jobs\SendOrderInvoicePDF;
use Illuminate\Support\Benchmark;
use Illuminate\Support\Facades\DB;
use Barryvdh\Debugbar\Facades\Debugbar;

class QueueController extends Controller
{
    public function getAllSimple()
    {
        // var_dump(Debugbar::info(['test' => 'info']));
        Debugbar::info(['test' => 'info']);
        // $orders = Order::with('items','items.product')->limit(10000)->get();
        $orders = [];
        $result = [
            Benchmark::measure(function () use (&$orders) {
                // return Order::with('items','items.product')->limit(100000)->get();
                // $orders = Order::with('items:id,order_id,product_id,quantity,price')->limit(1000)->get();
                // var_dump($orders[1]->toArray());
                // $orders = DB::table('orders')->limit(1000000)
                //     ->join('items','orders.id','=','items.order_id')
                //     ->get();
    
                // $orders = DB::table('orders')->where('order_number', '=', 'ORD-155049')->get()->toArray();//1943 1950 1063
                // var_dump($orders);
                // $orders_ids = DB::table('orders')->pluck('id')->toArray();
                // $orders = Order::limit(2)
                //     ->select(['id', 'user_id', 'order_number', 'total_amount', 'created_at'])
                //     ->with([
                //         // 'user:id',//,name,email',
                //         // 'items:id,product_id,order_id,quantity,price',
                //         'items:id',
                //         // 'items.product:id'//,name,price'
                //     ])
                //     ->paginate(3);
                $orders = DB::table('orders')->limit(500)
                    ->select(['orders.id as id', 'orders.user_id', 'orders.order_number', 'orders.total_amount', 'orders.created_at', 'users.id as userId', 'users.name', 'users.email', 'items.id  as itemId', 'items.product_id', 'items.order_id', 'items.quantity', 'items.price', 'products.id as pro_id', 'products.name', 'products.price'])//])
                    ->join('items', 'orders.id', '=', 'items.order_id')
                    ->join('products', 'items.product_id', '=', 'products.id')
                    ->join('users', 'orders.user_id', '=', 'users.id')
                    ->get();

                return $orders;
            }, 1),
            Benchmark::dd(function () use ($orders) {
                $imageData = base64_encode(file_get_contents(public_path('simple-logo.png')));
                $logo = 'data:image/png;base64,' . $imageData;

                foreach ($orders as $order) {

                    $pdf = PDF::loadView('orders.invoicePDF', ['order' => $order, 'logo' => $logo]);
                    $pdf->save(storage_path('app/public/' . $order->user_id . '-' . $order->id . '.pdf'));
                }
            }, 5)
        ];

        return view('orders.index', compact('result'));
    }

    public function getOrders($limit = 500)
    {
        $orders = DB::table('orders')->limit($limit)
            ->select(['orders.id as id', 'orders.user_id', 'orders.order_number', 'orders.total_amount', 'orders.created_at', 'users.id as userId', 'users.name', 'users.email', 'items.id  as itemId', 'items.product_id', 'items.order_id', 'items.quantity', 'items.price', 'products.id as pro_id', 'products.name', 'products.price'])//])
            ->join('items', 'orders.id', '=', 'items.order_id')
            ->join('products', 'items.product_id', '=', 'products.id')
            ->join('users', 'orders.user_id', '=', 'users.id')
            ->get();

        return $orders;
    }
    public function getAllWithQueue()
    {

        // $orders = $this->getOrders(5000);

        $orders = Order::query()
            ->limit(5000)
            ->pluck('id');//->limit(5000)

        Benchmark::dd(function () use ($orders){
            foreach ($orders as $order) {
                SendOrderInvoicePDF::dispatch($order);
            }
        },5);
        // return $orders;
    }
}
