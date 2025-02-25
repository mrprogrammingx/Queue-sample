<?php

namespace App\Jobs;

use App\Models\Order;
use Barryvdh\DomPDF\Facade\Pdf;
use Illuminate\Foundation\Queue\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;

class SendOrderInvoicePDF implements ShouldQueue
{
    use Queueable;

    private Order $order;

    /**
     * Create a new job instance.
     */
    public function __construct(public readonly int $orderId)
    {
        $this->order = Order::query()
            ->select([
                'id', 
                'order_number', 
                'total_amount', 
                'ordered_at'
            ])->with([
                'user:id,name,email',
                'items:id,order_id,product_id,quantity,price',
                'items.product:id,name,price'
            ])->findOrFail($orderId);
    }

    /**
     * Execute the job.
     */
    public function handle(): void
    {
        $imageData = base64_encode(file_get_contents(public_path('simple-logo.png')));
        $logo = 'data:image/png;base64,' . $imageData;

        $pdf = PDF::loadView('orders.invoicePDF', [
            'order' => $this->order,
            'logo' => $logo
        ]);

        $pdf->save($this->order->user_id . '-' . $this->order->id . '.pdf', 'public');
        
    }
}
