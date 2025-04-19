<?php

namespace App\Http\Controllers\CronJobs\Service;

use App\Http\Controllers\Controller;
use App\Models\Orders;
use Illuminate\Http\Request;
use App\Http\Controllers\Api\Serivce\SubgiareController;
use App\Http\Controllers\Api\Serivce\Hacklike17Controller;
use App\Models\User;
use Carbon\Carbon;

class CreateOrderController extends Controller
{
    public function SubgiareBuy(Request $request)
    {
        $orders = Orders::where('status', '=', 'PendingOrder')->where('actual_service', 'subgiare')->get();
        foreach ($orders as $order) {
            $actual_path = $order->actual_path;
            $actual_server = $order->actual_server;
            $action = json_decode($order->action, true);
            $quantity = $order->quantity;
            $order_link = $order->order_link;
            $total_payment = $order->total_payment;

            $subgiare = new SubgiareController();
            $subgiare->path = $actual_path;
            $subgiare->data = [
                'order_link' => $order_link,
                'quantity' => $quantity,
                'speed' => $action['speed'] ?? 'fast',
                'comment' => $action['comment'] ?? '',
                'minutes' => $action['minutes'] ?? '',
                'time' => $action['time'] ?? '',
                'reaction' => $action['reaction'] ?? '',
                'server_order' => $actual_server,
            ];

            $result = $subgiare->CreateOrder();
            if ($result['status'] == true) {
                $order_history = json_decode($order->history,true);
                $order_history[] = [
                    'time' => Carbon::now()->format('H:i d/m/Y'),
                    'status' => 'info',
                    'title' => "Đơn hàng đang hoạt động",
                ];
                $order->order_code = $result['data']['code_order'];
                $order->start = $result['data']['start'] ?? 0;
                $order->buff = $result['data']['buff'] ?? 0;
                $order->status = 'Active';
                $order->dataJson = json_encode($result['data']);
                $order->history = json_encode($order_history);
                $order->save();

            } else {
                $order_history = json_decode($order->history,true);
                // thêm array vào history
                $order_history[] = [
                    'time' => Carbon::now()->format('H:i d/m/Y'),
                    'status' => 'danger',
                    'title' => "Đơn hàng đã bị huỷ",
                ];
                // cập nhật lại history
                User::where('username', $order->username)->increment('balance', $total_payment);
                $order->status = 'Failed';
                $order->error = $result['message'];
                $order->dataJson = json_encode($result);
                $order->history = json_encode($order_history);
                // thêm history
                $order->save();
            }
        }
    }

    public function Hacklike17Buy(Request $request)
    {
        $orders = Orders::where('status', '=', 'PendingOrder')->where('actual_service', 'hacklike17')->get();
        foreach ($orders as $order) {
            $actual_path = $order->actual_path;
            $actual_server = $order->actual_server;
            $action = json_decode($order->action, true);
            $quantity = $order->quantity;
            $order_link = $order->order_link;
            $total_payment = $order->total_payment;

            $hacklike17 = new Hacklike17Controller();
            $hacklike17->path = $actual_path;
            $hacklike17->data = [
                'order_link' => $order_link,
                'quantity' => $quantity,
                'speed' => $action['speed'] ?? '0',
                'comment' => $action['comment'] ?? '',
                'minutes' => $action['minutes'] ?? '',
                'time' => $action['time'] ?? '',
                'reaction' => $action['reaction'] ?? '',
                'server_order' => $actual_server,
            ];
            $result = $hacklike17->CreateOrder();
            if ($result['status'] == true) {
                $order_history = json_decode($order->history,true);
                // thêm array vào history
                $order_history[] = [
                    'time' => Carbon::now()->format('H:i d/m/Y'),
                    'status' => 'info',
                    'title' => "Đơn hàng đã hoạt động",
                ];
                $order->order_code = $result['data']['code_order'];
                $order->start = $result['data']['start'] ?? 0;
                $order->buff = $result['data']['buff'] ?? 0;
                $order->status = 'Active';
                $order->dataJson = json_encode($result['data']);
                $order->history = json_encode($order_history);
                $order->save();
            } else {
                // hoàn tiền

                $order_history = json_decode($order->history,true);
                // thêm array vào history
                $order_history[] = [
                    'time' => Carbon::now()->format('H:i d/m/Y'),
                    'status' => 'danger',
                    'title' => "Đơn hàng đã bị huỷ",
                ];


                User::where('username', $order->username)->increment('balance', $total_payment);
                $order->status = 'Failed';
                $order->error = $result['message'];
                $order->dataJson = json_encode($result);
                $order->history = json_encode($order_history);
                $order->save();
            }
        }
    }
}
