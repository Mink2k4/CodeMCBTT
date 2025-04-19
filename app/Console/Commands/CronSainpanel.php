<?php

namespace App\Console\Commands;
use App\Http\Controllers\Api\Serivce\SaintPanelController;
use App\Models\Orders;
use Illuminate\Console\Command;

class CronSainpanel extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'cron:sainpanel';

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
        $this->info('Cron sainpanel: ' . date('d-m-Y H:i:s'));
        $data = Orders::where('actual_service', 'sainpanel')
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
            $dg = new SaintPanelController();
            $order_code = $item->order_code;
            $data = $dg->status($order_code);
            // var_dump($data);
            if (isset($data['status'])) {
                $start_count = $data['start_count'];
                $status = $data['status'];
                $remains = $data['remains'];
                $remains = str_replace('-', '', $remains);
                $buff = $start_count - $remains;
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

                if ($status == 'In progress') {
                    $status = 'Đang xử lý';
                }

                $item->status = $status;
                $item->start = $remains;
                $item->buff = $buff;
                $item->save();
                $count++;
            }
        }
        $this->info('Cron sainpanel: ' . $count . ' orders');
    }
}
