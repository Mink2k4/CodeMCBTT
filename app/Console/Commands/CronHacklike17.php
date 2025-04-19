<?php

namespace App\Console\Commands;

use App\Http\Controllers\Api\Serivce\Hacklike17Controller;
use App\Models\DataHistory;
use App\Models\Orders;
use App\Models\User;
use Illuminate\Console\Command;

class CronHacklike17 extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'cron:hacklike17';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Command description';

    /**
     * Execute the console command.
     *
     * @return int
     */
    public function handle()
    {
        $data = Orders::where('actual_service', 'hacklike17')
            ->where('status', '!=', 'PendingOrder')
            ->where('status', '!=', 'Suspended')
            ->where('status', '!=', 'Completed')
            ->where('status', '!=', 'Success')
            ->where('status', '!=', 'Refunded')
            ->where('status', '!=', 'Failed')
            ->where('status', '!=', 'Cancelled')->get();

        /* add trong code */
        $order_code = '';
        $count = 0;
        foreach ($data as $item) {
            $order_code = $item->order_code;
            $hl = new Hacklike17Controller();
            $data = $hl->order($order_code);
            if ($data['status'] == true) {
                // var_dump($data['data'][0]);
                $status = $data['data'][0]['status'];
                $start = $data['data'][0]['original'];
                $buff = $data['data'][0]['present'];

                if ($status == 1) {
                    $status = 'Success';
                } elseif ($status == 0) {
                    $status = 'Active';
                } elseif ($status == '-1') {
                    $status = 'Processing';
                } elseif ($status == 2) {
                    $status = 'Cancelled';
                } elseif ($status == 3) {
                    $status = 'Refunded';
                } elseif ($status == 4) {
                    $status = 'Failed';
                } elseif ($status == 10) {
                    $status = 'Active';
                } else {
                    $status = 'Active';
                }

                if ($status == 'Refunded') {
                    $user = User::where('username', $item->username)->first();
                    if ($user) {
                        $new_balance = $user->balance + $item->total_payment;
                        DataHistory::create([
                            'username' => $item->username,
                            'action' => "Hoàn tiền",
                            'data' => $item->total_payment,
                            'old_data' => $user->balance,
                            'new_data' => $new_balance,
                            'ip' => '',
                            'description' => "Hoàn tiền cho đơn hàng $item->order_code số tiền $item->total_payment",
                            'domain' => getDomain()
                        ]);
                        $user->balance = $new_balance;
                        $user->save();
                    }
                }

                $order_history = json_decode($item->history, true);
                if ($status == 'Success') {
                    $order_history[] = [
                        'time' => date('H:i d/m/Y'),
                        'status' => 'info',
                        'title' => "Đơn hàng hoàn thành",
                    ];
                    $item->history = json_encode($order_history);
                }

                if ($status == 'Failed') {
                    $order_history[] = [
                        'time' => date('H:i d/m/Y'),
                        'status' => 'danger',
                        'title' => "Đơn hàng thất bại",
                    ];
                    $item->history = json_encode($order_history);
                }

                if ($status == 'Cancelled') {
                    $order_history[] = [
                        'time' => date('H:i d/m/Y'),
                        'status' => 'danger',
                        'title' => "Đơn hàng đã bị huỷ",
                    ];
                    $item->history = json_encode($order_history);
                }

                if ($status == 'Refunded') {
                    $order_history[] = [
                        'time' => date('H:i d/m/Y'),
                        'status' => 'danger',
                        'title' => "Đơn hàng đã được hoàn tiền",
                    ];
                    $item->history = json_encode($order_history);
                }
                $item->status = $status;
                $item->start = $start;
                $item->buff = $buff;
                $item->save();
            }
            $count++;
        }


        $this->info('Cron Subgiare: ' . $count . ' orders');
    }
}
