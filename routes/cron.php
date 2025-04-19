<?php

use App\Http\Controllers\CronJobs\CallbackController;
use Illuminate\Support\Facades\Artisan;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\CronJobs\RechargeController;
use App\Http\Controllers\CronJobs\Service\CreateOrderController;

/* Route::get('/cron', function () {
    Artisan::call('schedule:run');
    return 'cron';
});
 */

Route::prefix('cronJob')->group(function () {
    Route::get('/recharge-card', [RechargeController::class, 'RechargeCard'])->name('cron.recharge.card');
    Route::get('/recharge-transfer/{type}/{domain}', function($type,$domain){
        return Artisan::call("recharge:transfer $type $domain");
    });
    Route::get('/all', function(){
        return Artisan::call('schedule:run');
    });

    // Route::get('/service/subgiare/buy', [CreateOrderController::class, 'SubgiareBuy'])->name('cronjob.service.subgiare.buy');
    // Route::get('/service/hacklike17/buy', [CreateOrderController::class, 'Hacklike17Buy'])->name('cronjob.service.hacklike17.buy');
});

Route::prefix('/callback')->group(function(){
    Route::match(['get', 'post'], '/telegram/v1', [CallbackController::class, 'telegramV1'])->name('callback.telegram.v1');
});
