<?php

namespace App\Console\Commands;

use App\Http\Controllers\Api\Serivce\MuaflController;
use App\Models\Orders;
use Illuminate\Console\Command;

class CronMuafl extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'cron:muafl';

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
            $this->info('Cron Muafl: ' . date('d-m-Y H:i:s'));
            $data = Orders::where('actual_service', 'muafl')
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
                $sb = new MuaflController();

            }
    }
}
