<?php

namespace App\Console\Commands;

use App\Models\Orders;
use Carbon\Carbon;
use Illuminate\Console\Command;


class RemoveOrderFailed extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'order:remove-failed {days}';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Xoá các đơn hàng thất bại tron 7 ngày trước';

    /**
     * Execute the console command.
     *
     * @return int
     */
    public function handle()
    {
        // return Command::SUCCESS;
        $days = $this->argument('days');

        // date có thời gian
        $date = Carbon::now()->subDays($days)->toDateString();

        $this->info('Đang xoá các đơn hàng thất bại trong ' . $days .  '  ngày trước');

        $orders = Orders::where('status', 'Failed')
            ->whereDate('created_at', '<=', $date . ' 00:00:00')
            ->get();

        foreach ($orders as $order) {
            $order->delete();
        }

        $this->info('Đã xoá ' . $orders->count() . ' đơn hàng thất bại trong ' . $days . ' ngày trước');
    }
}
