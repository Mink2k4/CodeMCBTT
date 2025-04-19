<?php

namespace App\Http\Controllers\Api\Document;
use App\Http\Controllers\Api\Serivce\CostNormal;
use App\Http\Controllers\Api\Serivce\CostDevideTwoPointTwo;
use App\Http\Controllers\Api\Serivce\CostDevideOnePointFive;
use App\Http\Controllers\Controller;
use App\Http\Resources\GetOrderResource;
use App\Http\Resources\ServicePriceResource;
use App\Http\Resources\UserResource;
use App\Models\Orders;
use App\Models\ServerService;
use App\Models\Service;
use App\Models\Ticket;
use Illuminate\Foundation\Auth\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

class ApiDocumentController extends Controller
{
    public $user;

    // public function __construct(Request $request)
    // {
    //     $this->user = $request->user;
    // }

    public function me(Request $request)
    {
        return new UserResource($request->user);
        // var_dump($request->user);
    }

    public function servicePrices(Request $request)
    {
        $server_service = ServerService::where('domain', env('PARENT_SITE'))->get();
        $arr = [];
        foreach ($server_service as $sv) {
            $arr[] = [
                'id' => $sv->id,
                'name' => Service::find($sv->service_id)->name,
                'server' => $sv->server,
                'price' => priceServer($sv->id, $request->user->level),
                'min' => $sv->min,
                'max' => $sv->max,
                'title' => $sv->title,
                'description' => $sv->description,
                'status' => $sv->status,
            ];
        }
        return response()->json([
            'status' => 'success',
            'message' => "Lấy dữ liệu thành công!",
            'data' => $arr
        ]);
    }

    public function getOrders(Request $request)
    {
        $valid = Validator::make($request->all(), [
            'order_id' => 'required|numeric',
        ]);
        if ($valid->fails()) {
            return response()->json([
                'status' => 'error',
                'message' => $valid->errors()->first(),
            ]);
        } else {
            $order = Orders::where('username', $request->user->username)->where('id', $request->order_id)->first();
            if ($order) {
                return new GetOrderResource($order);
            } else {
                return response()->json([
                    'status' => 'error',
                    'message' => "Không tìm thấy đơn hàng!",
                ]);
            }
        }
    }

    public function orderRefund(Request $request)
    {
        $valid = Validator::make($request->all(), [
            'order_id' => 'required|numeric',
        ]);
    
        if ($valid->fails()) {
            return response()->json([
                'status' => 'error',
                'message' => $valid->errors()->first(),
            ]);
        }
    
        $order = Orders::where('username', $request->user->username)
            ->where('id', $request->order_id)
            ->first();
    
        if (!$order) {
            return response()->json([
                'status' => 'error',
                'message' => "Không tìm thấy đơn hàng!",
            ]);
        }
    
        // Tìm dịch vụ theo cả hai điều kiện
        $server_service = ServerService::where('name', $order->service_name)
            ->where('server', $order->server_service)
            ->first();
    
        if (!$server_service) {
            return response()->json([
                'status' => 'error',
                'message' => "Không tìm thấy dịch vụ!",
            ]);
        }
    
        // Kiểm tra trạng thái đơn hàng có hợp lệ để hoàn tiền không
        $invalidStatuses = ['Refunded', 'Success', 'Failed', 'Cancelled', 'Completed'];
        if (in_array($order->status, $invalidStatuses)) {
            return response()->json([
                'status' => 'error',
                'message' => "Đơn hàng đã được hoàn tiền",
            ]);
        }
    
        // Kiểm tra xem dịch vụ có hỗ trợ hoàn tiền không
        if ($server_service->order_type !== 'refund') {
            return response()->json([
                'status' => 'error',
                'message' => "Máy chủ không hỗ trợ hoàn tiền!",
            ]);
        }
    
        // Kiểm tra nếu actual_service hợp lệ
        $serviceHandlers = [
            'Smm(Quantity-0%)'  => new CostNormal(),
            'Smm(Quantity/2.2)' => new CostDevideTwoPointTwo(),
            'Smm(Quantity/1.5)' => new CostDevideOnePointFive(),
        ];
    
        if (!array_key_exists($order->actual_service, $serviceHandlers)) {
            return response()->json([
                'status' => 'error',
                'message' => "Máy chủ không hỗ trợ hoàn tiền",
            ]);
        }
    
        // Gọi API hủy đơn hàng bằng class tương ứng
        $costHandler = $serviceHandlers[$order->actual_service];
        $data = $costHandler->cancel([$order->order_code]);
    
        // Kiểm tra phản hồi từ API
        $isCancelled = isset($data['data'][0]['cancel']) && !empty($data['data'][0]['cancel']);
        $hasError = isset($data['data'][0]['cancel']['error']);
    
        if (!$isCancelled && $hasError) {
            return response()->json([
                'status' => 'error',
                'message' => $data['data'][0]['cancel']['error'] ?? "Đơn hàng hoàn tiền thất bại!",
            ]);
        }
    
        // Cập nhật trạng thái đơn hàng thành "Refunded"
        $order->update(['status' => 'Refunded']);
    
        // Tạo ticket "Hoàn tiền"
        Ticket::create([
            'user_id'   => $request->user->id,
            'order_id'  => $order->id,
            'category'  => "Hỗ trợ Order",
            'type'      => "Hoàn tiền",
            'reason'    => "Hoàn tiền",
            'status'    => "Pending", // Chờ xử lý
            'title'     => "Hỗ trợ order - Hoàn tiền",
        ]);
    
        return response()->json([
            'status' => 'success',
            'message' => "Yêu cầu hoàn tiền đã được gửi!",
        ]);
    }


    public function orderWarranty(Request $request)
    {
        $valid = Validator::make($request->all(), [
            'order_id' => 'required|numeric',
        ]);
    
        if ($valid->fails()) {
            return response()->json([
                'status' => 'error',
                'message' => $valid->errors()->first(),
            ]);
        }
    
        $order = Orders::where('username', $request->user->username)
            ->where('id', $request->order_id)
            ->first();
    
        if (!$order) {
            return response()->json([
                'status' => 'error',
                'message' => "Không tìm thấy đơn hàng!",
            ]);
        }
    
        // Tìm dịch vụ
        $server_service = ServerService::where('name', $order->service_name)
            ->where('server', $order->server_service)
            ->first();
    
        if (!$server_service) {
            return response()->json([
                'status' => 'error',
                'message' => "Không tìm thấy dịch vụ!",
            ]);
        }
    
        // Kiểm tra trạng thái đơn hàng có hợp lệ để bảo hành không
        $invalidStatuses = ['Refunded', 'Success', 'Failed', 'Cancelled', 'Completed'];
        if (in_array($order->status, $invalidStatuses)) {
            return response()->json([
                'status' => 'error',
                'message' => "Đơn hàng không thể bảo hành!",
            ]);
        }
    
        // Kiểm tra xem dịch vụ có hỗ trợ bảo hành không
        if ($server_service->warranty !== 'yes') {
            return response()->json([
                'status' => 'error',
                'message' => "Máy chủ không hỗ trợ bảo hành!",
            ]);
        }
    
        // Chọn class xử lý bảo hành dựa theo actual_service
        $serviceHandlers = [
            'Smm(Quantity-0%)'  => new CostNormal(),
            'Smm(Quantity/2.2)' => new CostDevideTwoPointTwo(),
            'Smm(Quantity/1.5)' => new CostDevideOnePointFive(),
        ];
    
        if (!array_key_exists($order->actual_service, $serviceHandlers)) {
            return response()->json([
                'status' => 'error',
                'message' => "Dịch vụ này không đủ điều kiện bảo hành!",
            ]);
        }
    
        // Gọi API bảo hành bằng hàm `refill`
        $costHandler = $serviceHandlers[$order->actual_service];
        $data = $costHandler->refill($order->order_code);
    
        // Kiểm tra phản hồi từ API
        $isRefilled = isset($data['data'][0]['refill']) && !empty($data['data'][0]['refill']);
        $hasError = isset($data['data'][0]['refill']['error']);
    
        if (!$isRefilled && $hasError) {
            return response()->json([
                'status' => 'error',
                'message' => $data['data'][0]['refill']['error'] ?? "Đơn hàng bảo hành thất bại!",
            ]);
        }
    
        // Cập nhật trạng thái đơn hàng thành "Active"
        $order->update(['status' => 'Active']);
    
        // Tạo ticket "Bảo hành"
        Ticket::create([
            'user_id'   => $request->user->id,
            'order_id'  => $order->id,
            'category'  => "Hỗ trợ Order",
            'type'      => "Bảo hành",
            'reason'    => "Bảo hành",
            'status'    => "Pending", // Chờ xử lý
            'title'     => "Hỗ trợ order - Bảo hành",
        ]);
    
        return response()->json([
            'status' => 'success',
            'message' => "Yêu cầu bảo hành đã được gửi!",
        ]);
    }
}
