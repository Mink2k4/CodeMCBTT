<?php

namespace App\Console\Commands;

use App\Models\Orders;
use App\Models\User;
use Illuminate\Console\Command;

class CronOrderCon extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'cron:ordercon';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Đơn hàng site con';

    /**
     * Execute the console command.
     *
     * @return int
     */
    public function handle()
    {
        // return Command::SUCCESS;
        $this->info('Cron Order: ' . date('d-m-Y H:i:s'));
        $data = Orders::where('actual_service', env('PARENT_SITE'))
            ->where('status', '!=', 'Suspended')
            ->where('status', '!=', 'Completed')
            ->where('status', '!=', 'Success')
            ->where('status', '!=', 'Refunded')
            ->where('status', '!=', 'Failed')
            ->where('status', '!=', 'Cancelled')->get();
        $order_code = '';
        $count = 0;
        foreach ($data as $item) {
            $actual_server = $item->actual_server;
            $order = Orders::where('id', $actual_server)->where('domain', env('PARENT_SITE'))->first();
            if ($order) {
                if($order->status == 'Failed'){
                    User::where('username', $item->username)->increment('balance', $item->total_payment);
                }

                $item->start = $order->start;
                $item->buff = $order->buff;
                $item->status = $order->status === 'PendingOrder' ? 'Active' : $order->status;
                $item->save();
                $count++;
            }
        }
        $this->info('Cron Order: ' . $count . ' orders');
    }
}
