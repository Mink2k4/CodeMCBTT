<?php

use App\Http\Controllers\Api\Serivce\JustanotherpanelController;
use App\Http\Controllers\Api\Serivce\SecsersController;
use App\Http\Controllers\Api\Serivce\SmmFlarController;
use App\Http\Controllers\Api\Serivce\TestController;
use App\Mail\MailForgotPassword;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Guest\ViewClientController;
use App\Http\Controllers\Auth\AuthClientController;
use App\Http\Controllers\CronJobs\Service\CreateOrderController;
use App\Http\Controllers\Guest\DataClientController;
use App\Http\Controllers\Guest\Service\ViewServiceController;
use App\Mail\ForgotPassword;
use Illuminate\Support\Facades\Artisan;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| contains the "web" middleware group. Now create something great!
|
*/

/* Route::get('/', function () {
    return view('home');
}); */

/* Route::get('/callsql', function(){
    return Artisan::call('migrate');
}); */

Route::prefix('/install')->middleware(['install'])->group(function () {
    Route::get('/website', [AuthClientController::class, 'InstallPage'])->name('install.website');
    Route::post('/website', [AuthClientController::class, 'Install'])->name('install.website.post');
});

Route::get('/logout', [AuthClientController::class, 'Logout'])->name('logout');
Route::prefix('/auth')->middleware('guest')->group(function () {
    Route::get('/login', [AuthClientController::class, 'LoginPage'])->name('login');
    Route::post('/login', [AuthClientController::class, 'Login'])->name('login.post');
    Route::get('/register', [AuthClientController::class, 'RegisterPage'])->name('register');
    Route::post('/register', [AuthClientController::class, 'Register'])->name('register.post');
    Route::get('/forgot-password', [AuthClientController::class, 'ForgotPasswordPage'])->name('forgot.password');
    Route::post('/forgot-password', [AuthClientController::class, 'ForgotPassword'])->name('forgot.password.post');
    Route::get('/reset-password/{token}', [AuthClientController::class, 'ResetPasswordPage'])->name('reset.password');
    Route::post('/reset-password/{token}', [AuthClientController::class, 'ResetPassword'])->name('reset.password.post');

    //login google
    Route::get('/login/google', [AuthClientController::class, 'LoginGoogle'])->name('login.google');
    Route::get('/login/google/callback', [AuthClientController::class, 'LoginGoogleCallback'])->name('login.google.callback');
});
Route::get('/', [ViewClientController::class, 'LandingPage'])->name('landing');

/* Affilates */
Route::get('/user/affiliates', [ViewClientController::class, 'affiliates'])->name('user.affiliates');

Route::prefix('/')->middleware('auth')->group(function () {
    Route::get('/home', [ViewClientController::class, 'HomePage'])->name('home');
    Route::get('/profile', [ViewClientController::class, 'ProfilePage'])->name('profile');
    Route::post('/update-profile/{type}', [DataClientController::class, 'UpdateProfile'])->name('update-profile');

    Route::prefix('/recharge')->group(function () {
        Route::get('/transfer', [ViewClientController::class, 'TransferPage'])->name('recharge.transfer');
        Route::get('/card', [ViewClientController::class, 'CardPage'])->name('recharge.card');
        Route::post('/card', [DataClientController::class, 'Card'])->name('recharge.card.post');
    });

    Route::prefix('/ticket')->group(function () {
    Route::get('/', [ViewClientController::class, 'TicketPage'])->name('tickets'); // Trang danh sách ticket
    Route::get('/create', [ViewClientController::class, 'CreateTicketPage'])->name('ticket.create'); // Tạo ticket mới
    Route::post('/submit', [DataClientController::class, 'SubmitTicket'])->name('ticket.submit'); // Gửi ticket
    Route::post('/ticket/store', [ViewClientController::class, 'store'])->name('ticket.store');
    Route::get('/view/{id}', [ViewClientController::class, 'ViewTicket'])->name('ticket.view'); // Xem chi tiết ticket
    Route::put('/ticket/{id}', [ViewClientController::class, 'update'])->name('ticket.update');
    });
    
    Route::get('/user/history', [ViewClientController::class, 'HistoryPage'])->name('user.history');
    Route::get('/user/level', [ViewClientController::class, 'LevelPage'])->name('user.level');
    /* tool */
    Route::get('/tool/get-uid', [ViewClientController::class, 'ToolUid'])->name('tool.uid');
    /* Service */
    Route::get('/service/price', [ViewServiceController::class, 'viewService'])->name('service.price');
    Route::get('/service/{social}/{service}', [ViewServiceController::class, 'ViewServicePage'])->name('service.view');
    Route::get('/create-website', [ViewClientController::class, 'CreateWebsite'])->name('create.website');
    /* Refund */
    Route::get('/refunds', [ViewClientController::class, 'refundHistory'])->name('client.refund.index');
    Route::get('/refunds/history', [ViewClientController::class, 'refundHistory'])->name('client.refunds.history');

    Route::post('/user/list/{action}', [DataClientController::class, 'ListAction'])->name('user.list.action');
    Route::post('/user/order/{social}/{action}', [DataClientController::class, 'OrderAction'])->name('user.order.action');
    Route::post('/user/{action}', [DataClientController::class, 'UserAction'])->name('user.action');
    Route::post('/service/get/order', [DataClientController::class, 'ServiceGetOrder'])->name('service.get.order');

    Route::post('/create-website', [DataClientController::class, 'CreateWebsite'])->name('create.website.post');
    /* tool */
    Route::post('/tool/{action}', [DataClientController::class, 'ToolGetUid'])->name('tool.uid.post');
});

/* Route::get('/m', function(){
    return new MailForgotPassword('https://google.com');
}); */
