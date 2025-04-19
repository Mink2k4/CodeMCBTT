<?php

use App\Http\Controllers\Admin\DataAdminController;
use App\Http\Controllers\Admin\DataServiceController;
use App\Http\Controllers\Admin\ViewAdminController;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Custom\TelegramCustomController;
use Dflydev\DotAccessData\Data;

Route::prefix('/admin')->middleware(['auth', 'admin'])->group(function(){
    Route::get('/dashboard', [ViewAdminController::class, 'dashboard'])->name('admin.dashboard');
    Route::get('/website/config', [ViewAdminController::class, 'websiteConfig'])->name('admin.website.config');
    Route::get('/website/theme', [ViewAdminController::class, 'websiteTheme'])->name('admin.website.theme');
    Route::get('/user/list', [ViewAdminController::class, 'userList'])->name('admin.user.list');
    Route::get('/user/edit/{id}', [ViewAdminController::class, 'userEdit'])->name('admin.user.edit');
    Route::get('/user/balance', [ViewAdminController::class, 'userEditBalance'])->name('admin.user.balance');
    Route::get('/notification', [ViewAdminController::class, 'notification'])->name('admin.notification');
    Route::get('/activity', [ViewAdminController::class, 'activity'])->name('admin.activity');
    // Tickets
    Route::get('/tickets', [ViewAdminController::class, 'tickets'])->name('admin.tickets.index');
    Route::get('/tickets/{id}', [ViewAdminController::class, 'ticketDetail'])->name('admin.tickets.view');
    Route::post('/tickets/update/{id}', [DataAdminController::class, 'updateTicket'])->name('admin.tickets.update');
    Route::delete('/tickets/delete/{id}', [DataAdminController::class, 'deleteTicket'])->name('admin.tickets.delete');
    Route::post('/tickets/update-status/{id}', [ViewAdminController::class, 'updateStatus'])->name('admin.tickets.updateStatus');
    Route::get('/admin/tickets/{id}', [ViewAdminController::class, 'ticketDetail'])->name('admin.tickets.detail');
    Route::get('/tickets/edit/{id}', [ViewAdminController::class, 'editTicket'])->name('admin.tickets.edit');
    Route::put('/admin/tickets/{id}', [ViewAdminController::class, 'updateTicket'])->name('admin.tickets.update');
    // Hiển thị trang chỉnh sửa số dư từ refund
    Route::get('/refund/view/{id}', [ViewAdminController::class, 'viewRefund'])->name('admin.refund.view');
    // Xử lý cập nhật số dư user
    Route::post('/refund/update-balance', [ViewAdminController::class, 'userEditBalanceRefund'])->name('admin.refund.balance.post');
    /*Refund*/
    Route::get('/refunds', [ViewAdminController::class, 'refundIndex'])->name('admin.refund.index');
    Route::get('/refunds/list', [ViewAdminController::class, 'getRefundList'])->name('admin.list.refund');
    Route::delete('/refund/delete/{id}', [ViewAdminController::class, 'deleteRefund'])->name('admin.refund.delete');
    /* SERVICE */
    Route::get('/service/new/social', [ViewAdminController::class, 'serviceNewSocial'])->name('admin.service.new.social');
    Route::get('/service/social/edit/{id}', [ViewAdminController::class, 'serviceSocialEdit'])->name('admin.service.social.edit');
    Route::get('/service/new', [ViewAdminController::class, 'serviceNew'])->name('admin.service.new');
    Route::get('/service/edit/{id}', [ViewAdminController::class, 'serviceEdit'])->name('admin.service.edit');
    /* SERVER */
    Route::get('/server/list', [ViewAdminController::class, 'serverList'])->name('admin.server.list');
    Route::get('/server/new', [ViewAdminController::class, 'serverNew'])->name('admin.server.new');
    Route::get('/server/edit/{id}', [ViewAdminController::class, 'serverEdit'])->name('admin.server.edit');
    Route::get('/server/delete-all', [DataAdminController::class, 'serverDeleteAll'])->name('admin.server.delete-all');

    Route::prefix('/history')->group(function(){
        Route::get('/user', [ViewAdminController::class, 'HistoryUser'])->name('admin.history.user');
        Route::get('/order', [ViewAdminController::class, 'HistoryOrder'])->name('admin.history.order');
        Route::get('/recharge', [ViewAdminController::class, 'HistoryRecharge'])->name('admin.history.recharge');
        Route::get('/card', [ViewAdminController::class, 'HistoryCard'])->name('admin.history.card');
    });

    Route::get('/recharge/config', [ViewAdminController::class, 'rechargeConfig'])->name('admin.recharge.config');
    // Telegram
    Route::get('/config/telegram', [ViewAdminController::class, 'configTelegram'])->name('admin.config.telegram');

    //site con
    Route::get('/website-child/list', [ViewAdminController::class, 'websiteChildList'])->name('admin.website-child.list');

    Route::post('/website/config', [DataAdminController::class, 'websiteConfig'])->name('admin.website.config.post');
    Route::post('/website/theme', [DataAdminController::class, 'websiteTheme'])->name('admin.website.theme.post');
    Route::post('/user/edit/{id}', [DataAdminController::class, 'userEdit'])->name('admin.user.edit.post');
    Route::post('/user/change-password/{id}', [DataAdminController::class, 'userChangePassword'])->name('admin.user.change-password.post');
    Route::post('/user/balance', [DataAdminController::class, 'userEditBalance'])->name('admin.user.balance.post');
    Route::post('/user/delete/{id}', [DataAdminController::class, 'userDelete'])->name('admin.user.delete');
    Route::post('/notification-modal', [DataAdminController::class, 'notificationModal'])->name('admin.notification.modal.post');
    Route::post('/notification', [DataAdminController::class, 'notification'])->name('admin.notification.post');
    Route::post('/notification/delete/{id}', [DataAdminController::class, 'notificationDelete'])->name('admin.notification.delete');
    Route::post('/activity', [DataAdminController::class, 'activity'])->name('admin.activity.post');
    Route::delete('/activity/delete/{id}', [DataAdminController::class, 'activityDelete'])->name('admin.activity.delete');

    /* SERVICE POST */
    Route::post('/service/new/social', [DataServiceController::class, 'serviceNewSocial'])->name('admin.service.new.social.post');
    Route::post('/service/social/edit/{id}', [DataServiceController::class, 'serviceSocialEdit'])->name('admin.service.social.edit.post');
    Route::delete('/service/delete/{id}', [DataServiceController::class, 'serviceSocialDelete'])->name('admin.service.delete');
    Route::post('/service/new', [DataServiceController::class, 'serviceNew'])->name('admin.service.new.post');
    Route::post('/service/edit/{id}', [DataServiceController::class, 'serviceEdit'])->name('admin.service.edit.post');
    Route::get('/service/delete/{id}', [DataServiceController::class, 'serviceDelete'])->name('admin.service.delete');

    // order
    if(getDomain() == env('PARENT_SITE')){
        Route::post('order/active', [DataServiceController::class, 'orderActive'])->name('admin.order.active.post');
        Route::post('order/cancel', [DataServiceController::class, 'orderCancel'])->name('admin.order.cancel.post');
        Route::post('/order/active-all', [DataServiceController::class, 'approveAllOrders'])->name('admin.order.approveAll');
    }

    /* SERVER POST */
    Route::post('/server/new', [DataServiceController::class, 'serverNew'])->name('admin.server.new.post');
    Route::post('/server/edit/{id}', [DataServiceController::class, 'serverEdit'])->name('admin.server.edit.post');
    Route::get('/server/delete/{id}', [DataServiceController::class, 'serverDelete'])->name('admin.server.delete');
    Route::post('/server/notification-telegram', [DataServiceController::class, 'serverNotificationTelegram'])->name('admin.server.notification-telegram.post');
    Route::post('/service/checking', [DataServiceController::class, 'serviceChecking'])->name('admin.service.checking.post');
    Route::post('/server/auto-create', [DataAdminController::class, 'serverAutoCreate'])->name('admin.server.auto-create');

    Route::post('/recharge/config', [DataAdminController::class, 'rechargeConfig'])->name('admin.recharge.config.post');
    Route::get('/recharge/delete/{id}', [DataAdminController::class, 'rechargeDelete'])->name('admin.recharge.delete');
    Route::post('/recharge/promotion', [DataAdminController::class, 'rechargePromotion'])->name('admin.recharge.promotion.post');
    // telegram
    Route::post('/config/telegram', [DataAdminController::class, 'configTelegram'])->name('admin.config.telegram.post');

    Route::post('/website-child/active', [DataAdminController::class, 'websiteChildActive'])->name('admin.website-child.active.post');

    Route::post('/list/{action}', [DataAdminController::class, 'listAction'])->name('admin.list');
    Route::post('/delete-data/{type}', [DataAdminController::class, 'deleteData'])->name('admin.delete');
});

// Route::get('/tesst', [TelegramCustomController::class, 'getWebhookInfo']);
