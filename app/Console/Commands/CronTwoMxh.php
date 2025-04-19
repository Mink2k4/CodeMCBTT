<?php

namespace App\Console\Commands;

use App\Http\Controllers\Api\Serivce\TwoMxhController;
use App\Models\Orders;
use Illuminate\Console\Command;

class CronTwoMxh extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'cron:2mxh';

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
        // return Command::SUCCESS;
        $this->info('Cron 2Mxh: ' . date('d-m-Y H:i:s'));
        $data = Orders::where('actual_service', '2mxh')
            ->where('status', '!=', 'PendingOrder')
            ->where('status', '!=', 'Suspended')
            ->where('status', '!=', 'Completed')
            ->where('status', '!=', 'Success')
            ->where('status', '!=', 'Refunded')
            ->where('status', '!=', 'Failed')
            ->where('status', '!=', 'Cancelled')->get();


        $order_code = '';
        $count = 0;
        foreach ($data as $item) {
            $mxh = new TwoMxhController();
            $mxh->path = $item->actual_path;
            $order_code = $item->order_code;
            $data = $mxh->order($order_code);
            dd($data);
            if ($data['status'] == true) {
                if (isset($data['data'])) {

                    $status = $data['data'][0]['status'];
                    $start = $data['data'][0]['startNumber'];
                    $buff = $data['data'][0]['successCount'];
                    $order_history = json_decode($item->history, true);
                    if ($status == 'Completed') {
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

                    if ($status == 'Refund') {
                        $order_history[] = [
                            'time' => date('H:i d/m/Y'),
                            'status' => 'danger',
                            'title' => "Đơn hàng đã được hoàn tiền",
                        ];
                        $item->history = json_encode($order_history);
                    }

                    if ($status == 'Preparing') {
                        $order_history[] = [
                            'time' => date('H:i d/m/Y'),
                            'status' => 'warning',
                            'title' => "Đơn hàng đang được chuẩn bị",
                        ];
                        $item->history = json_encode($order_history);
                    }

                    if($status == 'Running'){
                        $status = 'Active';
                    }elseif($status == 'Waiting'){
                        $status = 'Pending';
                    }elseif($status == 'Partial'){
                        $status = 'Active';
                    }elseif($status == 'Paused'){
                        $status = 'Suspended';
                    }elseif($status == 'Error'){
                        $status = 'Failed';
                    }

                    $item->status = $status;
                    $item->start = $start;
                    $item->buff = $buff;
                    $item->save();
                    $count++;
                }
            }
        }


        $this->info('Cron 2Mxh: ' . $count . ' orders');
    }
}
