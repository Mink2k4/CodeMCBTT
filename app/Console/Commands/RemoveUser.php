<?php

namespace App\Console\Commands;

use App\Models\User;
use Illuminate\Console\Command;

class RemoveUser extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'user:remove {days}';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Xoá người dùng có hơn X ngày không đăng nhập';

    /**
     * Execute the console command.
     *
     * @return int
     */
    public function handle()
    {
        $days = $this->argument('days');
        $this->warn("Đang xoá người dùng có hơn $days ngày không đăng nhập");
        $users = User::where('last_login', '<', now()->subDays($days))->get();
        $count = $users->count();
        $this->info("Tìm thấy $count  người dùng");
        $this->info("Đang xoá...");
        $users->each(function ($user) {
            $user->delete();
        });
        $this->info("Done!");
    }
}
