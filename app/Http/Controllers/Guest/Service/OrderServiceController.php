<?php

namespace App\Http\Controllers\Guest\Service;

use App\Http\Controllers\Api\Serivce\FlareController;
use App\Http\Controllers\Api\Serivce\Hacklike17Controller;
use App\Http\Controllers\Api\Serivce\OneDgController;
use App\Http\Controllers\Api\Serivce\SubgiareController;
use App\Http\Controllers\Api\Serivce\TDSController;
use App\Http\Controllers\Api\Serivce\TTC;
use App\Http\Controllers\Api\Serivce\Trumvip;
use App\Http\Controllers\Api\Serivce\TwoMxhController;
use App\Http\Controllers\Api\Serivce\JAPController;
use App\Http\Controllers\Api\Serivce\CostDevideTwoPointTwo;
use App\Http\Controllers\Api\Serivce\CostDevideOnePointFive;
use App\Http\Controllers\Api\Serivce\CostNormal;
use App\Http\Controllers\Api\Serivce\CostTenPercent;
use App\Http\Controllers\Api\Serivce\CostEightPercent;
use App\Http\Controllers\Api\Serivce\CostTwoPerCent;
use App\Http\Controllers\Api\Serivce\Dnoxsmm;
use App\Http\Controllers\Api\Serivce\N1panel;
use App\Http\Controllers\Controller;
use App\Http\Controllers\Custom\TelegramCustomController;
use App\Models\DataHistory;
use App\Models\Orders;
use App\Models\ServerService;
use App\Models\Service;
use App\Models\ServiceSocial;
use App\Models\SiteCon;
use App\Models\User;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use SebastianBergmann\CodeCoverage\Report\PHP;
use Illuminate\Support\Facades\Http;


class OrderServiceController extends Controller
{
    /*  public function __construct()
    {
        $this->middleware('xss');
    } */

    public function createOrder($social, $service, Request $request)
    {
        $api_token = $request->header('Api-token');
        if (empty($api_token)) {
            return response()->json([
                'status' => 'error',
                'message' => 'Api token is required'
            ]);
        } else {
            if (getDomain() == env('PARENT_SITE')) {
                $user = User::where('domain', getDomain())->where('api_token', $api_token)->first();
                if ($user) {
                    $valid = Validator::make($request->all(), [
                        'link_order' => 'required',
                        'server_service' => 'required',
                    ]);

                    if ($valid->fails()) {
                        return response()->json([
                            'status' => 'error',
                            'message' => $valid->errors()->first()
                        ]);
                    } else {
                        $server = ServerService::where('domain', getDomain())->where('id', $request->server_service)->first();
                        $social_service = ServiceSocial::where('domain', getDomain())->where('slug', $social)->first();
                        if ($social_service) {
                            $service_ = Service::where('domain', getDomain())->where('slug', $service)->where('service_social', $social_service->slug)->first();
                            if ($service_) {
                                $server = ServerService::where('domain', getDomain())->where('social_id', $social_service->id)->where('service_id', $service_->id)->where('server', $request->server_service)->first();
                                if ($server) {
                                    if ($server->status != 'Active') {
                                        return response()->json([
                                            'status' => 'error',
                                            'message' => 'Server đang bảo trì hoặc ngừng nhận đơn'
                                        ]);
                                        die();
                                    } else {
                                        switch ($service_->category) {
                                            case 'default':
                                                $validator = [
                                                    'link_order' => 'required',
                                                    'server_service' => 'required',
                                                    'quantity' => 'required|numeric',
                                                ];
                                                break;
                                            case 'reaction':
                                                $validator = [
                                                    'link_order' => 'required',
                                                    'server_service' => 'required',
                                                    'quantity' => 'required|numeric',
                                                    'reaction' => 'required',
                                                ];
                                                break;
                                            case 'reaction-speed':
                                                $validator = [
                                                    'link_order' => 'required',
                                                    'server_service' => 'required',
                                                    'quantity' => 'required|numeric',
                                                    'reaction' => 'required',
                                                    'speed' => 'required',
                                                ];
                                                break;
                                            case 'comment':
                                                $validator = [
                                                    'link_order' => 'required',
                                                    'server_service' => 'required',
                                                    'comment' => 'required',
                                                ];
                                                break;
                                            case 'comment-quantity':
                                                $validator = [
                                                    'link_order' => 'required',
                                                    'server_service' => 'required',
                                                    'comment' => 'required',
                                                    'quantity' => 'required|numeric',
                                                ];
                                                break;
                                            case 'minutes':
                                                $validator = [
                                                    'link_order' => 'required',
                                                    'server_service' => 'required',
                                                    'quantity' => 'required|numeric',
                                                    'minutes' => 'required',
                                                ];
                                                break;
                                            case 'time':
                                                $validator = [
                                                    'link_order' => 'required',
                                                    'server_service' => 'required',
                                                    'quantity' => 'required|numeric',
                                                    'time' => 'required',
                                                ];
                                                break;
                                            default:
                                                $validator = [
                                                    'link_order' => 'required',
                                                    'server_service' => 'required',
                                                    'quantity' => 'required|numeric',
                                                ];
                                                break;
                                        }

                                        $valid = Validator::make($request->all(), $validator);
                                        if ($valid->fails()) {
                                            return response()->json([
                                                'status' => 'error',
                                                'message' => $valid->errors()->first()
                                            ]);
                                        } else {
                                            if ($service_->category == 'comment') {
                                                $quantity = count(explode("\n", $request->comment));
                                                $request->merge(['quantity' => $quantity]);
                                            }

                                            if ($server->min > $request->quantity) {
                                                return response()->json([
                                                    'status' => 'error',
                                                    'message' => 'Số lượng tối thiểu là ' . $server->min
                                                ]);
                                            } elseif ($server->max < $request->quantity) {
                                                return response()->json([
                                                    'status' => 'error',
                                                    'message' => 'Số lượng tối đa là ' . $server->max
                                                ]);
                                            } else {
                                                $price = priceServer($server->id, $user->level);
                                                $total_payment = 0;
                                                if ($service_->category == 'minutes') {
                                                    $total_payment = $price * $request->quantity * $request->minutes;
                                                } else {
                                                    $total_payment = $price * $request->quantity;
                                                }

                                                if ($user->balance < $total_payment) {
                                                    return response()->json([
                                                        'status' => 'error',
                                                        'message' => 'Số dư trong tài khoản không đủ'
                                                    ]);
                                                } 
                                                else {

                                                    if (env('IS_ORDER') == true) {
                                                        $data_send = false;
                                                        $actual_path = $server->actual_path;
                                                        $actual_server = $server->actual_server;
                                                        $quantity = $request->quantity;
                                                        $order_link = $request->link_order;
                                                        if ($server->actual_service == 'subgiare') {

                                                            $subgiare = new SubgiareController();
                                                            $actual_path = $server->actual_path;
                                                            $actual_server = $server->actual_server;
                                                            $quantity = $request->quantity;
                                                            $order_link = $request->link_order;
                                                            $subgiare = new SubgiareController();
                                                            $subgiare->path = $actual_path;
                                                            $subgiare->data = [
                                                                'order_link' => $order_link,
                                                                'quantity' => $quantity,
                                                                'speed' => $request->speed ?? '0',
                                                                'comment' => $request->comment ?? '',
                                                                'minutes' => $request->minutes ?? '',
                                                                'time' => $request->time ?? '',
                                                                'reaction' => $request->reaction ?? '',
                                                                'server_order' => $actual_server,
                                                            ];

                                                            $result = $subgiare->CreateOrder();
                                                            if ($result['status'] == true) {
                                                                $order_history = [
                                                                    'time' => Carbon::now()->format('H:i d/m/Y'),
                                                                    'status' => 'info',
                                                                    'title' => "Đơn hàng đang hoạt động",
                                                                ];
                                                                $order = Orders::create([
                                                                    'username' => $user->username,
                                                                    'service_id' => $service_->id,
                                                                    'service_name' => $service_->name,
                                                                    'server_service' => $request->server_service,
                                                                    'price' => $price,
                                                                    'quantity' => $request->quantity,
                                                                    'total_payment' => $total_payment,
                                                                    'order_code' => '',
                                                                    'order_link' => $request->link_order,
                                                                    'start' => 0,
                                                                    'buff' => 0,
                                                                    'actual_service' => $server->actual_service,
                                                                    'actual_path' => $server->actual_path,
                                                                    'actual_server' => $server->actual_server,
                                                                    'status' => 'Active',
                                                                    'action' => json_encode([
                                                                        'link_order' => $request->link_order,
                                                                        'server_service' => $request->server_service,
                                                                        'quantity' => $request->quantity,
                                                                        'reaction' => $request->reaction ?? '',
                                                                        'speed' => $request->speed ?? '',
                                                                        'comment' => $request->comment ?? '',
                                                                        'minutes' => $request->minutes ?? '',
                                                                        'time' => $request->time ?? '',
                                                                    ]),
                                                                    'dataJson' => '',
                                                                    'isShow' => 1,
                                                                    'history' => json_encode([
                                                                        [
                                                                            'status' => 'primary',
                                                                            'title' => "Đơn hàng đã được tạo",
                                                                            'time' => Carbon::now()->format('H:i d/m/Y'),
                                                                        ]
                                                                    ]),
                                                                    'note' => $request->note ?? '',
                                                                    'domain' => getDomain(),
                                                                ]);

                                                                if ($order) {
                                                                    $order->order_code = $result['data']['code_order'];
                                                                    $order->start = $result['data']['start'] ?? 0;
                                                                    $order->buff = $result['data']['buff'] ?? 0;
                                                                    $order->status = 'Active';
                                                                    $order->dataJson = json_encode($result['data']);
                                                                    $order->history = json_encode($order_history);
                                                                    $order->save();

                                                                    $balance = $user->balance;
                                                                    $user->balance = $user->balance - $total_payment;
                                                                    $user->total_deduct = $user->total_deduct + $total_payment;
                                                                    $user->save();
                                                                    DataHistory::create([
                                                                        'username' => $user->username,
                                                                        'action' => 'Tạo đơn',
                                                                        'data' => $total_payment,
                                                                        'old_data' => $balance,
                                                                        'new_data' => $user->balance,
                                                                        'ip' => $request->ip(),
                                                                        'description' => "Tạo đơn hàng " . $service_->name . " với số lượng " . $request->quantity . " với giá " . number_format($total_payment) . "đ",
                                                                        'data_json' => '',
                                                                        'domain' => getDomain(),
                                                                    ]);
                                                                    return response()->json([
                                                                        'status' => 'success',
                                                                        'message' => 'Đặt hàng thành công',
                                                                        'order_id' => $order->id,
                                                                    ]);
                                                                } else {
                                                                    return response()->json([
                                                                        'status' => 'error',
                                                                        'message' => 'Đặt hàng thất bại',
                                                                    ]);
                                                                }
                                                            } else {
                                                                return response()->json([
                                                                    'status' => 'error',
                                                                    'message' => $result['message'],
                                                                ]);
                                                            }
                                                        } elseif ($server->actual_service == 'hacklike17') {
                                                            $hacklike17 = new Hacklike17Controller();
                                                            $hacklike17->path = $actual_path;
                                                            $hacklike17->data = [
                                                                'order_link' => $order_link,
                                                                'quantity' => $quantity,
                                                                'speed' => $request->speed ?? '0',
                                                                'comment' => $request->comment ?? '',
                                                                'minutes' => $request->minutes ?? '',
                                                                'time' => $request->time ?? '',
                                                                'reaction' => $request->reaction ?? '',
                                                                'server_order' => $actual_server,
                                                            ];
                                                            $result = $hacklike17->CreateOrder();
                                                            if ($result['status'] == true) {
                                                                // thêm array vào history
                                                                $order_history = [
                                                                    'time' => Carbon::now()->format('H:i d/m/Y'),
                                                                    'status' => 'info',
                                                                    'title' => "Đơn hàng đã hoạt động",
                                                                ];
                                                                $order = Orders::create([
                                                                    'username' => $user->username,
                                                                    'service_id' => $service_->id,
                                                                    'service_name' => $service_->name,
                                                                    'server_service' => $request->server_service,
                                                                    'price' => $price,
                                                                    'quantity' => $request->quantity,
                                                                    'total_payment' => $total_payment,
                                                                    'order_code' => '',
                                                                    'order_link' => $request->link_order,
                                                                    'start' => 0,
                                                                    'buff' => 0,
                                                                    'actual_service' => $server->actual_service,
                                                                    'actual_path' => $server->actual_path,
                                                                    'actual_server' => $server->actual_server,
                                                                    'status' => 'Active',
                                                                    'action' => json_encode([
                                                                        'link_order' => $request->link_order,
                                                                        'server_service' => $request->server_service,
                                                                        'quantity' => $request->quantity,
                                                                        'reaction' => $request->reaction ?? '',
                                                                        'speed' => $request->speed ?? '',
                                                                        'comment' => $request->comment ?? '',
                                                                        'minutes' => $request->minutes ?? '',
                                                                        'time' => $request->time ?? '',
                                                                    ]),
                                                                    'dataJson' => '',
                                                                    'isShow' => 1,
                                                                    'history' => json_encode([
                                                                        [
                                                                            'status' => 'primary',
                                                                            'title' => "Đơn hàng đã được tạo",
                                                                            'time' => Carbon::now()->format('H:i d/m/Y'),
                                                                        ]
                                                                    ]),
                                                                    'note' => $request->note ?? '',
                                                                    'domain' => getDomain(),
                                                                ]);
                                                                $balance = $user->balance;
                                                                $user->balance = $user->balance - $total_payment;
                                                                $user->total_deduct = $user->total_deduct + $total_payment;
                                                                $user->save();
                                                                DataHistory::create([
                                                                    'username' => $user->username,
                                                                    'action' => 'Tạo đơn',
                                                                    'data' => $total_payment,
                                                                    'old_data' => $balance,
                                                                    'new_data' => $user->balance,
                                                                    'ip' => $request->ip(),
                                                                    'description' => "Tạo đơn hàng " . $service_->name . " với số lượng " . $request->quantity . " với giá " . number_format($total_payment) . "đ",
                                                                    'data_json' => '',
                                                                    'domain' => getDomain(),
                                                                ]);
                                                                if ($order) {
                                                                    $order->order_code = $result['data']['code_order'];
                                                                    $order->start = $result['data']['start'] ?? 0;
                                                                    $order->buff = $result['data']['buff'] ?? 0;
                                                                    $order->status = 'Active';
                                                                    $order->dataJson = json_encode($result['data']);
                                                                    $order->history = json_encode($order_history);
                                                                    $order->save();
                                                                    return response()->json([
                                                                        'status' => 'success',
                                                                        'message' => 'Đặt hàng thành công',
                                                                        'order_id' => $order->id,
                                                                    ]);
                                                                } else {
                                                                    return response()->json([
                                                                        'status' => 'error',
                                                                        'message' => 'Đặt hàng thất bại',
                                                                    ]);
                                                                }
                                                            } else {
                                                                return response()->json([
                                                                    'status' => 'error',
                                                                    'message' => $result['message'],
                                                                ]);
                                                            }
                                                        } elseif ($server->actual_service == '2mxh') {
                                                            $twomxh = new TwoMxhController();
                                                            $twomxh->path = $actual_path;
                                                            $twomxh->data = [
                                                                'order_link' => $order_link,
                                                                'quantity' => $quantity,
                                                                'speed' => $request->speed ?? '0',
                                                                'comment' => $request->comment ?? '',
                                                                'minutes' => $request->minutes ?? '',
                                                                'time' => $request->time ?? '',
                                                                'reaction' => $request->reaction ?? '',
                                                                'server_order' => $actual_server,
                                                            ];
                                                            $result = $twomxh->CreateOrder();
                                                            if ($result['status'] == true) {
                                                                $order_history = [
                                                                    'time' => Carbon::now()->format('H:i d/m/Y'),
                                                                    'status' => 'info',
                                                                    'title' => "Đơn hàng đã hoạt động",
                                                                ];
                                                                $order = Orders::create([
                                                                    'username' => $user->username,
                                                                    'service_id' => $service_->id,
                                                                    'service_name' => $service_->name,
                                                                    'server_service' => $request->server_service,
                                                                    'price' => $price,
                                                                    'quantity' => $request->quantity,
                                                                    'total_payment' => $total_payment,
                                                                    'order_code' => '',
                                                                    'order_link' => $request->link_order,
                                                                    'start' => 0,
                                                                    'buff' => 0,
                                                                    'actual_service' => $server->actual_service,
                                                                    'actual_path' => $server->actual_path,
                                                                    'actual_server' => $server->actual_server,
                                                                    'status' => 'Active',
                                                                    'action' => json_encode([
                                                                        'link_order' => $request->link_order,
                                                                        'server_service' => $request->server_service,
                                                                        'quantity' => $request->quantity,
                                                                        'reaction' => $request->reaction ?? '',
                                                                        'speed' => $request->speed ?? '',
                                                                        'comment' => $request->comment ?? '',
                                                                        'minutes' => $request->minutes ?? '',
                                                                        'time' => $request->time ?? '',
                                                                    ]),
                                                                    'dataJson' => '',
                                                                    'isShow' => 1,
                                                                    'history' => json_encode([
                                                                        [
                                                                            'status' => 'primary',
                                                                            'title' => "Đơn hàng đã được tạo",
                                                                            'time' => Carbon::now()->format('H:i d/m/Y'),
                                                                        ]
                                                                    ]),
                                                                    'note' => $request->note ?? '',
                                                                    'domain' => getDomain(),
                                                                ]);

                                                                if ($order) {
                                                                    $order->order_code = $result['data']['order']['order_id'];
                                                                    $order->start = $result['data']['order']['start_num'] ?? 0;
                                                                    $order->buff = 0;
                                                                    $order->status = 'Active';
                                                                    $order->dataJson = json_encode($result['data']);
                                                                    $order->history = json_encode($order_history);
                                                                    $order->save();


                                                                    $balance = $user->balance;
                                                                    $user->balance = $user->balance - $total_payment;
                                                                    $user->total_deduct = $user->total_deduct + $total_payment;
                                                                    $user->save();
                                                                    DataHistory::create([
                                                                        'username' => $user->username,
                                                                        'action' => 'Tạo đơn',
                                                                        'data' => $total_payment,
                                                                        'old_data' => $balance,
                                                                        'new_data' => $user->balance,
                                                                        'ip' => $request->ip(),
                                                                        'description' => "Tạo đơn hàng " . $service_->name . " với số lượng " . $request->quantity . " với giá " . number_format($total_payment) . "đ",
                                                                        'data_json' => '',
                                                                        'domain' => getDomain(),
                                                                    ]);
                                                                    return response()->json([
                                                                        'status' => 'success',
                                                                        'message' => 'Đặt hàng thành công',
                                                                        'order_id' => $order->id,
                                                                    ]);
                                                                } else {
                                                                    return response()->json([
                                                                        'status' => 'error',
                                                                        'message' => 'Đặt hàng thất bại',
                                                                    ]);
                                                                }
                                                            } else {
                                                                return response()->json([
                                                                    'status' => 'error',
                                                                    'message' => 'Lỗi',
                                                                ]);
                                                            }
                                                        } elseif ($server->actual_service == 'TDS') {
                                                            $tds = new TDSController();

                                                            $tds->path = $actual_path;

                                                            $tds->data = [
                                                                'order_link' => $order_link,
                                                                'quantity' => $quantity,
                                                                'speed' => $request->speed ?? '0',
                                                                'comment' => $request->comment ?? '',
                                                                'minutes' => $request->minutes ?? '',
                                                                'time' => $request->time ?? '',
                                                                'reaction' => $request->reaction ?? '',
                                                                'server_order' => $actual_server,
                                                            ];

                                                            $result = $tds->createOrder();
                                                            if ($result['status'] == true) {
                                                                $order_history = [
                                                                    'time' => Carbon::now()->format('H:i d/m/Y'),
                                                                    'status' => 'info',
                                                                    'title' => "Đơn hàng đã hoạt động",
                                                                ];
                                                                $order = Orders::create([
                                                                    'username' => $user->username,
                                                                    'service_id' => $service_->id,
                                                                    'service_name' => $service_->name,
                                                                    'server_service' => $request->server_service,
                                                                    'price' => $price,
                                                                    'quantity' => $request->quantity,
                                                                    'total_payment' => $total_payment,
                                                                    'order_code' => '',
                                                                    'order_link' => $request->link_order,
                                                                    'start' => 0,
                                                                    'buff' => 0,
                                                                    'actual_service' => $server->actual_service,
                                                                    'actual_path' => $server->actual_path,
                                                                    'actual_server' => $server->actual_server,
                                                                    'status' => 'Active',
                                                                    'action' => json_encode([
                                                                        'link_order' => $request->link_order,
                                                                        'server_service' => $request->server_service,
                                                                        'quantity' => $request->quantity,
                                                                        'reaction' => $request->reaction ?? '',
                                                                        'speed' => $request->speed ?? '',
                                                                        'comment' => $request->comment ?? '',
                                                                        'minutes' => $request->minutes ?? '',
                                                                        'time' => $request->time ?? '',
                                                                    ]),
                                                                    'dataJson' => '',
                                                                    'isShow' => 1,
                                                                    'history' => json_encode([
                                                                        [
                                                                            'status' => 'primary',
                                                                            'title' => "Đơn hàng đã được tạo",
                                                                            'time' => Carbon::now()->format('H:i d/m/Y'),
                                                                        ]
                                                                    ]),
                                                                    'note' => $request->note ?? '',
                                                                    'domain' => getDomain(),
                                                                ]);

                                                                if ($order) {
                                                                    $order->start = 0;
                                                                    $order->buff = 0;
                                                                    $order->status = 'Active';
                                                                    $order->dataJson = json_encode($result);
                                                                    $order->history = json_encode($order_history);
                                                                    $order->save();


                                                                    $balance = $user->balance;
                                                                    $user->balance = $user->balance - $total_payment;
                                                                    $user->total_deduct = $user->total_deduct + $total_payment;
                                                                    $user->save();
                                                                    DataHistory::create([
                                                                        'username' => $user->username,
                                                                        'action' => 'Tạo đơn',
                                                                        'data' => $total_payment,
                                                                        'old_data' => $balance,
                                                                        'new_data' => $user->balance,
                                                                        'ip' => $request->ip(),
                                                                        'description' => "Tạo đơn hàng " . $service_->name . " với số lượng " . $request->quantity . " với giá " . number_format($total_payment) . "đ",
                                                                        'data_json' => '',
                                                                        'domain' => getDomain(),
                                                                    ]);

                                                                    return response()->json([
                                                                        'status' => 'success',
                                                                        'message' => 'Đặt hàng thành công',
                                                                        'order_id' => $order->id,
                                                                    ]);
                                                                } else {
                                                                    return response()->json([
                                                                        'status' => 'error',
                                                                        'message' => 'Đặt hàng thất bại',
                                                                    ]);
                                                                }
                                                            } else {
                                                                return response()->json([
                                                                    'status' => 'error',
                                                                    'message' => $result['message'],
                                                                ]);
                                                            }

                                                        } elseif ($server->actual_service == 'TTC') {
                                                            $tds = new TTC();

                                                            $tds->path = $actual_path;

                                                            $tds->data = [
                                                                'order_link' => $order_link,
                                                                'quantity' => $quantity,
                                                                'speed' => $request->speed ?? '0',
                                                                'comment' => $request->comment ?? '',
                                                                'minutes' => $request->minutes ?? '',
                                                                'time' => $request->time ?? '',
                                                                'reaction' => $request->reaction ?? '',
                                                                'server_order' => $actual_server,
                                                            ];

                                                            $result = $tds->createOrder();
                                                            if ($result['status'] == true) {
                                                                $order_history = [
                                                                    'time' => Carbon::now()->format('H:i d/m/Y'),
                                                                    'status' => 'info',
                                                                    'title' => "Đơn hàng đã hoạt động",
                                                                ];
                                                                $order = Orders::create([
                                                                    'username' => $user->username,
                                                                    'service_id' => $service_->id,
                                                                    'service_name' => $service_->name,
                                                                    'server_service' => $request->server_service,
                                                                    'price' => $price,
                                                                    'quantity' => $request->quantity,
                                                                    'total_payment' => $total_payment,
                                                                    'order_code' => '',
                                                                    'order_link' => $request->link_order,
                                                                    'start' => 0,
                                                                    'buff' => 0,
                                                                    'actual_service' => $server->actual_service,
                                                                    'actual_path' => $server->actual_path,
                                                                    'actual_server' => $server->actual_server,
                                                                    'status' => 'Active',
                                                                    'action' => json_encode([
                                                                        'link_order' => $request->link_order,
                                                                        'server_service' => $request->server_service,
                                                                        'quantity' => $request->quantity,
                                                                        'reaction' => $request->reaction ?? '',
                                                                        'speed' => $request->speed ?? '',
                                                                        'comment' => $request->comment ?? '',
                                                                        'minutes' => $request->minutes ?? '',
                                                                        'time' => $request->time ?? '',
                                                                    ]),
                                                                    'dataJson' => '',
                                                                    'isShow' => 1,
                                                                    'history' => json_encode([
                                                                        [
                                                                            'status' => 'primary',
                                                                            'title' => "Đơn hàng đã được tạo",
                                                                            'time' => Carbon::now()->format('H:i d/m/Y'),
                                                                        ]
                                                                    ]),
                                                                    'note' => $request->note ?? '',
                                                                    'domain' => getDomain(),
                                                                ]);

                                                                if ($order) {
                                                                    $order->start = 0;
                                                                    $order->buff = 0;
                                                                    $order->status = 'Active';
                                                                    $order->dataJson = json_encode($result);
                                                                    $order->history = json_encode($order_history);
                                                                    $order->save();


                                                                    $balance = $user->balance;
                                                                    $user->balance = $user->balance - $total_payment;
                                                                    $user->total_deduct = $user->total_deduct + $total_payment;
                                                                    $user->save();
                                                                    DataHistory::create([
                                                                        'username' => $user->username,
                                                                        'action' => 'Tạo đơn',
                                                                        'data' => $total_payment,
                                                                        'old_data' => $balance,
                                                                        'new_data' => $user->balance,
                                                                        'ip' => $request->ip(),
                                                                        'description' => "Tạo đơn hàng " . $service_->name . " với số lượng " . $request->quantity . " với giá " . number_format($total_payment) . "đ",
                                                                        'data_json' => '',
                                                                        'domain' => getDomain(),
                                                                    ]);

                                                                    return response()->json([
                                                                        'status' => 'success',
                                                                        'message' => 'Đặt hàng thành công',
                                                                        'order_id' => $order->id,
                                                                    ]);
                                                                } else {
                                                                    return response()->json([
                                                                        'status' => 'error',
                                                                        'message' => 'Đặt hàng thất bại',
                                                                    ]);
                                                                }
                                                            } else {
                                                                return response()->json([
                                                                    'status' => 'error',
                                                                    'message' => $result['message'],
                                                                ]);
                                                            }

                                                        }elseif ($server->actual_service == 'dontay') {
                                                            $order = Orders::create([
                                                                'username' => $user->username,
                                                                'service_id' => $service_->id,
                                                                'service_name' => $service_->name,
                                                                'server_service' => $request->server_service,
                                                                'price' => $price,
                                                                'quantity' => $request->quantity,
                                                                'total_payment' => $total_payment,
                                                                'order_code' => '',
                                                                'order_link' => $request->link_order,
                                                                'start' => 0,
                                                                'buff' => 0,
                                                                'actual_service' => $server->actual_service,
                                                                'actual_path' => $server->actual_path,
                                                                'actual_server' => $server->actual_server,
                                                                'status' => 'Pending',
                                                                'action' => json_encode([
                                                                    'link_order' => $request->link_order,
                                                                    'server_service' => $request->server_service,
                                                                    'quantity' => $request->quantity,
                                                                    'reaction' => $request->reaction ?? '',
                                                                    'speed' => $request->speed ?? '',
                                                                    'comment' => $request->comment ?? '',
                                                                    'minutes' => $request->minutes ?? '',
                                                                    'time' => $request->time ?? '',
                                                                ]),
                                                                'dataJson' => '',
                                                                'isShow' => 1,
                                                                'history' => json_encode([
                                                                    [
                                                                        'status' => 'primary',
                                                                        'title' => "Đơn hàng đã được tạo",
                                                                        'time' => Carbon::now()->format('H:i d/m/Y'),
                                                                    ]
                                                                ]),
                                                                'note' => $request->note ?? '',
                                                                'domain' => getDomain(),
                                                            ]);

                                                            if ($order) {
                                                                if (DataSite('telegram_chat_id')) {
                                                                    $tele = new TelegramCustomController();
                                                                    $bot = $tele->bot();
                                                                    $bot->sendMessage([
                                                                        'chat_id' => DataSite('telegram_chat_id'),
                                                                        'text' => "🔔  Đơn hàng mới\nThành viên: " . $user->username . "\nDịch vụ: " . $service_->name . "\nCấp bậc: " . $user->level . "\nMáy chủ: " . $request->server_service . "\nSố lượng: " . $request->quantity . "\nGiá: " . number_format($total_payment) . "\nBalance: " . number_format($user->balance) . "đ\nLink: " . $request->link_order . "\nComment:\n" . $request->comment . "\nLoại cảm xúc: " . $request->reaction . "\nNote: " . $request->note . "\nTime: " . $request->minutes . "\nĐơn hàng từ: " . getDomain(),
                                                                    ]);
                                                                }
                                                                $balance = $user->balance;
                                                                $user->balance = $user->balance - $total_payment;
                                                                $user->total_deduct = $user->total_deduct + $total_payment;
                                                                $user->save();
                                                                DataHistory::create([
                                                                    'username' => $user->username,
                                                                    'action' => 'Tạo đơn',
                                                                    'data' => $total_payment,
                                                                    'old_data' => $balance,
                                                                    'new_data' => $user->balance,
                                                                    'ip' => $request->ip(),
                                                                    'description' => "Tạo đơn hàng " . $service_->name . " với số lượng " . $request->quantity . " với giá " . number_format($total_payment) . "đ",
                                                                    'data_json' => '',
                                                                    'domain' => getDomain(),
                                                                ]);
                                                                return response()->json([
                                                                    'status' => 'success',
                                                                    'message' => 'Đặt hàng thành công',
                                                                    'order_id' => $order->id,
                                                                ]);
                                                            }
                                                        }elseif ($server->actual_service == 'flare') {
                                                            $smm = new FlareController();
                                                            $link = $request->input('link_order');
                                                            $result = $smm->CreateOrder($request, $link, $quantity, $server->service_list, $total_payment);
                                                            if ($result['status']) {
                                                                $order_history = [
                                                                    'time' => Carbon::now()->format('H:i d/m/Y'),
                                                                    'status' => 'info',
                                                                    'title' => "Đơn hàng đã hoạt động",
                                                                ];
                                                                $order = Orders::create([
                                                                    'username' => $user->username,
                                                                    'service_id' => $service_->id,
                                                                    'service_name' => $service_->name,
                                                                    'server_service' => $request->server_service,
                                                                    'price' => $price,
                                                                    'quantity' => $request->quantity,
                                                                    'total_payment' => $total_payment,
                                                                    'order_code' => $result['data']['order'],
                                                                    'order_link' => $request->link_order,
                                                                    'start' => 0,
                                                                    'buff' => 0,
                                                                    'actual_service' => $server->actual_service,
                                                                    'actual_path' => $server->actual_path,
                                                                    'actual_server' => $server->actual_server,
                                                                    'status' => 'Active',
                                                                    'action' => json_encode([
                                                                        'link_order' => $request->link_order,
                                                                        'server_service' => $request->server_service,
                                                                        'quantity' => $request->quantity,
                                                                        'reaction' => $request->reaction ?? '',
                                                                        'speed' => $request->speed ?? '',
                                                                        'comment' => $request->comment ?? '',
                                                                        'minutes' => $request->minutes ?? '',
                                                                        'time' => $request->time ?? '',
                                                                    ]),
                                                                    'dataJson' => '',
                                                                    'isShow' => 1,
                                                                    'history' => json_encode([
                                                                        [
                                                                            'status' => 'primary',
                                                                            'title' => "Đơn hàng đã được tạo",
                                                                            'time' => Carbon::now()->format('H:i d/m/Y'),
                                                                        ]
                                                                    ]),
                                                                    'note' => $request->note ?? '',
                                                                    'domain' => getDomain(),
                                                                ]);

                                                                if ($order) {
                                                                    $order->order_code = $result['data']['order'] ?? '';
                                                                    $order->start = 0;
                                                                    $order->buff = 0;
                                                                    $order->status = 'Active';
                                                                    $order->dataJson = json_encode($result);
                                                                    $order->history = json_encode($order_history);
                                                                    $order->save();


                                                                    $balance = $user->balance;
                                                                    $user->balance = $user->balance - $total_payment;
                                                                    $user->total_deduct = $user->total_deduct + $total_payment;
                                                                    $user->save();
                                                                    DataHistory::create([
                                                                        'username' => $user->username,
                                                                        'action' => 'Tạo đơn',
                                                                        'data' => $total_payment,
                                                                        'old_data' => $balance,
                                                                        'new_data' => $user->balance,
                                                                        'ip' => $request->ip(),
                                                                        'description' => "Tạo đơn hàng " . $service_->name . " với số lượng " . $request->quantity . " với giá " . number_format($total_payment) . "đ",
                                                                        'data_json' => '',
                                                                        'domain' => getDomain(),
                                                                    ]);
                                                                    return response()->json([
                                                                        'status' => 'success',
                                                                        'message' => 'Đặt hàng thành công',
                                                                        'order_id' => $order->id,
                                                                    ]);
                                                                } else {
                                                                    return response()->json([
                                                                        'status' => 'error',
                                                                        'message' => 'Đặt hàng thất bại',
                                                                    ]);
                                                                }
                                                            } else {
                                                                return response()->json([
                                                                    'status' => 'error',
                                                                    'message' => $result['message'],
                                                                ]);
                                                            }
                                                        }elseif ($server->actual_service == 'n1panel') {
                                                            $smm = new N1panel();
                                                            $link = $request->input('link_order');
                                                            $result = $smm->CreateOrder($request, $link, $quantity, $server->service_list, $total_payment);
                                                            if ($result['status']) {
                                                                $order_history = [
                                                                    'time' => Carbon::now()->format('H:i d/m/Y'),
                                                                    'status' => 'info',
                                                                    'title' => "Đơn hàng đã hoạt động",
                                                                ];
                                                                $order = Orders::create([
                                                                    'username' => $user->username,
                                                                    'service_id' => $service_->id,
                                                                    'service_name' => $service_->name,
                                                                    'server_service' => $request->server_service,
                                                                    'price' => $price,
                                                                    'quantity' => $request->quantity,
                                                                    'total_payment' => $total_payment,
                                                                    'order_code' => $result['data']['order'],
                                                                    'order_link' => $request->link_order,
                                                                    'start' => 0,
                                                                    'buff' => 0,
                                                                    'actual_service' => $server->actual_service,
                                                                    'actual_path' => $server->actual_path,
                                                                    'actual_server' => $server->actual_server,
                                                                    'status' => 'Active',
                                                                    'action' => json_encode([
                                                                        'link_order' => $request->link_order,
                                                                        'server_service' => $request->server_service,
                                                                        'quantity' => $request->quantity,
                                                                        'reaction' => $request->reaction ?? '',
                                                                        'speed' => $request->speed ?? '',
                                                                        'comment' => $request->comment ?? '',
                                                                        'minutes' => $request->minutes ?? '',
                                                                        'time' => $request->time ?? '',
                                                                    ]),
                                                                    'dataJson' => '',
                                                                    'isShow' => 1,
                                                                    'history' => json_encode([
                                                                        [
                                                                            'status' => 'primary',
                                                                            'title' => "Đơn hàng đã được tạo",
                                                                            'time' => Carbon::now()->format('H:i d/m/Y'),
                                                                        ]
                                                                    ]),
                                                                    'note' => $request->note ?? '',
                                                                    'domain' => getDomain(),
                                                                ]);

                                                                if ($order) {
                                                                    $order->order_code = $result['data']['order'] ?? '';
                                                                    $order->start = 0;
                                                                    $order->buff = 0;
                                                                    $order->status = 'Active';
                                                                    $order->dataJson = json_encode($result);
                                                                    $order->history = json_encode($order_history);
                                                                    $order->save();


                                                                    $balance = $user->balance;
                                                                    $user->balance = $user->balance - $total_payment;
                                                                    $user->total_deduct = $user->total_deduct + $total_payment;
                                                                    $user->save();
                                                                    DataHistory::create([
                                                                        'username' => $user->username,
                                                                        'action' => 'Tạo đơn',
                                                                        'data' => $total_payment,
                                                                        'old_data' => $balance,
                                                                        'new_data' => $user->balance,
                                                                        'ip' => $request->ip(),
                                                                        'description' => "Tạo đơn hàng " . $service_->name . " với số lượng " . $request->quantity . " với giá " . number_format($total_payment) . "đ",
                                                                        'data_json' => '',
                                                                        'domain' => getDomain(),
                                                                    ]);
                                                                    return response()->json([
                                                                        'status' => 'success',
                                                                        'message' => 'Đặt hàng thành công',
                                                                        'order_id' => $order->id,
                                                                    ]);
                                                                } else {
                                                                    return response()->json([
                                                                        'status' => 'error',
                                                                        'message' => 'Đặt hàng thất bại',
                                                                    ]);
                                                                }
                                                            } else {
                                                                return response()->json([
                                                                    'status' => 'error',
                                                                    'message' => $result['message'],
                                                                ]);
                                                            }
                                                        }elseif ($server->actual_service == 'trumvip') {
                                                            $smm = new Trumvip();
                                                            $result = $smm->CreateOrder($order_link, $quantity, $server->service_list);
                                                            if ($result['status']) {
                                                                $order_history = [
                                                                    'time' => Carbon::now()->format('H:i d/m/Y'),
                                                                    'status' => 'info',
                                                                    'title' => "Đơn hàng đã hoạt động",
                                                                ];
                                                                $order = Orders::create([
                                                                    'username' => $user->username,
                                                                    'service_id' => $service_->id,
                                                                    'service_name' => $service_->name,
                                                                    'server_service' => $request->server_service,
                                                                    'price' => $price,
                                                                    'quantity' => $request->quantity,
                                                                    'total_payment' => $total_payment,
                                                                    'order_code' => $result['data']['order'],
                                                                    'order_link' => $request->link_order,
                                                                    'start' => 0,
                                                                    'buff' => 0,
                                                                    'actual_service' => $server->actual_service,
                                                                    'actual_path' => $server->actual_path,
                                                                    'actual_server' => $server->actual_server,
                                                                    'status' => 'Active',
                                                                    'action' => json_encode([
                                                                        'link_order' => $request->link_order,
                                                                        'server_service' => $request->server_service,
                                                                        'quantity' => $request->quantity,
                                                                        'reaction' => $request->reaction ?? '',
                                                                        'speed' => $request->speed ?? '',
                                                                        'comment' => $request->comment ?? '',
                                                                        'minutes' => $request->minutes ?? '',
                                                                        'time' => $request->time ?? '',
                                                                    ]),
                                                                    'dataJson' => '',
                                                                    'isShow' => 1,
                                                                    'history' => json_encode([
                                                                        [
                                                                            'status' => 'primary',
                                                                            'title' => "Đơn hàng đã được tạo",
                                                                            'time' => Carbon::now()->format('H:i d/m/Y'),
                                                                        ]
                                                                    ]),
                                                                    'note' => $request->note ?? '',
                                                                    'domain' => getDomain(),
                                                                ]);

                                                                if ($order) {
                                                                    $order->order_code = $result['data']['order'] ?? '';
                                                                    $order->start = 0;
                                                                    $order->buff = 0;
                                                                    $order->status = 'Active';
                                                                    $order->dataJson = json_encode($result);
                                                                    $order->history = json_encode($order_history);
                                                                    $order->save();


                                                                    $balance = $user->balance;
                                                                    $user->balance = $user->balance - $total_payment;
                                                                    $user->total_deduct = $user->total_deduct + $total_payment;
                                                                    $user->save();
                                                                    DataHistory::create([
                                                                        'username' => $user->username,
                                                                        'action' => 'Tạo đơn',
                                                                        'data' => $total_payment,
                                                                        'old_data' => $balance,
                                                                        'new_data' => $user->balance,
                                                                        'ip' => $request->ip(),
                                                                        'description' => "Tạo đơn hàng " . $service_->name . " với số lượng " . $request->quantity . " với giá " . number_format($total_payment) . "đ",
                                                                        'data_json' => '',
                                                                        'domain' => getDomain(),
                                                                    ]);
                                                                    return response()->json([
                                                                        'status' => 'success',
                                                                        'message' => 'Đặt hàng thành công',
                                                                        'order_id' => $order->id,
                                                                    ]);
                                                                } else {
                                                                    return response()->json([
                                                                        'status' => 'error',
                                                                        'message' => 'Đặt hàng thất bại',
                                                                    ]);
                                                                }
                                                            } else {
                                                                return response()->json([
                                                                    'status' => 'error',
                                                                    'message' => $result['message'],
                                                                ]);
                                                            }
                                                        }elseif ($server->actual_service == 'dino') {
                                                            $smm = new DinoController();
                                                            $link = $request->input('link_order');
                                                            $result = $smm->CreateOrder($request, $link, $quantity, $server->service_list, $total_payment);
                                                            if ($result['status']) {
                                                                $order_history = [
                                                                    'time' => Carbon::now()->format('H:i d/m/Y'),
                                                                    'status' => 'info',
                                                                    'title' => "Đơn hàng đã hoạt động",
                                                                ];
                                                                $order = Orders::create([
                                                                    'username' => $user->username,
                                                                    'service_id' => $service_->id,
                                                                    'service_name' => $service_->name,
                                                                    'server_service' => $request->server_service,
                                                                    'price' => $price,
                                                                    'quantity' => $request->quantity,
                                                                    'total_payment' => $total_payment,
                                                                    'order_code' => $result['data']['order'],
                                                                    'order_link' => $request->link_order,
                                                                    'start' => 0,
                                                                    'buff' => 0,
                                                                    'actual_service' => $server->actual_service,
                                                                    'actual_path' => $server->actual_path,
                                                                    'actual_server' => $server->actual_server,
                                                                    'status' => 'Active',
                                                                    'action' => json_encode([
                                                                        'link_order' => $request->link_order,
                                                                        'server_service' => $request->server_service,
                                                                        'quantity' => $request->quantity,
                                                                        'reaction' => $request->reaction ?? '',
                                                                        'speed' => $request->speed ?? '',
                                                                        'comment' => $request->comment ?? '',
                                                                        'minutes' => $request->minutes ?? '',
                                                                        'time' => $request->time ?? '',
                                                                    ]),
                                                                    'dataJson' => '',
                                                                    'isShow' => 1,
                                                                    'history' => json_encode([
                                                                        [
                                                                            'status' => 'primary',
                                                                            'title' => "Đơn hàng đã được tạo",
                                                                            'time' => Carbon::now()->format('H:i d/m/Y'),
                                                                        ]
                                                                    ]),
                                                                    'note' => $request->note ?? '',
                                                                    'domain' => getDomain(),
                                                                ]);

                                                                if ($order) {
                                                                    $order->order_code = $result['data']['order'] ?? '';
                                                                    $order->start = 0;
                                                                    $order->buff = 0;
                                                                    $order->status = 'Active';
                                                                    $order->dataJson = json_encode($result);
                                                                    $order->history = json_encode($order_history);
                                                                    $order->save();


                                                                    $balance = $user->balance;
                                                                    $user->balance = $user->balance - $total_payment;
                                                                    $user->total_deduct = $user->total_deduct + $total_payment;
                                                                    $user->save();
                                                                    DataHistory::create([
                                                                        'username' => $user->username,
                                                                        'action' => 'Tạo đơn',
                                                                        'data' => $total_payment,
                                                                        'old_data' => $balance,
                                                                        'new_data' => $user->balance,
                                                                        'ip' => $request->ip(),
                                                                        'description' => "Tạo đơn hàng " . $service_->name . " với số lượng " . $request->quantity . " với giá " . number_format($total_payment) . "đ",
                                                                        'data_json' => '',
                                                                        'domain' => getDomain(),
                                                                    ]);
                                                                    return response()->json([
                                                                        'status' => 'success',
                                                                        'message' => 'Đặt hàng thành công',
                                                                        'order_id' => $order->id,
                                                                    ]);
                                                                } else {
                                                                    return response()->json([
                                                                        'status' => 'error',
                                                                        'message' => 'Đặt hàng thất bại',
                                                                    ]);
                                                                }
                                                            } else {
                                                                return response()->json([
                                                                    'status' => 'error',
                                                                    'message' => $result['message'],
                                                                ]);
                                                            }

                                                        }elseif ($server->actual_service == 'Smm(Quantity-8%)') {
                                                            $smm = new CostEightPercent();
                                                            $link = $request->input('link_order');
                                                            $result = $smm->CreateOrder($request, $link, $quantity, $server->service_list, $total_payment);
                                                            if ($result['status']) {
                                                                $order_history = [
                                                                    'time' => Carbon::now()->format('H:i d/m/Y'),
                                                                    'status' => 'info',
                                                                    'title' => "Đơn hàng đã hoạt động",
                                                                ];
                                                                $order = Orders::create([
                                                                    'username' => $user->username,
                                                                    'service_id' => $service_->id,
                                                                    'service_name' => $service_->name,
                                                                    'server_service' => $request->server_service,
                                                                    'price' => $price,
                                                                    'quantity' => $request->quantity,
                                                                    'total_payment' => $total_payment,
                                                                    'order_code' => $result['data']['order'],
                                                                    'order_link' => $request->link_order,
                                                                    'start' => 0,
                                                                    'buff' => 0,
                                                                    'actual_service' => $server->actual_service,
                                                                    'actual_path' => $server->actual_path,
                                                                    'actual_server' => $server->actual_server,
                                                                    'status' => 'Active',
                                                                    'action' => json_encode([
                                                                        'link_order' => $request->link_order,
                                                                        'server_service' => $request->server_service,
                                                                        'quantity' => $request->quantity,
                                                                        'reaction' => $request->reaction ?? '',
                                                                        'speed' => $request->speed ?? '',
                                                                        'comment' => $request->comment ?? '',
                                                                        'minutes' => $request->minutes ?? '',
                                                                        'time' => $request->time ?? '',
                                                                    ]),
                                                                    'dataJson' => '',
                                                                    'isShow' => 1,
                                                                    'history' => json_encode([
                                                                        [
                                                                            'status' => 'primary',
                                                                            'title' => "Đơn hàng đã được tạo",
                                                                            'time' => Carbon::now()->format('H:i d/m/Y'),
                                                                        ]
                                                                    ]),
                                                                    'note' => $request->note ?? '',
                                                                    'domain' => getDomain(),
                                                                ]);

                                                                if ($order) {
                                                                    $order->order_code = $result['data']['order'] ?? '';
                                                                    $order->start = 0;
                                                                    $order->buff = 0;
                                                                    $order->status = 'Active';
                                                                    $order->dataJson = json_encode($result);
                                                                    $order->history = json_encode($order_history);
                                                                    $order->save();


                                                                    $balance = $user->balance;
                                                                    $user->balance = $user->balance - $total_payment;
                                                                    $user->total_deduct = $user->total_deduct + $total_payment;
                                                                    $user->save();
                                                                    DataHistory::create([
                                                                        'username' => $user->username,
                                                                        'action' => 'Tạo đơn',
                                                                        'data' => $total_payment,
                                                                        'old_data' => $balance,
                                                                        'new_data' => $user->balance,
                                                                        'ip' => $request->ip(),
                                                                        'description' => "Tạo đơn hàng " . $service_->name . " với số lượng " . $request->quantity . " với giá " . number_format($total_payment) . "đ",
                                                                        'data_json' => '',
                                                                        'domain' => getDomain(),
                                                                    ]);
                                                                    return response()->json([
                                                                        'status' => 'success',
                                                                        'message' => 'Đặt hàng thành công',
                                                                        'order_id' => $order->id,
                                                                    ]);
                                                                } else {
                                                                    return response()->json([
                                                                        'status' => 'error',
                                                                        'message' => 'Đặt hàng thất bại',
                                                                    ]);
                                                                }
                                                            } else {
                                                                return response()->json([
                                                                    'status' => 'error',
                                                                    'message' => $result['message'],
                                                                ]);
                                                            }
                                                        }elseif ($server->actual_service == 'Smm(Quantity-10%)') {
                                                            $smm = new CostTenPercent();
                                                            $link = $request->input('link_order');
                                                            $result = $smm->CreateOrder($request, $link, $quantity, $server->service_list, $total_payment);
                                                            if ($result['status']) {
                                                                $order_history = [
                                                                    'time' => Carbon::now()->format('H:i d/m/Y'),
                                                                    'status' => 'info',
                                                                    'title' => "Đơn hàng đã hoạt động",
                                                                ];
                                                                $order = Orders::create([
                                                                    'username' => $user->username,
                                                                    'service_id' => $service_->id,
                                                                    'service_name' => $service_->name,
                                                                    'server_service' => $request->server_service,
                                                                    'price' => $price,
                                                                    'quantity' => $request->quantity,
                                                                    'total_payment' => $total_payment,
                                                                    'order_code' => $result['data']['order'],
                                                                    'order_link' => $request->link_order,
                                                                    'start' => 0,
                                                                    'buff' => 0,
                                                                    'actual_service' => $server->actual_service,
                                                                    'actual_path' => $server->actual_path,
                                                                    'actual_server' => $server->actual_server,
                                                                    'status' => 'Active',
                                                                    'action' => json_encode([
                                                                        'link_order' => $request->link_order,
                                                                        'server_service' => $request->server_service,
                                                                        'quantity' => $request->quantity,
                                                                        'reaction' => $request->reaction ?? '',
                                                                        'speed' => $request->speed ?? '',
                                                                        'comment' => $request->comment ?? '',
                                                                        'minutes' => $request->minutes ?? '',
                                                                        'time' => $request->time ?? '',
                                                                    ]),
                                                                    'dataJson' => '',
                                                                    'isShow' => 1,
                                                                    'history' => json_encode([
                                                                        [
                                                                            'status' => 'primary',
                                                                            'title' => "Đơn hàng đã được tạo",
                                                                            'time' => Carbon::now()->format('H:i d/m/Y'),
                                                                        ]
                                                                    ]),
                                                                    'note' => $request->note ?? '',
                                                                    'domain' => getDomain(),
                                                                ]);

                                                                if ($order) {
                                                                    $order->order_code = $result['data']['order'] ?? '';
                                                                    $order->start = 0;
                                                                    $order->buff = 0;
                                                                    $order->status = 'Active';
                                                                    $order->dataJson = json_encode($result);
                                                                    $order->history = json_encode($order_history);
                                                                    $order->save();


                                                                    $balance = $user->balance;
                                                                    $user->balance = $user->balance - $total_payment;
                                                                    $user->total_deduct = $user->total_deduct + $total_payment;
                                                                    $user->save();
                                                                    DataHistory::create([
                                                                        'username' => $user->username,
                                                                        'action' => 'Tạo đơn',
                                                                        'data' => $total_payment,
                                                                        'old_data' => $balance,
                                                                        'new_data' => $user->balance,
                                                                        'ip' => $request->ip(),
                                                                        'description' => "Tạo đơn hàng " . $service_->name . " với số lượng " . $request->quantity . " với giá " . number_format($total_payment) . "đ",
                                                                        'data_json' => '',
                                                                        'domain' => getDomain(),
                                                                    ]);
                                                                    return response()->json([
                                                                        'status' => 'success',
                                                                        'message' => 'Đặt hàng thành công',
                                                                        'order_id' => $order->id,
                                                                    ]);
                                                                } else {
                                                                    return response()->json([
                                                                        'status' => 'error',
                                                                        'message' => 'Đặt hàng thất bại',
                                                                    ]);
                                                                }
                                                            } else {
                                                                return response()->json([
                                                                    'status' => 'error',
                                                                    'message' => $result['message'],
                                                                ]);
                                                            }
                                                        }elseif ($server->actual_service == 'Smm(Quantity-2%)') {
                                                            $smm = new CostTwoPercent();
                                                            $link = $request->input('link_order');
                                                            $result = $smm->CreateOrder($request, $link, $quantity, $server->service_list, $total_payment);
                                                            if ($result['status']) {
                                                                $order_history = [
                                                                    'time' => Carbon::now()->format('H:i d/m/Y'),
                                                                    'status' => 'info',
                                                                    'title' => "Đơn hàng đã hoạt động",
                                                                ];
                                                                $order = Orders::create([
                                                                    'username' => $user->username,
                                                                    'service_id' => $service_->id,
                                                                    'service_name' => $service_->name,
                                                                    'server_service' => $request->server_service,
                                                                    'price' => $price,
                                                                    'quantity' => $request->quantity,
                                                                    'total_payment' => $total_payment,
                                                                    'order_code' => $result['data']['order'],
                                                                    'order_link' => $request->link_order,
                                                                    'start' => 0,
                                                                    'buff' => 0,
                                                                    'actual_service' => $server->actual_service,
                                                                    'actual_path' => $server->actual_path,
                                                                    'actual_server' => $server->actual_server,
                                                                    'status' => 'Active',
                                                                    'action' => json_encode([
                                                                        'link_order' => $request->link_order,
                                                                        'server_service' => $request->server_service,
                                                                        'quantity' => $request->quantity,
                                                                        'reaction' => $request->reaction ?? '',
                                                                        'speed' => $request->speed ?? '',
                                                                        'comment' => $request->comment ?? '',
                                                                        'minutes' => $request->minutes ?? '',
                                                                        'time' => $request->time ?? '',
                                                                    ]),
                                                                    'dataJson' => '',
                                                                    'isShow' => 1,
                                                                    'history' => json_encode([
                                                                        [
                                                                            'status' => 'primary',
                                                                            'title' => "Đơn hàng đã được tạo",
                                                                            'time' => Carbon::now()->format('H:i d/m/Y'),
                                                                        ]
                                                                    ]),
                                                                    'note' => $request->note ?? '',
                                                                    'domain' => getDomain(),
                                                                ]);

                                                                if ($order) {
                                                                    $order->order_code = $result['data']['order'] ?? '';
                                                                    $order->start = 0;
                                                                    $order->buff = 0;
                                                                    $order->status = 'Active';
                                                                    $order->dataJson = json_encode($result);
                                                                    $order->history = json_encode($order_history);
                                                                    $order->save();


                                                                    $balance = $user->balance;
                                                                    $user->balance = $user->balance - $total_payment;
                                                                    $user->total_deduct = $user->total_deduct + $total_payment;
                                                                    $user->save();
                                                                    DataHistory::create([
                                                                        'username' => $user->username,
                                                                        'action' => 'Tạo đơn',
                                                                        'data' => $total_payment,
                                                                        'old_data' => $balance,
                                                                        'new_data' => $user->balance,
                                                                        'ip' => $request->ip(),
                                                                        'description' => "Tạo đơn hàng " . $service_->name . " với số lượng " . $request->quantity . " với giá " . number_format($total_payment) . "đ",
                                                                        'data_json' => '',
                                                                        'domain' => getDomain(),
                                                                    ]);
                                                                    return response()->json([
                                                                        'status' => 'success',
                                                                        'message' => 'Đặt hàng thành công',
                                                                        'order_id' => $order->id,
                                                                    ]);
                                                                } else {
                                                                    return response()->json([
                                                                        'status' => 'error',
                                                                        'message' => 'Đặt hàng thất bại',
                                                                    ]);
                                                                }
                                                            } else {
                                                                return response()->json([
                                                                    'status' => 'error',
                                                                    'message' => $result['message'],
                                                                ]);
                                                            }
                                                        }elseif ($server->actual_service == 'Smm(Quantity/2.2)') {
                                                            $smm = new CostDevideTwoPointTwo();
                                                            $link = $request->input('link_order');
                                                            $result = $smm->CreateOrder($request, $link, $quantity, $server->service_list, $total_payment);
                                                            if ($result['status']) {
                                                                $order_history = [
                                                                    'time' => Carbon::now()->format('H:i d/m/Y'),
                                                                    'status' => 'info',
                                                                    'title' => "Đơn hàng đã hoạt động",
                                                                ];
                                                                $order = Orders::create([
                                                                    'username' => $user->username,
                                                                    'service_id' => $service_->id,
                                                                    'service_name' => $service_->name,
                                                                    'server_service' => $request->server_service,
                                                                    'price' => $price,
                                                                    'quantity' => $request->quantity,
                                                                    'total_payment' => $total_payment,
                                                                    'order_code' => $result['data']['order'],
                                                                    'order_link' => $request->link_order,
                                                                    'start' => 0,
                                                                    'buff' => 0,
                                                                    'actual_service' => $server->actual_service,
                                                                    'actual_path' => $server->actual_path,
                                                                    'actual_server' => $server->actual_server,
                                                                    'status' => 'Active',
                                                                    'action' => json_encode([
                                                                        'link_order' => $request->link_order,
                                                                        'server_service' => $request->server_service,
                                                                        'quantity' => $request->quantity,
                                                                        'reaction' => $request->reaction ?? '',
                                                                        'speed' => $request->speed ?? '',
                                                                        'comment' => $request->comment ?? '',
                                                                        'minutes' => $request->minutes ?? '',
                                                                        'time' => $request->time ?? '',
                                                                    ]),
                                                                    'dataJson' => '',
                                                                    'isShow' => 1,
                                                                    'history' => json_encode([
                                                                        [
                                                                            'status' => 'primary',
                                                                            'title' => "Đơn hàng đã được tạo",
                                                                            'time' => Carbon::now()->format('H:i d/m/Y'),
                                                                        ]
                                                                    ]),
                                                                    'note' => $request->note ?? '',
                                                                    'domain' => getDomain(),
                                                                ]);

                                                                if ($order) {
                                                                    $order->order_code = $result['data']['order'] ?? '';
                                                                    $order->start = 0;
                                                                    $order->buff = 0;
                                                                    $order->status = 'Active';
                                                                    $order->dataJson = json_encode($result);
                                                                    $order->history = json_encode($order_history);
                                                                    $order->save();


                                                                    $balance = $user->balance;
                                                                    $user->balance = $user->balance - $total_payment;
                                                                    $user->total_deduct = $user->total_deduct + $total_payment;
                                                                    $user->save();
                                                                    DataHistory::create([
                                                                        'username' => $user->username,
                                                                        'action' => 'Tạo đơn',
                                                                        'data' => $total_payment,
                                                                        'old_data' => $balance,
                                                                        'new_data' => $user->balance,
                                                                        'ip' => $request->ip(),
                                                                        'description' => "Tạo đơn hàng " . $service_->name . " với số lượng " . $request->quantity . " với giá " . number_format($total_payment) . "đ",
                                                                        'data_json' => '',
                                                                        'domain' => getDomain(),
                                                                    ]);
                                                                    return response()->json([
                                                                        'status' => 'success',
                                                                        'message' => 'Đặt hàng thành công',
                                                                        'order_id' => $order->id,
                                                                    ]);
                                                                } else {
                                                                    return response()->json([
                                                                        'status' => 'error',
                                                                        'message' => 'Đặt hàng thất bại',
                                                                    ]);
                                                                }
                                                            } else {
                                                                return response()->json([
                                                                    'status' => 'error',
                                                                    'message' => $result['message'],
                                                                ]);
                                                            }
                                                        }elseif ($server->actual_service == 'Smm(Quantity/1.5)') {
                                                            $smm = new CostDevideOnePointFive();
                                                            $link = $request->input('link_order');
                                                            $result = $smm->CreateOrder($request, $link, $quantity, $server->service_list, $total_payment);
                                                            if ($result['status']) {
                                                                $order_history = [
                                                                    'time' => Carbon::now()->format('H:i d/m/Y'),
                                                                    'status' => 'info',
                                                                    'title' => "Đơn hàng đã hoạt động",
                                                                ];
                                                                $order = Orders::create([
                                                                    'username' => $user->username,
                                                                    'service_id' => $service_->id,
                                                                    'service_name' => $service_->name,
                                                                    'server_service' => $request->server_service,
                                                                    'price' => $price,
                                                                    'quantity' => $request->quantity,
                                                                    'total_payment' => $total_payment,
                                                                    'order_code' => $result['data']['order'],
                                                                    'order_link' => $request->link_order,
                                                                    'start' => 0,
                                                                    'buff' => 0,
                                                                    'actual_service' => $server->actual_service,
                                                                    'actual_path' => $server->actual_path,
                                                                    'actual_server' => $server->actual_server,
                                                                    'status' => 'Active',
                                                                    'action' => json_encode([
                                                                        'link_order' => $request->link_order,
                                                                        'server_service' => $request->server_service,
                                                                        'quantity' => $request->quantity,
                                                                        'reaction' => $request->reaction ?? '',
                                                                        'speed' => $request->speed ?? '',
                                                                        'comment' => $request->comment ?? '',
                                                                        'minutes' => $request->minutes ?? '',
                                                                        'time' => $request->time ?? '',
                                                                    ]),
                                                                    'dataJson' => '',
                                                                    'isShow' => 1,
                                                                    'history' => json_encode([
                                                                        [
                                                                            'status' => 'primary',
                                                                            'title' => "Đơn hàng đã được tạo",
                                                                            'time' => Carbon::now()->format('H:i d/m/Y'),
                                                                        ]
                                                                    ]),
                                                                    'note' => $request->note ?? '',
                                                                    'domain' => getDomain(),
                                                                ]);

                                                                if ($order) {
                                                                    $order->order_code = $result['data']['order'] ?? '';
                                                                    $order->start = 0;
                                                                    $order->buff = 0;
                                                                    $order->status = 'Active';
                                                                    $order->dataJson = json_encode($result);
                                                                    $order->history = json_encode($order_history);
                                                                    $order->save();


                                                                    $balance = $user->balance;
                                                                    $user->balance = $user->balance - $total_payment;
                                                                    $user->total_deduct = $user->total_deduct + $total_payment;
                                                                    $user->save();
                                                                    DataHistory::create([
                                                                        'username' => $user->username,
                                                                        'action' => 'Tạo đơn',
                                                                        'data' => $total_payment,
                                                                        'old_data' => $balance,
                                                                        'new_data' => $user->balance,
                                                                        'ip' => $request->ip(),
                                                                        'description' => "Tạo đơn hàng " . $service_->name . " với số lượng " . $request->quantity . " với giá " . number_format($total_payment) . "đ",
                                                                        'data_json' => '',
                                                                        'domain' => getDomain(),
                                                                    ]);
                                                                    return response()->json([
                                                                        'status' => 'success',
                                                                        'message' => 'Đặt hàng thành công',
                                                                        'order_id' => $order->id,
                                                                    ]);
                                                                } else {
                                                                    return response()->json([
                                                                        'status' => 'error',
                                                                        'message' => 'Đặt hàng thất bại',
                                                                    ]);
                                                                }
                                                            } else {
                                                                return response()->json([
                                                                    'status' => 'error',
                                                                    'message' => $result['message'],
                                                                ]);
                                                            }
                                                        }elseif ($server->actual_service == 'Smm(Quantity-0%)') {
                                                            $smm = new CostNormal();
                                                            $link = $request->input('link_order');
                                                            $result = $smm->CreateOrder($request, $link, $quantity, $server->service_list, $total_payment);
                                                            if ($result['status']) {
                                                                $order_history = [
                                                                    'time' => Carbon::now()->format('H:i d/m/Y'),
                                                                    'status' => 'info',
                                                                    'title' => "Đơn hàng đã hoạt động",
                                                                ];
                                                                $order = Orders::create([
                                                                    'username' => $user->username,
                                                                    'service_id' => $service_->id,
                                                                    'service_name' => $service_->name,
                                                                    'server_service' => $request->server_service,
                                                                    'price' => $price,
                                                                    'quantity' => $request->quantity,
                                                                    'total_payment' => $total_payment,
                                                                    'order_code' => $result['data']['order'],
                                                                    'order_link' => $request->link_order,
                                                                    'start' => 0,
                                                                    'buff' => 0,
                                                                    'actual_service' => $server->actual_service,
                                                                    'actual_path' => $server->actual_path,
                                                                    'actual_server' => $server->actual_server,
                                                                    'status' => 'Active',
                                                                    'action' => json_encode([
                                                                        'link_order' => $request->link_order,
                                                                        'server_service' => $request->server_service,
                                                                        'quantity' => $request->quantity,
                                                                        'reaction' => $request->reaction ?? '',
                                                                        'speed' => $request->speed ?? '',
                                                                        'comment' => $request->comment ?? '',
                                                                        'minutes' => $request->minutes ?? '',
                                                                        'time' => $request->time ?? '',
                                                                    ]),
                                                                    'dataJson' => '',
                                                                    'isShow' => 1,
                                                                    'history' => json_encode([
                                                                        [
                                                                            'status' => 'primary',
                                                                            'title' => "Đơn hàng đã được tạo",
                                                                            'time' => Carbon::now()->format('H:i d/m/Y'),
                                                                        ]
                                                                    ]),
                                                                    'note' => $request->note ?? '',
                                                                    'domain' => getDomain(),
                                                                ]);

                                                                if ($order) {
                                                                    $order->order_code = $result['data']['order'] ?? '';
                                                                    $order->start = 0;
                                                                    $order->buff = 0;
                                                                    $order->status = 'Active';
                                                                    $order->dataJson = json_encode($result);
                                                                    $order->history = json_encode($order_history);
                                                                    $order->save();


                                                                    $balance = $user->balance;
                                                                    $user->balance = $user->balance - $total_payment;
                                                                    $user->total_deduct = $user->total_deduct + $total_payment;
                                                                    $user->save();
                                                                    DataHistory::create([
                                                                        'username' => $user->username,
                                                                        'action' => 'Tạo đơn',
                                                                        'data' => $total_payment,
                                                                        'old_data' => $balance,
                                                                        'new_data' => $user->balance,
                                                                        'ip' => $request->ip(),
                                                                        'description' => "Tạo đơn hàng " . $service_->name . " với số lượng " . $request->quantity . " với giá " . number_format($total_payment) . "đ",
                                                                        'data_json' => '',
                                                                        'domain' => getDomain(),
                                                                    ]);
                                                                    return response()->json([
                                                                        'status' => 'success',
                                                                        'message' => 'Đặt hàng thành công',
                                                                        'order_id' => $order->id,
                                                                    ]);
                                                                } else {
                                                                    return response()->json([
                                                                        'status' => 'error',
                                                                        'message' => 'Đặt hàng thất bại',
                                                                    ]);
                                                                }
                                                            } else {
                                                                return response()->json([
                                                                    'status' => 'error',
                                                                    'message' => $result['message'],
                                                                ]);
                                                            }
                                                        }elseif ($server->actual_service == 'jap') {
                                                            $smm = new JAPController();
                                                            $link = $request->input('link_order');
                                                            $result = $smm->CreateOrder($request, $link, $quantity, $server->service_list, $total_payment);
                                                            if ($result['status']) {
                                                                $order_history = [
                                                                    'time' => Carbon::now()->format('H:i d/m/Y'),
                                                                    'status' => 'info',
                                                                    'title' => "Đơn hàng đã hoạt động",
                                                                ];
                                                                $order = Orders::create([
                                                                    'username' => $user->username,
                                                                    'service_id' => $service_->id,
                                                                    'service_name' => $service_->name,
                                                                    'server_service' => $request->server_service,
                                                                    'price' => $price,
                                                                    'quantity' => $request->quantity,
                                                                    'total_payment' => $total_payment,
                                                                    'order_code' => $result['data']['order'],
                                                                    'order_link' => $request->link_order,
                                                                    'start' => 0,
                                                                    'buff' => 0,
                                                                    'actual_service' => $server->actual_service,
                                                                    'actual_path' => $server->actual_path,
                                                                    'actual_server' => $server->actual_server,
                                                                    'status' => 'Active',
                                                                    'action' => json_encode([
                                                                        'link_order' => $request->link_order,
                                                                        'server_service' => $request->server_service,
                                                                        'quantity' => $request->quantity,
                                                                        'reaction' => $request->reaction ?? '',
                                                                        'speed' => $request->speed ?? '',
                                                                        'comment' => $request->comment ?? '',
                                                                        'minutes' => $request->minutes ?? '',
                                                                        'time' => $request->time ?? '',
                                                                    ]),
                                                                    'dataJson' => '',
                                                                    'isShow' => 1,
                                                                    'history' => json_encode([
                                                                        [
                                                                            'status' => 'primary',
                                                                            'title' => "Đơn hàng đã được tạo",
                                                                            'time' => Carbon::now()->format('H:i d/m/Y'),
                                                                        ]
                                                                    ]),
                                                                    'note' => $request->note ?? '',
                                                                    'domain' => getDomain(),
                                                                ]);

                                                                if ($order) {
                                                                    $order->order_code = $result['data']['order'] ?? '';
                                                                    $order->start = 0;
                                                                    $order->buff = 0;
                                                                    $order->status = 'Active';
                                                                    $order->dataJson = json_encode($result);
                                                                    $order->history = json_encode($order_history);
                                                                    $order->save();


                                                                    $balance = $user->balance;
                                                                    $user->balance = $user->balance - $total_payment;
                                                                    $user->total_deduct = $user->total_deduct + $total_payment;
                                                                    $user->save();
                                                                    DataHistory::create([
                                                                        'username' => $user->username,
                                                                        'action' => 'Tạo đơn',
                                                                        'data' => $total_payment,
                                                                        'old_data' => $balance,
                                                                        'new_data' => $user->balance,
                                                                        'ip' => $request->ip(),
                                                                        'description' => "Tạo đơn hàng " . $service_->name . " với số lượng " . $request->quantity . " với giá " . number_format($total_payment) . "đ",
                                                                        'data_json' => '',
                                                                        'domain' => getDomain(),
                                                                    ]);
                                                                    return response()->json([
                                                                        'status' => 'success',
                                                                        'message' => 'Đặt hàng thành công',
                                                                        'order_id' => $order->id,
                                                                    ]);
                                                                } else {
                                                                    return response()->json([
                                                                        'status' => 'error',
                                                                        'message' => 'Đặt hàng thất bại',
                                                                    ]);
                                                                }
                                                            } else {
                                                                return response()->json([
                                                                    'status' => 'error',
                                                                    'message' => $result['message'],
                                                                ]);
                                                            }
                                                        } else {
                                                            return response()->json([
                                                                'status' => 'error',
                                                                'message' => 'Dữ liệu không hợp lệ vui lòng thử lại sau',
                                                                "error" => "O day"
                                                            ]);
                                                        }
                                                    } 
                                                    else {
                                                        $order = Orders::create([
                                                            'username' => $user->username,
                                                            'service_id' => $service_->id,
                                                            'service_name' => $service_->name,
                                                            'server_service' => $request->server_service,
                                                            'price' => $price,
                                                            'quantity' => $request->quantity,
                                                            'total_payment' => $total_payment,
                                                            'order_code' => '',
                                                            'order_link' => $request->link_order,
                                                            'start' => 0,
                                                            'buff' => 0,
                                                            'actual_service' => $server->actual_service,
                                                            'actual_path' => $server->actual_path,
                                                            'actual_server' => $server->actual_server,
                                                            'status' => 'Active',
                                                            'action' => json_encode([
                                                                'link_order' => $request->link_order,
                                                                'server_service' => $request->server_service,
                                                                'quantity' => $request->quantity,
                                                                'reaction' => $request->reaction ?? '',
                                                                'speed' => $request->speed ?? '',
                                                                'comment' => $request->comment ?? '',
                                                                'minutes' => $request->minutes ?? '',
                                                                'time' => $request->time ?? '',
                                                            ]),
                                                            'dataJson' => '',
                                                            'isShow' => 1,
                                                            'history' => json_encode([
                                                                [
                                                                    'status' => 'primary',
                                                                    'title' => "Đơn hàng đã được tạo",
                                                                    'time' => Carbon::now()->format('H:i d/m/Y'),
                                                                ]
                                                            ]),
                                                            'note' => $request->note ?? '',
                                                            'domain' => getDomain(),
                                                        ]);

                                                        if ($order) {
                                                            return response()->json([
                                                                'status' => 'success',
                                                                'message' => 'Đặt hàng thành công',
                                                                'order_id' => $order->id,
                                                            ]);
                                                        }
                                                    }
                                                }
                                            }
                                        }
                                    }
                                } else {
                                    return response()->json([
                                        'status' => 'error',
                                        'message' => 'Server không tồn tại'
                                    ]);
                                    die();
                                }
                            } else {
                                return response()->json([
                                    'status' => 'error',
                                    'message' => 'Dịch vụ không tồn tại'
                                ]);
                                die();
                            }
                        } else {
                            return response()->json([
                                'status' => 'error',
                                'message' => 'Dịch vụ Social không tồn tại'
                            ]);
                            die();
                        }
                    }
                } else {
                    return response()->json([
                        'status' => 'error',
                        'message' => 'Người dùng không tồn tại'
                    ]);
                }
            } else {
                $user = User::where('domain', getDomain())->where('api_token', $api_token)->first();
                if ($user) {
                    $valid = Validator::make($request->all(), [
                        'link_order' => 'required',
                        'server_service' => 'required',
                    ]);
                    if ($valid->fails()) {
                        return response()->json([
                            'status' => 'error',
                            'message' => $valid->errors()->first()
                        ]);
                    } else {
                        $admin = User::where('username', DataSite('username_web'))->where('domain', env('PARENT_SITE'))->first();
                        if ($admin) {
                            // $server_s = ServerService::where('domain', getDomain())->where('id', $request->server_service)->first();
                            $social_service = ServiceSocial::where('domain', env('PARENT_SITE'))->where('slug', $social)->first();
                            if ($social_service) {
                                $service_ = Service::where('domain', env('PARENT_SITE'))->where('slug', $service)->where('service_social', $social_service->slug)->first();
                                if ($service_) {
                                    $server = ServerService::where('domain', getDomain())->where('social_id', $social_service->id)->where('service_id', $service_->id)->where('server', $request->server_service)->first();
                                    $server_admin = ServerService::where('domain', env('PARENT_SITE'))->where('social_id', $social_service->id)->where('service_id', $service_->id)->where('server', $server->server)->first();
                                    if ($server && $server_admin) {
                                        if ($server->status != 'Active') {
                                            return response()->json([
                                                'status' => 'error',
                                                'message' => 'Server đang bảo trì hoặc ngừng nhận đơn'
                                            ]);
                                            die();
                                        } elseif ($server_admin->status != 'Active') {
                                            return response()->json([
                                                'status' => 'error',
                                                'message' => 'Server đang bảo trì hoặc ngừng nhận đơn'
                                            ]);
                                            die();
                                        } else {
                                            switch ($service_->category) {
                                                case 'default':
                                                    $validator = [
                                                        'link_order' => 'required',
                                                        'server_service' => 'required',
                                                        'quantity' => 'required|numeric',
                                                    ];
                                                    break;
                                                case 'reaction':
                                                    $validator = [
                                                        'link_order' => 'required',
                                                        'server_service' => 'required',
                                                        'quantity' => 'required|numeric',
                                                        'reaction' => 'required',
                                                    ];
                                                    break;
                                                case 'reaction-speed':
                                                    $validator = [
                                                        'link_order' => 'required',
                                                        'server_service' => 'required',
                                                        'quantity' => 'required|numeric',
                                                        'reaction' => 'required',
                                                        'speed' => 'required',
                                                    ];
                                                    break;
                                                case 'comment':
                                                    $validator = [
                                                        'link_order' => 'required',
                                                        'server_service' => 'required',
                                                        'comment' => 'required',
                                                    ];
                                                    break;
                                                case 'comment-quantity':
                                                    $validator = [
                                                        'link_order' => 'required',
                                                        'server_service' => 'required',
                                                        'comment' => 'required',
                                                        'quantity' => 'required|numeric',
                                                    ];
                                                    break;
                                                case 'minutes':
                                                    $validator = [
                                                        'link_order' => 'required',
                                                        'server_service' => 'required',
                                                        'quantity' => 'required|numeric',
                                                        'minutes' => 'required',
                                                    ];
                                                    break;
                                                case 'time':
                                                    $validator = [
                                                        'link_order' => 'required',
                                                        'server_service' => 'required',
                                                        'quantity' => 'required|numeric',
                                                        'time' => 'required',
                                                    ];
                                                    break;
                                                default:
                                                    $validator = [
                                                        'link_order' => 'required',
                                                        'server_service' => 'required',
                                                        'quantity' => 'required|numeric',
                                                    ];
                                                    break;
                                            }
                                            $valid = Validator::make($request->all(), $validator);
                                            if ($valid->fails()) {
                                                return response()->json([
                                                    'status' => 'error',
                                                    'message' => $valid->errors()->first()
                                                ]);
                                            } else {
                                                if ($service_->category == 'comment') {
                                                    $quantity = count(explode("\n", $request->comment));
                                                    $request->merge(['quantity' => $quantity]);
                                                }

                                                if ($server->min > $request->quantity) {
                                                    return response()->json([
                                                        'status' => 'error',
                                                        'message' => 'Số lượng tối thiểu là ' . $server->min
                                                    ]);
                                                } elseif ($server->max < $request->quantity) {
                                                    return response()->json([
                                                        'status' => 'error',
                                                        'message' => 'Số lượng tối đa là ' . $server->max
                                                    ]);
                                                } else {
                                                    $price = priceServer($server->id, $user->level);
                                                    $price_admin = priceServer($server_admin->id, $admin->level);
                                                    $total_payment = 0;
                                                    if ($service_->category == 'minutes') {
                                                        $total_payment = $price * $request->quantity * $request->minutes;
                                                        $total_payment_admin = $price_admin * $request->quantity * $request->minutes;
                                                    } else {
                                                        $total_payment = $price * $request->quantity;
                                                        $total_payment_admin = $price_admin * $request->quantity;
                                                    }
                                                    if ($user->balance < $total_payment) {
                                                        return response()->json([
                                                            'status' => 'error',
                                                            'message' => 'Số dư trong tài khoản không đủ'
                                                        ]);
                                                    } elseif ($admin->balance <= $total_payment_admin) {
                                                        dd($admin->balance);
                                                        return response()->json([
                                                            'status' => 'error',
                                                            'message' => 'Lỗi! liên hệ admin để biết thêm thông tin'
                                                        ]);
                                                    } else {
                                                        // echo $server_admin->id;
                                                        // die();
                                                        $dataArr = [
                                                            'link_order' => $request->link_order,
                                                            'server_service' => $server_admin->server,
                                                            'quantity' => $request->quantity,
                                                            'reaction' => $request->reaction ?? '',
                                                            'speed' => $request->speed ?? '',
                                                            'comment' => $request->comment ?? '',
                                                            'minutes' => $request->minutes ?? '',
                                                            'time' => $request->time ?? '',
                                                        ];

                                                        $dataArr = http_build_query($dataArr);

                                                        if (env('IS_ORDER') == true) {
                                                            $data_send = false;
                                                            $actual_path = $server->actual_path;
                                                            $actual_server = $server->actual_server;
                                                            $quantity = $request->quantity;
                                                            $order_link = $request->link_order;
                                                            if ($server->actual_service == 'subgiare') {

                                                                $subgiare = new SubgiareController();
                                                                $actual_path = $server->actual_path;
                                                                $actual_server = $server->actual_server;
                                                                $quantity = $request->quantity;
                                                                $order_link = $request->link_order;
                                                                $subgiare = new SubgiareController();
                                                                $subgiare->path = $actual_path;
                                                                $subgiare->data = [
                                                                    'order_link' => $order_link,
                                                                    'quantity' => $quantity,
                                                                    'speed' => $request->speed ?? '0',
                                                                    'comment' => $request->comment ?? '',
                                                                    'minutes' => $request->minutes ?? '',
                                                                    'time' => $request->time ?? '',
                                                                    'reaction' => $request->reaction ?? '',
                                                                    'server_order' => $actual_server,
                                                                ];
                                                                // if($admin->balance)
                                                                $result = $subgiare->CreateOrder();
                                                                if ($result['status'] == true) {
                                                                    $order_history = [
                                                                        'time' => Carbon::now()->format('H:i d/m/Y'),
                                                                        'status' => 'info',
                                                                        'title' => "Đơn hàng đang hoạt động",
                                                                    ];
                                                                    $order = Orders::create([
                                                                        'username' => $user->username,
                                                                        'service_id' => $service_->id,
                                                                        'service_name' => $service_->name,
                                                                        'server_service' => $request->server_service,
                                                                        'price' => $price,
                                                                        'quantity' => $request->quantity,
                                                                        'total_payment' => $total_payment,
                                                                        'order_code' => '',
                                                                        'order_link' => $request->link_order,
                                                                        'start' => 0,
                                                                        'buff' => 0,
                                                                        'actual_service' => $server->actual_service,
                                                                        'actual_path' => $server->actual_path,
                                                                        'actual_server' => $server->actual_server,
                                                                        'status' => 'Active',
                                                                        'action' => json_encode([
                                                                            'link_order' => $request->link_order,
                                                                            'server_service' => $request->server_service,
                                                                            'quantity' => $request->quantity,
                                                                            'reaction' => $request->reaction ?? '',
                                                                            'speed' => $request->speed ?? '',
                                                                            'comment' => $request->comment ?? '',
                                                                            'minutes' => $request->minutes ?? '',
                                                                            'time' => $request->time ?? '',
                                                                        ]),
                                                                        'dataJson' => '',
                                                                        'isShow' => 1,
                                                                        'history' => json_encode([
                                                                            [
                                                                                'status' => 'primary',
                                                                                'title' => "Đơn hàng đã được tạo",
                                                                                'time' => Carbon::now()->format('H:i d/m/Y'),
                                                                            ]
                                                                        ]),
                                                                        'note' => $request->note ?? '',
                                                                        'domain' => getDomain(),
                                                                    ]);

                                                                    if ($order) {
                                                                        $order->order_code = $result['data']['order'];
                                                                        $order->start = $result['data']['start'] ?? 0;
                                                                        $order->buff = $result['data']['buff'] ?? 0;
                                                                        $order->status = 'Active';
                                                                        $order->dataJson = json_encode($result['data']);
                                                                        $order->history = json_encode($order_history);
                                                                        $order->save();

                                                                        $balance = $user->balance;
                                                                        $user->balance = $user->balance - $total_payment;
                                                                        $admin->balance = $admin->balance - $total_payment;
                                                                        $admin->save();
                                                                        $user->total_deduct = $user->total_deduct + $total_payment;
                                                                        $user->save();
                                                                        DataHistory::create([
                                                                            'username' => $user->username,
                                                                            'action' => 'Tạo đơn',
                                                                            'data' => $total_payment,
                                                                            'old_data' => $balance,
                                                                            'new_data' => $user->balance,
                                                                            'ip' => $request->ip(),
                                                                            'description' => "Tạo đơn hàng " . $service_->name . " với số lượng " . $request->quantity . " với giá " . number_format($total_payment) . "đ",
                                                                            'data_json' => '',
                                                                            'domain' => getDomain(),
                                                                        ]);
                                                                        return response()->json([
                                                                            'status' => 'success',
                                                                            'message' => 'Đặt hàng thành công',
                                                                            'order_id' => $order->id,
                                                                        ]);
                                                                    } else {
                                                                        return response()->json([
                                                                            'status' => 'error',
                                                                            'message' => 'Đặt hàng thất bại',
                                                                        ]);
                                                                    }
                                                                } else {
                                                                    return response()->json([
                                                                        'status' => 'error',
                                                                        'message' => $result['message'],
                                                                    ]);
                                                                }
                                                            } elseif ($server->actual_service == 'hacklike17') {
                                                                $hacklike17 = new Hacklike17Controller();
                                                                $hacklike17->path = $actual_path;
                                                                $hacklike17->data = [
                                                                    'order_link' => $order_link,
                                                                    'quantity' => $quantity,
                                                                    'speed' => $request->speed ?? '0',
                                                                    'comment' => $request->comment ?? '',
                                                                    'minutes' => $request->minutes ?? '',
                                                                    'time' => $request->time ?? '',
                                                                    'reaction' => $request->reaction ?? '',
                                                                    'server_order' => $actual_server,
                                                                ];
                                                                $result = $hacklike17->CreateOrder();
                                                                if ($result['status'] == true) {
                                                                    // thêm array vào history
                                                                    $order_history = [
                                                                        'time' => Carbon::now()->format('H:i d/m/Y'),
                                                                        'status' => 'info',
                                                                        'title' => "Đơn hàng đã hoạt động",
                                                                    ];
                                                                    $order = Orders::create([
                                                                        'username' => $user->username,
                                                                        'service_id' => $service_->id,
                                                                        'service_name' => $service_->name,
                                                                        'server_service' => $request->server_service,
                                                                        'price' => $price,
                                                                        'quantity' => $request->quantity,
                                                                        'total_payment' => $total_payment,
                                                                        'order_code' => '',
                                                                        'order_link' => $request->link_order,
                                                                        'start' => 0,
                                                                        'buff' => 0,
                                                                        'actual_service' => $server->actual_service,
                                                                        'actual_path' => $server->actual_path,
                                                                        'actual_server' => $server->actual_server,
                                                                        'status' => 'Active',
                                                                        'action' => json_encode([
                                                                            'link_order' => $request->link_order,
                                                                            'server_service' => $request->server_service,
                                                                            'quantity' => $request->quantity,
                                                                            'reaction' => $request->reaction ?? '',
                                                                            'speed' => $request->speed ?? '',
                                                                            'comment' => $request->comment ?? '',
                                                                            'minutes' => $request->minutes ?? '',
                                                                            'time' => $request->time ?? '',
                                                                        ]),
                                                                        'dataJson' => '',
                                                                        'isShow' => 1,
                                                                        'history' => json_encode([
                                                                            [
                                                                                'status' => 'primary',
                                                                                'title' => "Đơn hàng đã được tạo",
                                                                                'time' => Carbon::now()->format('H:i d/m/Y'),
                                                                            ]
                                                                        ]),
                                                                        'note' => $request->note ?? '',
                                                                        'domain' => getDomain(),
                                                                    ]);
                                                                    $balance = $user->balance;
                                                                    $user->balance = $user->balance - $total_payment;
                                                                    $user->total_deduct = $user->total_deduct + $total_payment;
                                                                    $admin->balance = $admin->balance - $total_payment;
                                                                    $admin->save();
                                                                    $user->save();
                                                                    DataHistory::create([
                                                                        'username' => $user->username,
                                                                        'action' => 'Tạo đơn',
                                                                        'data' => $total_payment,
                                                                        'old_data' => $balance,
                                                                        'new_data' => $user->balance,
                                                                        'ip' => $request->ip(),
                                                                        'description' => "Tạo đơn hàng " . $service_->name . " với số lượng " . $request->quantity . " với giá " . number_format($total_payment) . "đ",
                                                                        'data_json' => '',
                                                                        'domain' => getDomain(),
                                                                    ]);
                                                                    if ($order) {
                                                                        $order->order_code = $result['data']['code_order'];
                                                                        $order->start = $result['data']['start'] ?? 0;
                                                                        $order->buff = $result['data']['buff'] ?? 0;
                                                                        $order->status = 'Active';
                                                                        $order->dataJson = json_encode($result['data']);
                                                                        $order->history = json_encode($order_history);
                                                                        $order->save();
                                                                        return response()->json([
                                                                            'status' => 'success',
                                                                            'message' => 'Đặt hàng thành công',
                                                                            'order_id' => $order->id,
                                                                        ]);
                                                                    } else {
                                                                        return response()->json([
                                                                            'status' => 'error',
                                                                            'message' => 'Đặt hàng thất bại',
                                                                        ]);
                                                                    }
                                                                } else {
                                                                    return response()->json([
                                                                        'status' => 'error',
                                                                        'message' => $result['message'],
                                                                    ]);
                                                                }
                                                            } elseif ($server->actual_service == '2mxh') {
                                                                $twomxh = new TwoMxhController();
                                                                $twomxh->path = $actual_path;
                                                                $twomxh->data = [
                                                                    'order_link' => $order_link,
                                                                    'quantity' => $quantity,
                                                                    'speed' => $request->speed ?? '0',
                                                                    'comment' => $request->comment ?? '',
                                                                    'minutes' => $request->minutes ?? '',
                                                                    'time' => $request->time ?? '',
                                                                    'reaction' => $request->reaction ?? '',
                                                                    'server_order' => $actual_server,
                                                                ];
                                                                $result = $twomxh->CreateOrder();
                                                                if ($result['status'] == true) {
                                                                    $order_history = [
                                                                        'time' => Carbon::now()->format('H:i d/m/Y'),
                                                                        'status' => 'info',
                                                                        'title' => "Đơn hàng đã hoạt động",
                                                                    ];
                                                                    $order = Orders::create([
                                                                        'username' => $user->username,
                                                                        'service_id' => $service_->id,
                                                                        'service_name' => $service_->name,
                                                                        'server_service' => $request->server_service,
                                                                        'price' => $price,
                                                                        'quantity' => $request->quantity,
                                                                        'total_payment' => $total_payment,
                                                                        'order_code' => '',
                                                                        'order_link' => $request->link_order,
                                                                        'start' => 0,
                                                                        'buff' => 0,
                                                                        'actual_service' => $server->actual_service,
                                                                        'actual_path' => $server->actual_path,
                                                                        'actual_server' => $server->actual_server,
                                                                        'status' => 'Active',
                                                                        'action' => json_encode([
                                                                            'link_order' => $request->link_order,
                                                                            'server_service' => $request->server_service,
                                                                            'quantity' => $request->quantity,
                                                                            'reaction' => $request->reaction ?? '',
                                                                            'speed' => $request->speed ?? '',
                                                                            'comment' => $request->comment ?? '',
                                                                            'minutes' => $request->minutes ?? '',
                                                                            'time' => $request->time ?? '',
                                                                        ]),
                                                                        'dataJson' => '',
                                                                        'isShow' => 1,
                                                                        'history' => json_encode([
                                                                            [
                                                                                'status' => 'primary',
                                                                                'title' => "Đơn hàng đã được tạo",
                                                                                'time' => Carbon::now()->format('H:i d/m/Y'),
                                                                            ]
                                                                        ]),
                                                                        'note' => $request->note ?? '',
                                                                        'domain' => getDomain(),
                                                                    ]);

                                                                    if ($order) {
                                                                        $order->order_code = $result['data']['order']['order_id'];
                                                                        $order->start = $result['data']['order']['start_num'] ?? 0;
                                                                        $order->buff = 0;
                                                                        $order->status = 'Active';
                                                                        $order->dataJson = json_encode($result['data']);
                                                                        $order->history = json_encode($order_history);
                                                                        $order->save();


                                                                        $balance = $user->balance;
                                                                        $user->balance = $user->balance - $total_payment;
                                                                        $admin->balance = $admin->balance - $total_payment;
                                                                        $admin->save();
                                                                        $user->total_deduct = $user->total_deduct + $total_payment;
                                                                        $user->save();
                                                                        DataHistory::create([
                                                                            'username' => $user->username,
                                                                            'action' => 'Tạo đơn',
                                                                            'data' => $total_payment,
                                                                            'old_data' => $balance,
                                                                            'new_data' => $user->balance,
                                                                            'ip' => $request->ip(),
                                                                            'description' => "Tạo đơn hàng " . $service_->name . " với số lượng " . $request->quantity . " với giá " . number_format($total_payment) . "đ",
                                                                            'data_json' => '',
                                                                            'domain' => getDomain(),
                                                                        ]);
                                                                        return response()->json([
                                                                            'status' => 'success',
                                                                            'message' => 'Đặt hàng thành công',
                                                                            'order_id' => $order->id,
                                                                        ]);
                                                                    } else {
                                                                        return response()->json([
                                                                            'status' => 'error',
                                                                            'message' => 'Đặt hàng thất bại',
                                                                        ]);
                                                                    }
                                                                } else {
                                                                    return response()->json([
                                                                        'status' => 'error',
                                                                        'message' => 'Lỗi!',
                                                                    ]);
                                                                }
                                                            } elseif ($server->actual_service == '1dg') {
                                                                $onedg = new OneDgController();
                                                                $data = [
                                                                    'service' => $actual_server,
                                                                    'link' => $order_link,
                                                                    'quantity' => $quantity,
                                                                    'comments' => $request->comment ?? '',
                                                                ];
                                                                $result = $onedg->order($data);
                                                                if (isset($result['order'])) {

                                                                    $order_history = [
                                                                        'time' => Carbon::now()->format('H:i d/m/Y'),
                                                                        'status' => 'info',
                                                                        'title' => "Đơn hàng đã hoạt động",
                                                                    ];
                                                                    $order = Orders::create([
                                                                        'username' => $user->username,
                                                                        'service_id' => $service_->id,
                                                                        'service_name' => $service_->name,
                                                                        'server_service' => $request->server_service,
                                                                        'price' => $price,
                                                                        'quantity' => $request->quantity,
                                                                        'total_payment' => $total_payment,
                                                                        'order_code' => '',
                                                                        'order_link' => $request->link_order,
                                                                        'start' => 0,
                                                                        'buff' => 0,
                                                                        'actual_service' => $server->actual_service,
                                                                        'actual_path' => $server->actual_path,
                                                                        'actual_server' => $server->actual_server,
                                                                        'status' => 'Active',
                                                                        'action' => json_encode([
                                                                            'link_order' => $request->link_order,
                                                                            'server_service' => $request->server_service,
                                                                            'quantity' => $request->quantity,
                                                                            'reaction' => $request->reaction ?? '',
                                                                            'speed' => $request->speed ?? '',
                                                                            'comment' => $request->comment ?? '',
                                                                            'minutes' => $request->minutes ?? '',
                                                                            'time' => $request->time ?? '',
                                                                        ]),
                                                                        'dataJson' => '',
                                                                        'isShow' => 1,
                                                                        'history' => json_encode([
                                                                            [
                                                                                'status' => 'primary',
                                                                                'title' => "Đơn hàng đã được tạo",
                                                                                'time' => Carbon::now()->format('H:i d/m/Y'),
                                                                            ]
                                                                        ]),
                                                                        'note' => $request->note ?? '',
                                                                        'domain' => getDomain(),
                                                                    ]);

                                                                    if ($order) {
                                                                        $order->order_code = $result['order'];
                                                                        $order->start = 0;
                                                                        $order->buff = 0;
                                                                        $order->status = 'Active';
                                                                        $order->dataJson = json_encode($result);
                                                                        $order->history = json_encode($order_history);
                                                                        $order->save();

                                                                        $balance = $user->balance;
                                                                        $user->balance = $user->balance - $total_payment;
                                                                        $admin->balance = $admin->balance - $total_payment;
                                                                        $admin->save();
                                                                        $user->total_deduct = $user->total_deduct + $total_payment;
                                                                        $user->save();
                                                                        DataHistory::create([
                                                                            'username' => $user->username,
                                                                            'action' => 'Tạo đơn',
                                                                            'data' => $total_payment,
                                                                            'old_data' => $balance,
                                                                            'new_data' => $user->balance,
                                                                            'ip' => $request->ip(),
                                                                            'description' => "Tạo đơn hàng " . $service_->name . " với số lượng " . $request->quantity . " với giá " . number_format($total_payment) . "đ",
                                                                            'data_json' => '',
                                                                            'domain' => getDomain(),
                                                                        ]);
                                                                        return response()->json([
                                                                            'status' => 'success',
                                                                            'message' => "Đặt hàng thành công",
                                                                            'order_id' => $order->id,
                                                                        ]);
                                                                    } else {
                                                                        return response()->json([
                                                                            'status' => 'error',
                                                                            'message' => 'Đặt hàng thất bại',
                                                                        ]);
                                                                    }
                                                                } else {
                                                                    return response()->json([
                                                                        'status' => 'error',
                                                                        'message' => $result['error'],
                                                                    ]);
                                                                }
                                                            } 
                                                            elseif ($server->actual_service == 'traodoisub') {
                                                                $tds = new TraodoisubController();

                                                                $tds->path = $actual_path;

                                                                $tds->data = [
                                                                    'order_link' => $order_link,
                                                                    'quantity' => $quantity,
                                                                    'speed' => $request->speed ?? '0',
                                                                    'comment' => $request->comment ?? '',
                                                                    'minutes' => $request->minutes ?? '',
                                                                    'time' => $request->time ?? '',
                                                                    'reaction' => $request->reaction ?? '',
                                                                    'server_order' => $actual_server,
                                                                ];

                                                                $result = $tds->createOrder();
                                                                if ($result['status'] == true) {
                                                                    $order_history = [
                                                                        'time' => Carbon::now()->format('H:i d/m/Y'),
                                                                        'status' => 'info',
                                                                        'title' => "Đơn hàng đã hoạt động",
                                                                    ];
                                                                    $order = Orders::create([
                                                                        'username' => $user->username,
                                                                        'service_id' => $service_->id,
                                                                        'service_name' => $service_->name,
                                                                        'server_service' => $request->server_service,
                                                                        'price' => $price,
                                                                        'quantity' => $request->quantity,
                                                                        'total_payment' => $total_payment,
                                                                        'order_code' => '',
                                                                        'order_link' => $request->link_order,
                                                                        'start' => 0,
                                                                        'buff' => 0,
                                                                        'actual_service' => $server->actual_service,
                                                                        'actual_path' => $server->actual_path,
                                                                        'actual_server' => $server->actual_server,
                                                                        'status' => 'Active',
                                                                        'action' => json_encode([
                                                                            'link_order' => $request->link_order,
                                                                            'server_service' => $request->server_service,
                                                                            'quantity' => $request->quantity,
                                                                            'reaction' => $request->reaction ?? '',
                                                                            'speed' => $request->speed ?? '',
                                                                            'comment' => $request->comment ?? '',
                                                                            'minutes' => $request->minutes ?? '',
                                                                            'time' => $request->time ?? '',
                                                                        ]),
                                                                        'dataJson' => '',
                                                                        'isShow' => 1,
                                                                        'history' => json_encode([
                                                                            [
                                                                                'status' => 'primary',
                                                                                'title' => "Đơn hàng đã được tạo",
                                                                                'time' => Carbon::now()->format('H:i d/m/Y'),
                                                                            ]
                                                                        ]),
                                                                        'note' => $request->note ?? '',
                                                                        'domain' => getDomain(),
                                                                    ]);

                                                                    if ($order) {
                                                                        $order->start = 0;
                                                                        $order->buff = 0;
                                                                        $order->status = 'Active';
                                                                        $order->dataJson = json_encode($result);
                                                                        $order->history = json_encode($order_history);
                                                                        $order->save();


                                                                        $balance = $user->balance;
                                                                        $user->balance = $user->balance - $total_payment;
                                                                        $admin->balance = $admin->balance - $total_payment;
                                                                        $admin->save();
                                                                        $user->total_deduct = $user->total_deduct + $total_payment;
                                                                        $user->save();
                                                                        DataHistory::create([
                                                                            'username' => $user->username,
                                                                            'action' => 'Tạo đơn',
                                                                            'data' => $total_payment,
                                                                            'old_data' => $balance,
                                                                            'new_data' => $user->balance,
                                                                            'ip' => $request->ip(),
                                                                            'description' => "Tạo đơn hàng " . $service_->name . " với số lượng " . $request->quantity . " với giá " . number_format($total_payment) . "đ",
                                                                            'data_json' => '',
                                                                            'domain' => getDomain(),
                                                                        ]);

                                                                        return response()->json([
                                                                            'status' => 'success',
                                                                            'message' => 'Đặt hàng thành công',
                                                                            'order_id' => $order->id,
                                                                        ]);
                                                                    } else {
                                                                        return response()->json([
                                                                            'status' => 'error',
                                                                            'message' => 'Đặt hàng thất bại',
                                                                        ]);
                                                                    }
                                                                } else {
                                                                    return response()->json([
                                                                        'status' => 'error',
                                                                        'message' => $result['message'],
                                                                    ]);
                                                                }
                                                            }
                                                            elseif ($server->actual_service == 'traodoisublike') {
                                                                $tds = new TraodoisubControllerLike();

                                                                $tds->path = $actual_path;

                                                                $tds->data = [
                                                                    'order_link' => $order_link,
                                                                    'quantity' => $quantity,
                                                                    'speed' => $request->speed ?? '0',
                                                                    'comment' => $request->comment ?? '',
                                                                    'minutes' => $request->minutes ?? '',
                                                                    'time' => $request->time ?? '',
                                                                    'reaction' => $request->reaction ?? '',
                                                                    'server_order' => $actual_server,
                                                                ];

                                                                $result = $tds->createOrder();
                                                                if ($result['status'] == true) {
                                                                    $order_history = [
                                                                        'time' => Carbon::now()->format('H:i d/m/Y'),
                                                                        'status' => 'info',
                                                                        'title' => "Đơn hàng đã hoạt động",
                                                                    ];
                                                                    $order = Orders::create([
                                                                        'username' => $user->username,
                                                                        'service_id' => $service_->id,
                                                                        'service_name' => $service_->name,
                                                                        'server_service' => $request->server_service,
                                                                        'price' => $price,
                                                                        'quantity' => $request->quantity,
                                                                        'total_payment' => $total_payment,
                                                                        'order_code' => '',
                                                                        'order_link' => $request->link_order,
                                                                        'start' => 0,
                                                                        'buff' => 0,
                                                                        'actual_service' => $server->actual_service,
                                                                        'actual_path' => $server->actual_path,
                                                                        'actual_server' => $server->actual_server,
                                                                        'status' => 'Active',
                                                                        'action' => json_encode([
                                                                            'link_order' => $request->link_order,
                                                                            'server_service' => $request->server_service,
                                                                            'quantity' => $request->quantity,
                                                                            'reaction' => $request->reaction ?? '',
                                                                            'speed' => $request->speed ?? '',
                                                                            'comment' => $request->comment ?? '',
                                                                            'minutes' => $request->minutes ?? '',
                                                                            'time' => $request->time ?? '',
                                                                        ]),
                                                                        'dataJson' => '',
                                                                        'isShow' => 1,
                                                                        'history' => json_encode([
                                                                            [
                                                                                'status' => 'primary',
                                                                                'title' => "Đơn hàng đã được tạo",
                                                                                'time' => Carbon::now()->format('H:i d/m/Y'),
                                                                            ]
                                                                        ]),
                                                                        'note' => $request->note ?? '',
                                                                        'domain' => getDomain(),
                                                                    ]);

                                                                    if ($order) {
                                                                        $order->start = 0;
                                                                        $order->buff = 0;
                                                                        $order->status = 'Active';
                                                                        $order->dataJson = json_encode($result);
                                                                        $order->history = json_encode($order_history);
                                                                        $order->save();


                                                                        $balance = $user->balance;
                                                                        $user->balance = $user->balance - $total_payment;
                                                                        $admin->balance = $admin->balance - $total_payment;
                                                                        $admin->save();
                                                                        $user->total_deduct = $user->total_deduct + $total_payment;
                                                                        $user->save();
                                                                        DataHistory::create([
                                                                            'username' => $user->username,
                                                                            'action' => 'Tạo đơn',
                                                                            'data' => $total_payment,
                                                                            'old_data' => $balance,
                                                                            'new_data' => $user->balance,
                                                                            'ip' => $request->ip(),
                                                                            'description' => "Tạo đơn hàng " . $service_->name . " với số lượng " . $request->quantity . " với giá " . number_format($total_payment) . "đ",
                                                                            'data_json' => '',
                                                                            'domain' => getDomain(),
                                                                        ]);

                                                                        return response()->json([
                                                                            'status' => 'success',
                                                                            'message' => 'Đặt hàng thành công',
                                                                            'order_id' => $order->id,
                                                                        ]);
                                                                    } else {
                                                                        return response()->json([
                                                                            'status' => 'error',
                                                                            'message' => 'Đặt hàng thất bại',
                                                                        ]);
                                                                    }
                                                                } else {
                                                                    return response()->json([
                                                                        'status' => 'error',
                                                                        'message' => $result['message'],
                                                                    ]);
                                                                }
                                                            }
                                                            elseif ($server->actual_service == 'tuongtaccheo') {
                                                                $tds = new Tuongtaccheo();

                                                                $tds->path = $actual_path;

                                                                $tds->data = [
                                                                    'order_link' => $order_link,
                                                                    'quantity' => $quantity,
                                                                    'speed' => $request->speed ?? '0',
                                                                    'comment' => $request->comment ?? '',
                                                                    'minutes' => $request->minutes ?? '',
                                                                    'time' => $request->time ?? '',
                                                                    'reaction' => $request->reaction ?? '',
                                                                    'server_order' => $actual_server,
                                                                ];

                                                                $result = $tds->createOrder();
                                                                if ($result['status'] == true) {
                                                                    $order_history = [
                                                                        'time' => Carbon::now()->format('H:i d/m/Y'),
                                                                        'status' => 'info',
                                                                        'title' => "Đơn hàng đã hoạt động",
                                                                    ];
                                                                    $order = Orders::create([
                                                                        'username' => $user->username,
                                                                        'service_id' => $service_->id,
                                                                        'service_name' => $service_->name,
                                                                        'server_service' => $request->server_service,
                                                                        'price' => $price,
                                                                        'quantity' => $request->quantity,
                                                                        'total_payment' => $total_payment,
                                                                        'order_code' => '',
                                                                        'order_link' => $request->link_order,
                                                                        'start' => 0,
                                                                        'buff' => 0,
                                                                        'actual_service' => $server->actual_service,
                                                                        'actual_path' => $server->actual_path,
                                                                        'actual_server' => $server->actual_server,
                                                                        'status' => 'Active',
                                                                        'action' => json_encode([
                                                                            'link_order' => $request->link_order,
                                                                            'server_service' => $request->server_service,
                                                                            'quantity' => $request->quantity,
                                                                            'reaction' => $request->reaction ?? '',
                                                                            'speed' => $request->speed ?? '',
                                                                            'comment' => $request->comment ?? '',
                                                                            'minutes' => $request->minutes ?? '',
                                                                            'time' => $request->time ?? '',
                                                                        ]),
                                                                        'dataJson' => '',
                                                                        'isShow' => 1,
                                                                        'history' => json_encode([
                                                                            [
                                                                                'status' => 'primary',
                                                                                'title' => "Đơn hàng đã được tạo",
                                                                                'time' => Carbon::now()->format('H:i d/m/Y'),
                                                                            ]
                                                                        ]),
                                                                        'note' => $request->note ?? '',
                                                                        'domain' => getDomain(),
                                                                    ]);

                                                                    if ($order) {
                                                                        $order->start = 0;
                                                                        $order->buff = 0;
                                                                        $order->status = 'Active';
                                                                        $order->dataJson = json_encode($result);
                                                                        $order->history = json_encode($order_history);
                                                                        $order->save();


                                                                        $balance = $user->balance;
                                                                        $user->balance = $user->balance - $total_payment;
                                                                        $admin->balance = $admin->balance - $total_payment;
                                                                        $admin->save();
                                                                        $user->total_deduct = $user->total_deduct + $total_payment;
                                                                        $user->save();
                                                                        DataHistory::create([
                                                                            'username' => $user->username,
                                                                            'action' => 'Tạo đơn',
                                                                            'data' => $total_payment,
                                                                            'old_data' => $balance,
                                                                            'new_data' => $user->balance,
                                                                            'ip' => $request->ip(),
                                                                            'description' => "Tạo đơn hàng " . $service_->name . " với số lượng " . $request->quantity . " với giá " . number_format($total_payment) . "đ",
                                                                            'data_json' => '',
                                                                            'domain' => getDomain(),
                                                                        ]);

                                                                        return response()->json([
                                                                            'status' => 'success',
                                                                            'message' => 'Đặt hàng thành công',
                                                                            'order_id' => $order->id,
                                                                        ]);
                                                                    } else {
                                                                        return response()->json([
                                                                            'status' => 'error',
                                                                            'message' => 'Đặt hàng thất bại',
                                                                        ]);
                                                                    }
                                                                } else {
                                                                    return response()->json([
                                                                        'status' => 'error',
                                                                        'message' => $result['message'],
                                                                    ]);
                                                                }
                                                            }
                                                             elseif ($server->actual_service == 'dontay') {
                                                                $order = Orders::create([
                                                                    'username' => $user->username,
                                                                    'service_id' => $service_->id,
                                                                    'service_name' => $service_->name,
                                                                    'server_service' => $request->server_service,
                                                                    'price' => $price,
                                                                    'quantity' => $request->quantity,
                                                                    'total_payment' => $total_payment,
                                                                    'order_code' => '',
                                                                    'order_link' => $request->link_order,
                                                                    'start' => 0,
                                                                    'buff' => 0,
                                                                    'actual_service' => $server->actual_service,
                                                                    'actual_path' => $server->actual_path,
                                                                    'actual_server' => $server->actual_server,
                                                                    'status' => 'Pending',
                                                                    'action' => json_encode([
                                                                        'link_order' => $request->link_order,
                                                                        'server_service' => $request->server_service,
                                                                        'quantity' => $request->quantity,
                                                                        'reaction' => $request->reaction ?? '',
                                                                        'speed' => $request->speed ?? '',
                                                                        'comment' => $request->comment ?? '',
                                                                        'minutes' => $request->minutes ?? '',
                                                                        'time' => $request->time ?? '',
                                                                    ]),
                                                                    'dataJson' => '',
                                                                    'isShow' => 1,
                                                                    'history' => json_encode([
                                                                        [
                                                                            'status' => 'primary',
                                                                            'title' => "Đơn hàng đã được tạo",
                                                                            'time' => Carbon::now()->format('H:i d/m/Y'),
                                                                        ]
                                                                    ]),
                                                                    'note' => $request->note ?? '',
                                                                    'domain' => getDomain(),
                                                                ]);

                                                                if ($order) {
                                                                    // send telegram
                                                                    if (DataSite('telegram_chat_id')) {
                                                                        $tele = new TelegramCustomController();
                                                                        $bot = $tele->bot();
                                                                        $bot->sendMessage([
                                                                            'chat_id' => DataSite('telegram_chat_id'),
                                                                            'text' => "🔔  Đơn hàng mới\nThành viên: " . $user->username . "\nDịch vụ: " . $service_->name . "\nCấp bậc: " . $user->level . "\nMáy chủ: " . $request->server_service . "\nSố lượng: " . $request->quantity . "\nGiá: " . number_format($total_payment) . "\nBalance: " . number_format($user->balance) . "đ\nLink: " . $request->link_order . "\nComment:\n" . $request->comment . "\nLoại cảm xúc: " . $request->reaction . "\nNote: " . $request->note . "\nTime: " . $request->minutes . "\nĐơn hàng từ: " . getDomain(),
                                                                        ]);
                                                                    }
                                                                    $balance = $user->balance;
                                                                    $user->balance = $user->balance - $total_payment;
                                                                    $user->total_deduct = $user->total_deduct + $total_payment;
                                                                    $admin->balance = $admin->balance - $total_payment;
                                                                    $admin->save();
                                                                    $user->save();
                                                                    return response()->json([
                                                                        'status' => 'success',
                                                                        'message' => 'Đặt hàng thành công',
                                                                        'order_id' => $order->id,
                                                                    ]);
                                                                }
                                                            }elseif ($server->actual_service == 'smmflare') {
                                                                $smm = new SmmFlarController();
                                                                $result = $smm->CreateOrder($order_link, $quantity, $server->service_list);
                                                                if ($result['status']) {
                                                                    $order_history = [
                                                                        'time' => Carbon::now()->format('H:i d/m/Y'),
                                                                        'status' => 'info',
                                                                        'title' => "Đơn hàng đã hoạt động",
                                                                    ];
                                                                    $order = Orders::create([
                                                                        'username' => $user->username,
                                                                        'service_id' => $service_->id,
                                                                        'service_name' => $service_->name,
                                                                        'server_service' => $request->server_service,
                                                                        'price' => $price,
                                                                        'quantity' => $request->quantity,
                                                                        'total_payment' => $total_payment,
                                                                        'order_code' => $result['data']['order'],
                                                                        'order_link' => $request->link_order,
                                                                        'start' => 0,
                                                                        'buff' => 0,
                                                                        'actual_service' => $server->actual_service,
                                                                        'actual_path' => $server->actual_path,
                                                                        'actual_server' => $server->actual_server,
                                                                        'status' => 'Active',
                                                                        'action' => json_encode([
                                                                            'link_order' => $request->link_order,
                                                                            'server_service' => $request->server_service,
                                                                            'quantity' => $request->quantity,
                                                                            'reaction' => $request->reaction ?? '',
                                                                            'speed' => $request->speed ?? '',
                                                                            'comment' => $request->comment ?? '',
                                                                            'minutes' => $request->minutes ?? '',
                                                                            'time' => $request->time ?? '',
                                                                        ]),
                                                                        'dataJson' => '',
                                                                        'isShow' => 1,
                                                                        'history' => json_encode([
                                                                            [
                                                                                'status' => 'primary',
                                                                                'title' => "Đơn hàng đã được tạo",
                                                                                'time' => Carbon::now()->format('H:i d/m/Y'),
                                                                            ]
                                                                        ]),
                                                                        'note' => $request->note ?? '',
                                                                        'domain' => getDomain(),
                                                                    ]);
    
                                                                    if ($order) {
                                                                        $order->order_code = $result['data']['order'] ?? '';
                                                                        $order->start = 0;
                                                                        $order->buff = 0;
                                                                        $order->status = 'Active';
                                                                        $order->dataJson = json_encode($result);
                                                                        $order->history = json_encode($order_history);
                                                                        $order->save();
    
    
                                                                        $balance = $user->balance;
                                                                        $user->balance = $user->balance - $total_payment;
                                                                        $user->total_deduct = $user->total_deduct + $total_payment;
                                                                        $user->save();
                                                                        DataHistory::create([
                                                                            'username' => $user->username,
                                                                            'action' => 'Tạo đơn',
                                                                            'data' => $total_payment,
                                                                            'old_data' => $balance,
                                                                            'new_data' => $user->balance,
                                                                            'ip' => $request->ip(),
                                                                            'description' => "Tạo đơn hàng " . $service_->name . " với số lượng " . $request->quantity . " với giá " . number_format($total_payment) . "đ",
                                                                            'data_json' => '',
                                                                            'domain' => getDomain(),
                                                                        ]);
                                                                        return response()->json([
                                                                            'status' => 'success',
                                                                            'message' => 'Đặt hàng thành công',
                                                                            'order_id' => $order->id,
                                                                        ]);
                                                                    } else {
                                                                        return response()->json([
                                                                            'status' => 'error',
                                                                            'message' => 'Đặt hàng thất bại',
                                                                        ]);
                                                                    }
                                                                } else {
                                                                    return response()->json([
                                                                        'status' => 'error',
                                                                        'message' => $result['message'],
                                                                    ]);
                                                                }
                                                            }elseif ($server->actual_service == 'trumvip') {
                                                                $smm = new Trumvip();
                                                                $result = $smm->CreateOrder($order_link, $quantity, $server->service_list);
                                                                if ($result['status']) {
                                                                    $order_history = [
                                                                        'time' => Carbon::now()->format('H:i d/m/Y'),
                                                                        'status' => 'info',
                                                                        'title' => "Đơn hàng đã hoạt động",
                                                                    ];
                                                                    $order = Orders::create([
                                                                        'username' => $user->username,
                                                                        'service_id' => $service_->id,
                                                                        'service_name' => $service_->name,
                                                                        'server_service' => $request->server_service,
                                                                        'price' => $price,
                                                                        'quantity' => $request->quantity,
                                                                        'total_payment' => $total_payment,
                                                                        'order_code' => $result['data']['order'],
                                                                        'order_link' => $request->link_order,
                                                                        'start' => 0,
                                                                        'buff' => 0,
                                                                        'actual_service' => $server->actual_service,
                                                                        'actual_path' => $server->actual_path,
                                                                        'actual_server' => $server->actual_server,
                                                                        'status' => 'Active',
                                                                        'action' => json_encode([
                                                                            'link_order' => $request->link_order,
                                                                            'server_service' => $request->server_service,
                                                                            'quantity' => $request->quantity,
                                                                            'reaction' => $request->reaction ?? '',
                                                                            'speed' => $request->speed ?? '',
                                                                            'comment' => $request->comment ?? '',
                                                                            'minutes' => $request->minutes ?? '',
                                                                            'time' => $request->time ?? '',
                                                                        ]),
                                                                        'dataJson' => '',
                                                                        'isShow' => 1,
                                                                        'history' => json_encode([
                                                                            [
                                                                                'status' => 'primary',
                                                                                'title' => "Đơn hàng đã được tạo",
                                                                                'time' => Carbon::now()->format('H:i d/m/Y'),
                                                                            ]
                                                                        ]),
                                                                        'note' => $request->note ?? '',
                                                                        'domain' => getDomain(),
                                                                    ]);
    
                                                                    if ($order) {
                                                                        $order->order_code = $result['data']['order'] ?? '';
                                                                        $order->start = 0;
                                                                        $order->buff = 0;
                                                                        $order->status = 'Active';
                                                                        $order->dataJson = json_encode($result);
                                                                        $order->history = json_encode($order_history);
                                                                        $order->save();
    
    
                                                                        $balance = $user->balance;
                                                                        $user->balance = $user->balance - $total_payment;
                                                                        $user->total_deduct = $user->total_deduct + $total_payment;
                                                                        $user->save();
                                                                        DataHistory::create([
                                                                            'username' => $user->username,
                                                                            'action' => 'Tạo đơn',
                                                                            'data' => $total_payment,
                                                                            'old_data' => $balance,
                                                                            'new_data' => $user->balance,
                                                                            'ip' => $request->ip(),
                                                                            'description' => "Tạo đơn hàng " . $service_->name . " với số lượng " . $request->quantity . " với giá " . number_format($total_payment) . "đ",
                                                                            'data_json' => '',
                                                                            'domain' => getDomain(),
                                                                        ]);
                                                                        return response()->json([
                                                                            'status' => 'success',
                                                                            'message' => 'Đặt hàng thành công',
                                                                            'order_id' => $order->id,
                                                                        ]);
                                                                    } else {
                                                                        return response()->json([
                                                                            'status' => 'error',
                                                                            'message' => 'Đặt hàng thất bại',
                                                                        ]);
                                                                    }
                                                                } else {
                                                                    return response()->json([
                                                                        'status' => 'error',
                                                                        'message' => $result['message'],
                                                                    ]);
                                                                }
                                                            }elseif ($server->actual_service == 'dnoxsmm') {
                                                                $smm = new Dnoxsmm();
                                                                $result = $smm->CreateOrder($order_link, $quantity, $server->service_list);
                                                                if ($result['status']) {
                                                                    $order_history = [
                                                                        'time' => Carbon::now()->format('H:i d/m/Y'),
                                                                        'status' => 'info',
                                                                        'title' => "Đơn hàng đã hoạt động",
                                                                    ];
                                                                    $order = Orders::create([
                                                                        'username' => $user->username,
                                                                        'service_id' => $service_->id,
                                                                        'service_name' => $service_->name,
                                                                        'server_service' => $request->server_service,
                                                                        'price' => $price,
                                                                        'quantity' => $request->quantity,
                                                                        'total_payment' => $total_payment,
                                                                        'order_code' => $result['data']['order'],
                                                                        'order_link' => $request->link_order,
                                                                        'start' => 0,
                                                                        'buff' => 0,
                                                                        'actual_service' => $server->actual_service,
                                                                        'actual_path' => $server->actual_path,
                                                                        'actual_server' => $server->actual_server,
                                                                        'status' => 'Active',
                                                                        'action' => json_encode([
                                                                            'link_order' => $request->link_order,
                                                                            'server_service' => $request->server_service,
                                                                            'quantity' => $request->quantity,
                                                                            'reaction' => $request->reaction ?? '',
                                                                            'speed' => $request->speed ?? '',
                                                                            'comment' => $request->comment ?? '',
                                                                            'minutes' => $request->minutes ?? '',
                                                                            'time' => $request->time ?? '',
                                                                        ]),
                                                                        'dataJson' => '',
                                                                        'isShow' => 1,
                                                                        'history' => json_encode([
                                                                            [
                                                                                'status' => 'primary',
                                                                                'title' => "Đơn hàng đã được tạo",
                                                                                'time' => Carbon::now()->format('H:i d/m/Y'),
                                                                            ]
                                                                        ]),
                                                                        'note' => $request->note ?? '',
                                                                        'domain' => getDomain(),
                                                                    ]);
    
                                                                    if ($order) {
                                                                        $order->order_code = $result['data']['order'] ?? '';
                                                                        $order->start = 0;
                                                                        $order->buff = 0;
                                                                        $order->status = 'Active';
                                                                        $order->dataJson = json_encode($result);
                                                                        $order->history = json_encode($order_history);
                                                                        $order->save();
    
    
                                                                        $balance = $user->balance;
                                                                        $user->balance = $user->balance - $total_payment;
                                                                        $user->total_deduct = $user->total_deduct + $total_payment;
                                                                        $user->save();
                                                                        DataHistory::create([
                                                                            'username' => $user->username,
                                                                            'action' => 'Tạo đơn',
                                                                            'data' => $total_payment,
                                                                            'old_data' => $balance,
                                                                            'new_data' => $user->balance,
                                                                            'ip' => $request->ip(),
                                                                            'description' => "Tạo đơn hàng " . $service_->name . " với số lượng " . $request->quantity . " với giá " . number_format($total_payment) . "đ",
                                                                            'data_json' => '',
                                                                            'domain' => getDomain(),
                                                                        ]);
                                                                        return response()->json([
                                                                            'status' => 'success',
                                                                            'message' => 'Đặt hàng thành công',
                                                                            'order_id' => $order->id,
                                                                        ]);
                                                                    } else {
                                                                        return response()->json([
                                                                            'status' => 'error',
                                                                            'message' => 'Đặt hàng thất bại',
                                                                        ]);
                                                                    }
                                                                } else {
                                                                    return response()->json([
                                                                        'status' => 'error',
                                                                        'message' => $result['message'],
                                                                    ]);
                                                                }
                                                            }elseif ($server->actual_service == 'Smm(Quantity-8%)') {
                                                                $smm = new CostEightPercent();
                                                                $result = $smm->CreateOrder($order_link, $quantity, $server->service_list);
                                                                if ($result['status']) {
                                                                    $order_history = [
                                                                        'time' => Carbon::now()->format('H:i d/m/Y'),
                                                                        'status' => 'info',
                                                                        'title' => "Đơn hàng đã hoạt động",
                                                                    ];
                                                                    $order = Orders::create([
                                                                        'username' => $user->username,
                                                                        'service_id' => $service_->id,
                                                                        'service_name' => $service_->name,
                                                                        'server_service' => $request->server_service,
                                                                        'price' => $price,
                                                                        'quantity' => $request->quantity,
                                                                        'total_payment' => $total_payment,
                                                                        'order_code' => $result['data']['order'],
                                                                        'order_link' => $request->link_order,
                                                                        'start' => 0,
                                                                        'buff' => 0,
                                                                        'actual_service' => $server->actual_service,
                                                                        'actual_path' => $server->actual_path,
                                                                        'actual_server' => $server->actual_server,
                                                                        'status' => 'Active',
                                                                        'action' => json_encode([
                                                                            'link_order' => $request->link_order,
                                                                            'server_service' => $request->server_service,
                                                                            'quantity' => $request->quantity,
                                                                            'reaction' => $request->reaction ?? '',
                                                                            'speed' => $request->speed ?? '',
                                                                            'comment' => $request->comment ?? '',
                                                                            'minutes' => $request->minutes ?? '',
                                                                            'time' => $request->time ?? '',
                                                                        ]),
                                                                        'dataJson' => '',
                                                                        'isShow' => 1,
                                                                        'history' => json_encode([
                                                                            [
                                                                                'status' => 'primary',
                                                                                'title' => "Đơn hàng đã được tạo",
                                                                                'time' => Carbon::now()->format('H:i d/m/Y'),
                                                                            ]
                                                                        ]),
                                                                        'note' => $request->note ?? '',
                                                                        'domain' => getDomain(),
                                                                    ]);
    
                                                                    if ($order) {
                                                                        $order->order_code = $result['data']['order'] ?? '';
                                                                        $order->start = 0;
                                                                        $order->buff = 0;
                                                                        $order->status = 'Active';
                                                                        $order->dataJson = json_encode($result);
                                                                        $order->history = json_encode($order_history);
                                                                        $order->save();
    
    
                                                                        $balance = $user->balance;
                                                                        $user->balance = $user->balance - $total_payment;
                                                                        $user->total_deduct = $user->total_deduct + $total_payment;
                                                                        $user->save();
                                                                        DataHistory::create([
                                                                            'username' => $user->username,
                                                                            'action' => 'Tạo đơn',
                                                                            'data' => $total_payment,
                                                                            'old_data' => $balance,
                                                                            'new_data' => $user->balance,
                                                                            'ip' => $request->ip(),
                                                                            'description' => "Tạo đơn hàng " . $service_->name . " với số lượng " . $request->quantity . " với giá " . number_format($total_payment) . "đ",
                                                                            'data_json' => '',
                                                                            'domain' => getDomain(),
                                                                        ]);
                                                                        return response()->json([
                                                                            'status' => 'success',
                                                                            'message' => 'Đặt hàng thành công',
                                                                            'order_id' => $order->id,
                                                                        ]);
                                                                    } else {
                                                                        return response()->json([
                                                                            'status' => 'error',
                                                                            'message' => 'Đặt hàng thất bại',
                                                                        ]);
                                                                    }
                                                                } else {
                                                                    return response()->json([
                                                                        'status' => 'error',
                                                                        'message' => $result['message'],
                                                                    ]);
                                                                }
                                                            }elseif ($server->actual_service == 'Smm(Quantity-10%)') {
                                                                $smm = new CostTenPercent();
                                                                $result = $smm->CreateOrder($order_link, $quantity, $server->service_list);
                                                                if ($result['status']) {
                                                                    $order_history = [
                                                                        'time' => Carbon::now()->format('H:i d/m/Y'),
                                                                        'status' => 'info',
                                                                        'title' => "Đơn hàng đã hoạt động",
                                                                    ];
                                                                    $order = Orders::create([
                                                                        'username' => $user->username,
                                                                        'service_id' => $service_->id,
                                                                        'service_name' => $service_->name,
                                                                        'server_service' => $request->server_service,
                                                                        'price' => $price,
                                                                        'quantity' => $request->quantity,
                                                                        'total_payment' => $total_payment,
                                                                        'order_code' => $result['data']['order'],
                                                                        'order_link' => $request->link_order,
                                                                        'start' => 0,
                                                                        'buff' => 0,
                                                                        'actual_service' => $server->actual_service,
                                                                        'actual_path' => $server->actual_path,
                                                                        'actual_server' => $server->actual_server,
                                                                        'status' => 'Active',
                                                                        'action' => json_encode([
                                                                            'link_order' => $request->link_order,
                                                                            'server_service' => $request->server_service,
                                                                            'quantity' => $request->quantity,
                                                                            'reaction' => $request->reaction ?? '',
                                                                            'speed' => $request->speed ?? '',
                                                                            'comment' => $request->comment ?? '',
                                                                            'minutes' => $request->minutes ?? '',
                                                                            'time' => $request->time ?? '',
                                                                        ]),
                                                                        'dataJson' => '',
                                                                        'isShow' => 1,
                                                                        'history' => json_encode([
                                                                            [
                                                                                'status' => 'primary',
                                                                                'title' => "Đơn hàng đã được tạo",
                                                                                'time' => Carbon::now()->format('H:i d/m/Y'),
                                                                            ]
                                                                        ]),
                                                                        'note' => $request->note ?? '',
                                                                        'domain' => getDomain(),
                                                                    ]);
    
                                                                    if ($order) {
                                                                        $order->order_code = $result['data']['order'] ?? '';
                                                                        $order->start = 0;
                                                                        $order->buff = 0;
                                                                        $order->status = 'Active';
                                                                        $order->dataJson = json_encode($result);
                                                                        $order->history = json_encode($order_history);
                                                                        $order->save();
    
    
                                                                        $balance = $user->balance;
                                                                        $user->balance = $user->balance - $total_payment;
                                                                        $user->total_deduct = $user->total_deduct + $total_payment;
                                                                        $user->save();
                                                                        DataHistory::create([
                                                                            'username' => $user->username,
                                                                            'action' => 'Tạo đơn',
                                                                            'data' => $total_payment,
                                                                            'old_data' => $balance,
                                                                            'new_data' => $user->balance,
                                                                            'ip' => $request->ip(),
                                                                            'description' => "Tạo đơn hàng " . $service_->name . " với số lượng " . $request->quantity . " với giá " . number_format($total_payment) . "đ",
                                                                            'data_json' => '',
                                                                            'domain' => getDomain(),
                                                                        ]);
                                                                        return response()->json([
                                                                            'status' => 'success',
                                                                            'message' => 'Đặt hàng thành công',
                                                                            'order_id' => $order->id,
                                                                        ]);
                                                                    } else {
                                                                        return response()->json([
                                                                            'status' => 'error',
                                                                            'message' => 'Đặt hàng thất bại',
                                                                        ]);
                                                                    }
                                                                } else {
                                                                    return response()->json([
                                                                        'status' => 'error',
                                                                        'message' => $result['message'],
                                                                    ]);
                                                                }
                                                            }elseif ($server->actual_service == 'Smm(Quantity-2%)') {
                                                                $smm = new CostTwoPerCent();
                                                                $result = $smm->CreateOrder($order_link, $quantity, $server->service_list);
                                                                if ($result['status']) {
                                                                    $order_history = [
                                                                        'time' => Carbon::now()->format('H:i d/m/Y'),
                                                                        'status' => 'info',
                                                                        'title' => "Đơn hàng đã hoạt động",
                                                                    ];
                                                                    $order = Orders::create([
                                                                        'username' => $user->username,
                                                                        'service_id' => $service_->id,
                                                                        'service_name' => $service_->name,
                                                                        'server_service' => $request->server_service,
                                                                        'price' => $price,
                                                                        'quantity' => $request->quantity,
                                                                        'total_payment' => $total_payment,
                                                                        'order_code' => $result['data']['order'],
                                                                        'order_link' => $request->link_order,
                                                                        'start' => 0,
                                                                        'buff' => 0,
                                                                        'actual_service' => $server->actual_service,
                                                                        'actual_path' => $server->actual_path,
                                                                        'actual_server' => $server->actual_server,
                                                                        'status' => 'Active',
                                                                        'action' => json_encode([
                                                                            'link_order' => $request->link_order,
                                                                            'server_service' => $request->server_service,
                                                                            'quantity' => $request->quantity,
                                                                            'reaction' => $request->reaction ?? '',
                                                                            'speed' => $request->speed ?? '',
                                                                            'comment' => $request->comment ?? '',
                                                                            'minutes' => $request->minutes ?? '',
                                                                            'time' => $request->time ?? '',
                                                                        ]),
                                                                        'dataJson' => '',
                                                                        'isShow' => 1,
                                                                        'history' => json_encode([
                                                                            [
                                                                                'status' => 'primary',
                                                                                'title' => "Đơn hàng đã được tạo",
                                                                                'time' => Carbon::now()->format('H:i d/m/Y'),
                                                                            ]
                                                                        ]),
                                                                        'note' => $request->note ?? '',
                                                                        'domain' => getDomain(),
                                                                    ]);
    
                                                                    if ($order) {
                                                                        $order->order_code = $result['data']['order'] ?? '';
                                                                        $order->start = 0;
                                                                        $order->buff = 0;
                                                                        $order->status = 'Active';
                                                                        $order->dataJson = json_encode($result);
                                                                        $order->history = json_encode($order_history);
                                                                        $order->save();
    
    
                                                                        $balance = $user->balance;
                                                                        $user->balance = $user->balance - $total_payment;
                                                                        $user->total_deduct = $user->total_deduct + $total_payment;
                                                                        $user->save();
                                                                        DataHistory::create([
                                                                            'username' => $user->username,
                                                                            'action' => 'Tạo đơn',
                                                                            'data' => $total_payment,
                                                                            'old_data' => $balance,
                                                                            'new_data' => $user->balance,
                                                                            'ip' => $request->ip(),
                                                                            'description' => "Tạo đơn hàng " . $service_->name . " với số lượng " . $request->quantity . " với giá " . number_format($total_payment) . "đ",
                                                                            'data_json' => '',
                                                                            'domain' => getDomain(),
                                                                        ]);
                                                                        return response()->json([
                                                                            'status' => 'success',
                                                                            'message' => 'Đặt hàng thành công',
                                                                            'order_id' => $order->id,
                                                                        ]);
                                                                    } else {
                                                                        return response()->json([
                                                                            'status' => 'error',
                                                                            'message' => 'Đặt hàng thất bại',
                                                                        ]);
                                                                    }
                                                                } else {
                                                                    return response()->json([
                                                                        'status' => 'error',
                                                                        'message' => $result['message'],
                                                                    ]);
                                                                }
                                                            }elseif ($server->actual_service == 'Smm(Quantity/2.2)') {
                                                                $smm = new CostDevideTwoPointTwo();
                                                                $result = $smm->CreateOrder($order_link, $quantity, $server->service_list);
                                                                if ($result['status']) {
                                                                    $order_history = [
                                                                        'time' => Carbon::now()->format('H:i d/m/Y'),
                                                                        'status' => 'info',
                                                                        'title' => "Đơn hàng đã hoạt động",
                                                                    ];
                                                                    $order = Orders::create([
                                                                        'username' => $user->username,
                                                                        'service_id' => $service_->id,
                                                                        'service_name' => $service_->name,
                                                                        'server_service' => $request->server_service,
                                                                        'price' => $price,
                                                                        'quantity' => $request->quantity,
                                                                        'total_payment' => $total_payment,
                                                                        'order_code' => $result['data']['order'],
                                                                        'order_link' => $request->link_order,
                                                                        'start' => 0,
                                                                        'buff' => 0,
                                                                        'actual_service' => $server->actual_service,
                                                                        'actual_path' => $server->actual_path,
                                                                        'actual_server' => $server->actual_server,
                                                                        'status' => 'Active',
                                                                        'action' => json_encode([
                                                                            'link_order' => $request->link_order,
                                                                            'server_service' => $request->server_service,
                                                                            'quantity' => $request->quantity,
                                                                            'reaction' => $request->reaction ?? '',
                                                                            'speed' => $request->speed ?? '',
                                                                            'comment' => $request->comment ?? '',
                                                                            'minutes' => $request->minutes ?? '',
                                                                            'time' => $request->time ?? '',
                                                                        ]),
                                                                        'dataJson' => '',
                                                                        'isShow' => 1,
                                                                        'history' => json_encode([
                                                                            [
                                                                                'status' => 'primary',
                                                                                'title' => "Đơn hàng đã được tạo",
                                                                                'time' => Carbon::now()->format('H:i d/m/Y'),
                                                                            ]
                                                                        ]),
                                                                        'note' => $request->note ?? '',
                                                                        'domain' => getDomain(),
                                                                    ]);
    
                                                                    if ($order) {
                                                                        $order->order_code = $result['data']['order'] ?? '';
                                                                        $order->start = 0;
                                                                        $order->buff = 0;
                                                                        $order->status = 'Active';
                                                                        $order->dataJson = json_encode($result);
                                                                        $order->history = json_encode($order_history);
                                                                        $order->save();
    
    
                                                                        $balance = $user->balance;
                                                                        $user->balance = $user->balance - $total_payment;
                                                                        $user->total_deduct = $user->total_deduct + $total_payment;
                                                                        $user->save();
                                                                        DataHistory::create([
                                                                            'username' => $user->username,
                                                                            'action' => 'Tạo đơn',
                                                                            'data' => $total_payment,
                                                                            'old_data' => $balance,
                                                                            'new_data' => $user->balance,
                                                                            'ip' => $request->ip(),
                                                                            'description' => "Tạo đơn hàng " . $service_->name . " với số lượng " . $request->quantity . " với giá " . number_format($total_payment) . "đ",
                                                                            'data_json' => '',
                                                                            'domain' => getDomain(),
                                                                        ]);
                                                                        return response()->json([
                                                                            'status' => 'success',
                                                                            'message' => 'Đặt hàng thành công',
                                                                            'order_id' => $order->id,
                                                                        ]);
                                                                    } else {
                                                                        return response()->json([
                                                                            'status' => 'error',
                                                                            'message' => 'Đặt hàng thất bại',
                                                                        ]);
                                                                    }
                                                                } else {
                                                                    return response()->json([
                                                                        'status' => 'error',
                                                                        'message' => $result['message'],
                                                                    ]);
                                                                }
                                                            }elseif ($server->actual_service == 'Smm(Quantity/1.5)') {
                                                                $smm = new CostDevideOnePointFive();
                                                                $result = $smm->CreateOrder($order_link, $quantity, $server->service_list);
                                                                if ($result['status']) {
                                                                    $order_history = [
                                                                        'time' => Carbon::now()->format('H:i d/m/Y'),
                                                                        'status' => 'info',
                                                                        'title' => "Đơn hàng đã hoạt động",
                                                                    ];
                                                                    $order = Orders::create([
                                                                        'username' => $user->username,
                                                                        'service_id' => $service_->id,
                                                                        'service_name' => $service_->name,
                                                                        'server_service' => $request->server_service,
                                                                        'price' => $price,
                                                                        'quantity' => $request->quantity,
                                                                        'total_payment' => $total_payment,
                                                                        'order_code' => $result['data']['order'],
                                                                        'order_link' => $request->link_order,
                                                                        'start' => 0,
                                                                        'buff' => 0,
                                                                        'actual_service' => $server->actual_service,
                                                                        'actual_path' => $server->actual_path,
                                                                        'actual_server' => $server->actual_server,
                                                                        'status' => 'Active',
                                                                        'action' => json_encode([
                                                                            'link_order' => $request->link_order,
                                                                            'server_service' => $request->server_service,
                                                                            'quantity' => $request->quantity,
                                                                            'reaction' => $request->reaction ?? '',
                                                                            'speed' => $request->speed ?? '',
                                                                            'comment' => $request->comment ?? '',
                                                                            'minutes' => $request->minutes ?? '',
                                                                            'time' => $request->time ?? '',
                                                                        ]),
                                                                        'dataJson' => '',
                                                                        'isShow' => 1,
                                                                        'history' => json_encode([
                                                                            [
                                                                                'status' => 'primary',
                                                                                'title' => "Đơn hàng đã được tạo",
                                                                                'time' => Carbon::now()->format('H:i d/m/Y'),
                                                                            ]
                                                                        ]),
                                                                        'note' => $request->note ?? '',
                                                                        'domain' => getDomain(),
                                                                    ]);
    
                                                                    if ($order) {
                                                                        $order->order_code = $result['data']['order'] ?? '';
                                                                        $order->start = 0;
                                                                        $order->buff = 0;
                                                                        $order->status = 'Active';
                                                                        $order->dataJson = json_encode($result);
                                                                        $order->history = json_encode($order_history);
                                                                        $order->save();
    
    
                                                                        $balance = $user->balance;
                                                                        $user->balance = $user->balance - $total_payment;
                                                                        $user->total_deduct = $user->total_deduct + $total_payment;
                                                                        $user->save();
                                                                        DataHistory::create([
                                                                            'username' => $user->username,
                                                                            'action' => 'Tạo đơn',
                                                                            'data' => $total_payment,
                                                                            'old_data' => $balance,
                                                                            'new_data' => $user->balance,
                                                                            'ip' => $request->ip(),
                                                                            'description' => "Tạo đơn hàng " . $service_->name . " với số lượng " . $request->quantity . " với giá " . number_format($total_payment) . "đ",
                                                                            'data_json' => '',
                                                                            'domain' => getDomain(),
                                                                        ]);
                                                                        return response()->json([
                                                                            'status' => 'success',
                                                                            'message' => 'Đặt hàng thành công',
                                                                            'order_id' => $order->id,
                                                                        ]);
                                                                    } else {
                                                                        return response()->json([
                                                                            'status' => 'error',
                                                                            'message' => 'Đặt hàng thất bại',
                                                                        ]);
                                                                    }
                                                                } else {
                                                                    return response()->json([
                                                                        'status' => 'error',
                                                                        'message' => $result['message'],
                                                                    ]);
                                                                }
                                                            } elseif ($server->actual_service == 'justanotherpanel') {
                                                                $smm = new JustanotherpanelController();
                                                                
                                                                $result = $smm->CreateOrder($order_link, $quantity,  $server->service_list);
                                                               
                                                                if ($result["status"]) {
                                                                    $order_history = [
                                                                        'time' => Carbon::now()->format('H:i d/m/Y'),
                                                                        'status' => 'info',
                                                                        'title' => "Đơn hàng đã hoạt động",
                                                                    ];
                                                                    $order = Orders::create([
                                                                        'username' => $user->username,
                                                                        'service_id' => $service_->id,
                                                                        'service_name' => $service_->name,
                                                                        'server_service' => $request->server_service,
                                                                        'price' => $price,
                                                                        'quantity' => $request->quantity,
                                                                        'total_payment' => $total_payment,
                                                                        'order_code' => $result['data']['order'],
                                                                        'order_link' => $request->link_order,
                                                                        'start' => 0,
                                                                        'buff' => 0,
                                                                        'actual_service' => $server->actual_service,
                                                                        'actual_path' => $server->actual_path,
                                                                        'actual_server' => $server->actual_server,
                                                                        'status' => 'Active',
                                                                        'action' => json_encode([
                                                                            'link_order' => $request->link_order,
                                                                            'server_service' => $request->server_service,
                                                                            'quantity' => $request->quantity,
                                                                            'reaction' => $request->reaction ?? '',
                                                                            'speed' => $request->speed ?? '',
                                                                            'comment' => $request->comment ?? '',
                                                                            'minutes' => $request->minutes ?? '',
                                                                            'time' => $request->time ?? '',
                                                                        ]),
                                                                        'dataJson' => '',
                                                                        'isShow' => 1,
                                                                        'history' => json_encode([
                                                                            [
                                                                                'status' => 'primary',
                                                                                'title' => "Đơn hàng đã được tạo",
                                                                                'time' => Carbon::now()->format('H:i d/m/Y'),
                                                                            ]
                                                                        ]),
                                                                        'note' => $request->note ?? '',
                                                                        'domain' => getDomain(),
                                                                    ]);
    
                                                                    if ($order) {
                                                                        $order->order_code = $result['data']['order'] ?? '';
                                                                        $order->start = 0;
                                                                        $order->buff = 0;
                                                                        $order->status = 'Active';
                                                                        $order->dataJson = json_encode($result);
                                                                        $order->history = json_encode($order_history);
                                                                        $order->save();
    
    
                                                                        $balance = $user->balance;
                                                                        $user->balance = $user->balance - $total_payment;
                                                                        $user->total_deduct = $user->total_deduct + $total_payment;
                                                                        $user->save();
                                                                        DataHistory::create([
                                                                            'username' => $user->username,
                                                                            'action' => 'Tạo đơn',
                                                                            'data' => $total_payment,
                                                                            'old_data' => $balance,
                                                                            'new_data' => $user->balance,
                                                                            'ip' => $request->ip(),
                                                                            'description' => "Tạo đơn hàng " . $service_->name . " với số lượng " . $request->quantity . " với giá " . number_format($total_payment) . "đ",
                                                                            'data_json' => '',
                                                                            'domain' => getDomain(),
                                                                        ]);
                                                                        return response()->json([
                                                                            'status' => 'success',
                                                                            'message' => 'Đặt hàng thành công',
                                                                            'order_id' => $order->id,
                                                                        ]);
                                                                    } else {
                                                                        return response()->json([
                                                                            'status' => 'error',
                                                                            'message' => 'Đặt hàng thất bại',
                                                                        ]);
                                                                    }
                                                                } else {
                                                                    return response()->json([
                                                                        'status' => 'error',
                                                                        'message' => $result['message'],
                                                                    ]);
                                                                }
                                                            }
                                                            
                                                            else {
                                                                return response()->json([
                                                                    'status' => 'error',
                                                                    'message' => 'Dữ liệu không hợp lệ vui lòng thử lại sau'
                                                                ]);
                                                            }
                                                        } else {
                                                            $order = Orders::create([
                                                                'username' => $user->username,
                                                                'service_id' => $service_->id,
                                                                'service_name' => $service_->name,
                                                                'server_service' => $request->server_service,
                                                                'price' => $price,
                                                                'quantity' => $request->quantity,
                                                                'total_payment' => $total_payment,
                                                                'order_code' => '',
                                                                'order_link' => $request->link_order,
                                                                'start' => 0,
                                                                'buff' => 0,
                                                                'actual_service' => $server->actual_service,
                                                                'actual_path' => $server->actual_path,
                                                                'actual_server' => $server->actual_server,
                                                                'status' => 'Active',
                                                                'action' => json_encode([
                                                                    'link_order' => $request->link_order,
                                                                    'server_service' => $request->server_service,
                                                                    'quantity' => $request->quantity,
                                                                    'reaction' => $request->reaction ?? '',
                                                                    'speed' => $request->speed ?? '',
                                                                    'comment' => $request->comment ?? '',
                                                                    'minutes' => $request->minutes ?? '',
                                                                    'time' => $request->time ?? '',
                                                                ]),
                                                                'dataJson' => '',
                                                                'isShow' => 1,
                                                                'history' => json_encode([
                                                                    [
                                                                        'status' => 'primary',
                                                                        'title' => "Đơn hàng đã được tạo",
                                                                        'time' => Carbon::now()->format('H:i d/m/Y'),
                                                                    ]
                                                                ]),
                                                                'note' => $request->note ?? '',
                                                                'domain' => getDomain(),
                                                            ]);

                                                            if ($order) {
                                                                return response()->json([
                                                                    'status' => 'success',
                                                                    'message' => 'Đặt hàng thành công',
                                                                    'order_id' => $order->id,
                                                                ]);
                                                            }
                                                        }
                                                
                                                    }
                                                }
                                            }
                                        }
                                    } else {
                                        return response()->json([
                                            'status' => 'error',
                                            'message' => 'Dịch vụ không tồn tại!'
                                        ]);
                                    }
                                }
                            } else {
                                return response()->json([
                                    'status' => 'error',
                                    'message' => 'Máy chủ không tồn tại!'
                                ]);
                            }
                        } else {
                            return response()->json([
                                'status' => 'error',
                                'message' => 'Thằng admin này có vấn đề'
                            ]);
                        }
                    }
                } else {
                    return response()->json([
                        'status' => 'error',
                        'message' => 'Người dùng không tồn tại'
                    ]);
                }
            }
        }
    }
}
