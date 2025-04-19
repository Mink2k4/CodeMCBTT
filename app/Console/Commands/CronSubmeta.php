<?php

namespace App\Console\Commands;

use App\Http\Controllers\Api\Serivce\SubmetaController;
use App\Models\Orders;
use Illuminate\Console\Command;

class CronSubmeta extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'cron:submeta';

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
        $this->info('Cron Submeta: ' . date('d-m-Y H:i:s'));
        $data = Orders::where('actual_service', 'submeta')
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
            $submeta = new SubmetaController();
            $order_code = $item->order_code;
            $data = $submeta->status($order_code);
            if ($data['status'] == true) {
                // var_dump($data);
                $totalStart = $data['data']['totalStart'];
                $totalRun = $data['data']['totalRun'];
                $status = ucwords($data['data']['status']);
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
                $item->start = $totalStart;
                $item->buff = $totalRun;
                $item->save();
                $count++;
            }
        }
        $this->info('Cron Submeta: ' . date('d-m-Y H:i:s') . ' - ' . $count . ' orders updated');
    }
}
