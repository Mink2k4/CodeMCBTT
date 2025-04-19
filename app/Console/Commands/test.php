<?php

namespace App\Console\Commands;

use App\Http\Controllers\Api\Serivce\SubmetaController;
use App\Models\Orders;
use Illuminate\Console\Command;

class CronTest extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'cron:test';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Command test';

    /**
     * Execute the console command.
     *
     * @return int
     */
    public function handle()
    {

        info("hello word");
    }
}
