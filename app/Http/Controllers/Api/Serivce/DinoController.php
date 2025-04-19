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

class DinoController extends Controller
{

    public function __construct()
    {
    }
    public function getServiceRate($service_list)
    {
        $key = '838300bad20da5400dc9323224034343';
        $action = 'services';
    
        $response = Http::get('https://dnoxsmm.com/api/v2', [
            'key' => $key,
            'action' => $action,
        ]);
    
        Log::info('Response from API:', ['response' => $response->json()]);
    
        if ($response->successful()) {
            $data = $response->json();
    
            foreach ($data as $item) {
                if (isset($item['service']) && $item['service'] == $service_list) {
                    $rate = $item['rate'] * 26;
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
    public function getAllServiceByCategory(Request $request)
    {
        if($request->source == "dino"){
            $key = '838300bad20da5400dc9323224034343';
            $action = 'services';
    
            $response = Http::get('	https://dnoxsmm.com/api/v2', [
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
        $key = '838300bad20da5400dc9323224034343';

        $server = ServerService::where('domain', getDomain())
                               ->where(function($query) use ($request) {
                                   $query->where('id', $request->server_service)
                                         ->orWhere('actual_service', 'dino');
                               })
                               ->first();
        if (!$server) {
            return [
                'status' => false,
                'message' => 'Không tìm thấy dịch vụ với server_service ID.'
            ];
        }
    
        $price = $server->price;

        $serviceRate = $this->getServiceRate($service);
        if (!$serviceRate['status']) {
            return [
                'status' => false,
                'message' => $serviceRate['message']
            ];
        }
    
        $api_rate = $serviceRate['rate'];
        
        Log::info("API Rate: $api_rate - Database Price: $price - Total Payment: $total_payment");
    
        if ($price < $api_rate) {
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
    
        $response = Http::post('https://dnoxsmm.com/api/v2', [
            'key' => $key,
            'action' => 'add',
            'link' => $link,
            'quantity' => $quantity,
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