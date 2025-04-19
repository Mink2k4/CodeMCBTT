<?php

namespace App\Console;

use Illuminate\Console\Scheduling\Schedule;
use Illuminate\Foundation\Console\Kernel as ConsoleKernel;
use Illuminate\Support\Facades\DB;

class Kernel extends ConsoleKernel
{

    // protected commands
    protected $commands = [
        \App\Console\Commands\RemoveUser::class,
        \App\Console\Commands\RemoveOrderFailed::class,
        \App\Console\Commands\CronOrderCon::class,
        \App\Console\Commands\CronHacklike17::class,
        \App\Console\Commands\CronSubgiare::class,
        \App\Console\Commands\CronTwoMxh::class,
        \App\Console\Commands\CronOneDg::class,
        \App\Console\Commands\CronSubmeta::class,
        \App\Console\Commands\CronSainpanel::class,
        \App\Console\Commands\RechargeTransfer::class,
        \App\Console\Commands\CronTest::class,

    ];

    /**
     * Define the application's command schedule.
     *
     * @param  \Illuminate\Console\Scheduling\Schedule  $schedule
     * @return void
     */
    protected function schedule(Schedule $schedule)
    {
        $schedule->command('user:remove 60')->daily();
        $schedule->command('order:remove-failed 7')->daily();
        $schedule->command('cron:hacklike17')->everyMinute();
        $schedule->command('cron:subgiare')->everyMinute();
        $schedule->command('cron:2mxh')->everyMinute();
        $schedule->command('cron:1dg')->everyMinute();
        $schedule->command('cron:submeta')->everyMinute();
        $schedule->command('cron:ordercon')->everyMinute();
        $schedule->command('cron:sainpanel')->everyMinute();
    }

    /**
     * Register the commands for the application.
     *
     * @return void
     */
    protected function commands()
    {
        $this->load(__DIR__ . '/Commands');
        require base_path('routes/console.php');
    }
}
