<?php

namespace App\Http\Controllers\Api\Serivce;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

class Hacklike17Controller extends Controller
{
    private $api_token;

    public $path;
    public $server;

    public $data = [
        'order_link' => '',
        'quantity' => '',
        'speed' => '',
        'comment' => '',
        'minutes' => '',
        'time' => '',
        'days' => '',
        'post' => '',
        'reaction' => '',
        'server_order' => '',
        'social' => '',
    ];

    public function __construct()
    {
        $this->api_token = env('TOKEN_HACKLIKE17');
    }

    public function refundOrder($id)
    {
        $url = 'https://hacklike17.com/api/facebook/refund';
        $data = [
            'token' => $this->api_token,
            'id' => $id,
        ];
        $dataPost = http_build_query($data);

        $result = $this->sendRequest($url, $dataPost);
        if (isset($result)) {
            if ($result['status'] == true) {
                return [
                    'status' => true,
                    'message' => 'Hoàn tiền thành công',
                ];
            } else {
                return [
                    'status' => false,
                    'message' => $result['msg'],
                ];
            }
        } else {
            return [
                'status' => false,
                'message' => 'Hoàn tiền thất bại vui lòng thử lại',
            ];
        }
    }

    public function createOrder()
    {
        $url = 'https://hacklike17.com/api/' . $this->path;
        $data = $this->data;

        $dataPost = [
            'token' => $this->api_token,
            'uid' => $data['order_link'] ?? '',
            'link' => $data['order_link'] ?? '',
            'count' => $data['quantity'] ?? '',
            'owner_link' => $data['order_link'] ?? '',
            'server' => $data['server_order'] ?? '',
            'reaction' => $data['reaction'] ?? '',
            'speed' => $data['speed'] == '1' ? '0' : '5',
            'speed_server_2' => $data['speed'] == '1' ? 'high' : 'low',
            'name' => 'Lương Bình Dương',
            'content' => "",
            'list_comment' => $data['comment'] ?? '',
            'messages' => $data['comment'] ?? '',
            'url' => $data['order_link'] ?? '',
            'max_post' => $data['post'] ?? '',
            'days' => $data['days'] ?? '',
            'minute' => $data['minutes'] ?? '',
            'vip_package' => $data['quantity'] ?? '',
            'comments' => $data['comment'] ?? '',
            'note' => "Tạo đơn hàng từ API",
        ];
        $dataPost = http_build_query($dataPost);
        $result = $this->sendRequest($url, $dataPost);
        if (isset($result)) {
            if ($result['status'] == true) {
                return [
                    'status' => true,
                    'message' => 'Tạo đơn hàng thành công',
                    'data' => [
                        'code_order' => $result['order_id'],
                    ],
                ];
            } else {
                return [
                    'status' => false,
                    'message' => $result['msg'],
                ];
            }
        } else {
            return [
                'status' => false,
                'message' => 'Tạo đơn hàng thất bại vui lòng thử lại',
            ];
        }
    }

    public function order($order_codes)
    {

        $curl = curl_init();

        curl_setopt_array($curl, array(
            CURLOPT_URL => 'https://hacklike17.com/api/facebook/get-orders',
            CURLOPT_RETURNTRANSFER => true,
            CURLOPT_ENCODING => '',
            CURLOPT_MAXREDIRS => 10,
            CURLOPT_TIMEOUT => 0,
            CURLOPT_FOLLOWLOCATION => true,
            CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
            CURLOPT_CUSTOMREQUEST => 'POST',
            CURLOPT_POSTFIELDS => 'token=' .  $this->api_token . '&order_ids%5B%5D=' . $order_codes,
            CURLOPT_HTTPHEADER => array(
                'Content-Type: application/x-www-form-urlencoded'
            ),
        ));

        $response = curl_exec($curl);

        curl_close($curl);
        return json_decode($response, true);
    }

    public function sendRequest($url, $data)
    {

        $curl = curl_init();
        curl_setopt_array($curl, array(
            CURLOPT_URL => $url,
            CURLOPT_RETURNTRANSFER => true,
            CURLOPT_ENCODING => '',
            CURLOPT_MAXREDIRS => 10,
            CURLOPT_TIMEOUT => 0,
            CURLOPT_FOLLOWLOCATION => true,
            CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
            CURLOPT_CUSTOMREQUEST => 'POST',
            CURLOPT_POSTFIELDS => $data,
            CURLOPT_HTTPHEADER => array(
                'Content-Type: application/x-www-form-urlencoded'
            ),
        ));

        $response = curl_exec($curl);

        curl_close($curl);

        return json_decode($response, true);
    }
}
