<?php

namespace App\Http\Controllers\Api\Serivce;

use App\Http\Controllers\Custom\TelegramCustomController;
use App\Http\Controllers\Controller;
use App\Models\Service;
use App\Models\ServiceSocial;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\File;
use Illuminate\Support\Facades\Log;
use App\Models\ServerService; 

class CostTwoPerCent extends Controller
{

    public function __construct()
    {
    }
    public function getServiceRate($service_list, $quantity)
    {
        $key = 'af09a033cb10e544c67fd95ef711c8f5';
        $action = 'services';
    
        $response = Http::get('https://smmcost.com/api/v2', [
            'key' => $key,
            'action' => $action,
        ]);
    
        if ($response->successful()) {
            $data = $response->json();
    
            foreach ($data as $item) {
                if (isset($item['service']) && $item['service'] == $service_list) {
                    $rate = $item['rate'] * 26.5 * $quantity;
                    Log::info('Found service:', [
                        'service' => $item['service'],
                        'rate' => $rate,
                        'name' => $item['name']
                    ]);
                    return [
                        'status' => true,
                        'rate' => $rate, 
                        'service_name' => $item['name'],
                        'category' => $item['category'],
                    ];
                }
            }
            Log::warning('Service not found for service_list: ' . $service_list);
            return [
                'status' => false,
                'message' => 'Không tìm thấy dịch vụ với service_list này.'
            ];
        } else {
            $status = $response->status();
            $errorMessage = $response->body();
    
            Log::error("API request failed with status $status", ['error' => $errorMessage]);
    
            return [
                'status' => false,
                'message' => "Lỗi từ API: $status - $errorMessage"
            ];
        }
    }
    public function status($order_code)
    {
        $key = 'af09a033cb10e544c67fd95ef711c8f5';
        
        $response = Http::get('https://smmcost.com/api/v2', [
            'key' => $key,
            'action' => 'status',
            'order' => $order_code
        ]);
    
        Log::info("Checking status for order: $order_code", ['response' => $response->json()]);
    
        if ($response->successful()) {
            $data = $response->json();
    
            if (isset($data['start_count'], $data['remains'], $data['status'])) {
                return [
                    'status' => true,
                    'start_count' => $data['start_count'],
                    'remains' => $data['remains'],
                    'order_status' => $data['status']
                ];
            } else {
                return [
                    'status' => false,
                    'message' => 'Dữ liệu trả về từ API không đầy đủ.'
                ];
            }
        } else {
            $status = $response->status();
            $errorMessage = $response->body();
    
            Log::error("API request failed for order $order_code with status $status", ['error' => $errorMessage]);
    
            return [
                'status' => false,
                'message' => "Lỗi từ API: $status - $errorMessage"
            ];
        }
    }
    public function cancel(array $orderIds)
    {
        $key = 'af09a033cb10e544c67fd95ef711c8f5';
    
        $response = Http::post('https://smmcost.com/api/v2', [
            'key' => $key,
            'action' => 'cancel',
            'orders' => implode(',', $orderIds),
        ]);
    
        Log::info("Canceling orders", ['orderIds' => $orderIds, 'response' => $response->json()]);
    
        if ($response->successful()) {
            $data = $response->json();
    
            if (isset($data['success']) && $data['success']) {
                return [
                    'status' => true,
                    'message' => 'Hủy đơn hàng thành công',
                    'data' => $data
                ];
            } else {
                return [
                    'status' => false,
                    'message' => 'Hủy đơn hàng thất bại',
                    'data' => $data
                ];
            }
        } else {
            $status = $response->status();
            $errorMessage = $response->body();
    
            Log::error("API request failed while canceling orders", ['status' => $status, 'error' => $errorMessage]);
    
            return [
                'status' => false,
                'message' => "Lỗi từ API: $status - $errorMessage"
            ];
        }
    }
    public function refill(int $orderId)
    {
        $key = 'af09a033cb10e544c67fd95ef711c8f5';
    
        $response = Http::post('https://smmcost.com/api/v2', [
            'key' => $key,
            'action' => 'refill',
            'order' => $orderId,
        ]);
    
        Log::info("Refilling order: $orderId", ['response' => $response->json()]);
    
        if ($response->successful()) {
            $data = $response->json();
    
            if (isset($data['success']) && $data['success']) {
                return [
                    'status' => true,
                    'message' => 'Yêu cầu refill thành công',
                    'data' => $data
                ];
            } else {
                return [
                    'status' => false,
                    'message' => 'Yêu cầu refill thất bại',
                    'data' => $data
                ];
            }
        } else {
            $status = $response->status();
            $errorMessage = $response->body();
    
            Log::error("API request failed while refilling order $orderId", ['status' => $status, 'error' => $errorMessage]);
    
            return [
                'status' => false,
                'message' => "Lỗi từ API: $status - $errorMessage"
            ];
        }
    }
    public function getAllServiceByCategory(Request $request)
    {
        if($request->source == "Smm(Quantity-2%)"){
            $key = 'af09a033cb10e544c67fd95ef711c8f5';
            $action = 'services';
    
            $response = Http::get('	https://smmcost.com/api/v2', [
                'key' => $key,
                'action' => $action,
            ]);
	  
            $categories = [];
            $success = 0;
           
            $service = Service::where('id', $request->id)->where('domain', getDomain())->first();
            if ($response->successful()) {
                $data = $response->json();
                foreach ($data as $item) {
                    if ($service->category == $item['category']) {
                        $categories[] = $item;
                        $success++;
                    }
                }
            } else {
                $status = $response->status();
                $errorMessage = $response->body();
            }
    
            return response()->json([
                'categories' => $categories,
                'success' => $success,
            ]);
        }
    }
    public function CreateOrder($request, $link, $quantity, $service, $total_payment)
    {
        $key = 'af09a033cb10e544c67fd95ef711c8f5';

        $serviceRate = $this->getServiceRate($service, $quantity);
        if (!$serviceRate['status']) {
            return [
                'status' => false,
                'message' => $serviceRate['message']
            ];
        }
        
        $service_id = $service; // Thay đổi từ $serviceRate['service'] thành $service
        $server = ServerService::where('domain', getDomain())
                               ->where('service_list', $service_id)
                               ->where(function($query) use ($request) {
                                   $query->where('id', $request->server_service)
                                         ->orWhere('actual_service', 'Smm(Quantity-2%)');
                               })
                               ->first();
        
        if (!$server) {
            return [
                'status' => false,
                'message' => 'Không tìm thấy dịch vụ với server_service ID.'
            ];
        }    
        
        $api_rate = $serviceRate['rate'];
        Log::info("API Rate: $api_rate - Total Payment: $total_payment");
        // Ghi log khi giá dịch vụ thay đổi
        Log::info("⚠️ Giá dịch vụ đã thay đổi! 
        Dịch vụ: {$server->name} 
        Máy chủ: {$request->server_service}");
        if ($total_payment < $api_rate) {
            $telegramToken = '7949050405:AAFQYbxoMxf2bZBOz_sG6wHUzdB6D3aRck8';
            $telegramChatId = '5140790624';
        
            $message = "⚠️ Giá dịch vụ đã thay đổi!\n"
                     . "Dịch vụ: " . $server->name . "\n" // 
                     . "Máy chủ: " . $request->server_service . "\n" ;

            $response = Http::post("https://api.telegram.org/bot{$telegramToken}/sendMessage", [
                'chat_id' => $telegramChatId,
                'text' => $message
            ]);
        
            return [
                'status' => false,
                'message' => 'Dịch vụ đang quá tải. Vui lòng chờ vài phút'
            ];
        }
    
        $response = Http::post('https://smmcost.com/api/v2', [
            'key' => $key,
            'action' => 'add',
            'link' => $link,
            'quantity' => ((double)$quantity) - ((2/100) * (double)$quantity),
            'service' => $service
        ]);
    
        if ($response->successful()) {
            $data = $response->json();
    
            if (isset($data['order'])) {
                return [
                    'status' => true,
                    'message' => 'Thành công',
                    'data' => $data
                ];
            } else {
                return [
                    'status' => false,
                    'message' => 'Tạo đơn hàng thất bại',
                    'data' => $data
                ];
            }
        } else {
            $status = $response->status();
            $errorMessage = $response->body();
    
            return [
                'status' => false,
                'message' => "Lỗi từ API: $status - $errorMessage"
            ];
        }
    }
}