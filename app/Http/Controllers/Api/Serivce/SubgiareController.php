<?php

namespace App\Http\Controllers\Api\Serivce;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

class SubgiareController extends Controller
{
    private $api_token;
    public $path = "";
    public $server = "";
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
        $this->api_token = env('TOKEN_SUBGIARE');
    }

    public function CreateOrder()
    {
        $url = "https://thuycute.hoangvanlinh.vn/api/service/";
        $headers[] = 'Api-token: ' . $this->api_token;
        $headers[] = 'Content-Type: application/json';
        $uri = $url . $this->path . '/order';
        $data = $this->data;
        $adjustedQuantity = $data['quantity'] - 50;
        $dataPost = [
            'link_post' => $data['order_link'] ?? '',
            'idpost' => $data['order_link'] ?? '',
            'idfb' => $data['order_link'] ?? '',
            'idpage' => $data['order_link'] ?? '',
            'idcomment' => $data['order_link'] ?? '',
            'link_story' => $data['order_link'] ?? '',
            'link_video' => $data['order_link'] ?? '',
            'username' => $data['order_link'] ?? '',
            'server_order' => $data['server_order'] ?? 'null',
            'reaction' => $data['reaction'] ?? 'like',
            'amount' => $adjustedQuantity,
            'speed' => $data['speed'] == '1' ? 'fast' : 'slow',
            'comment' => $data['comment'] ?? '',
            'minutes' => $data['minutes'] ?? '0',
            'time' => $data['time'] ?? '0',
            'days' => $data['days'] ?? '0',
            'post' => $data['post'] ?? '0',
        ];

        $result = $this->curl($uri, $headers, $dataPost);
        if ($result['status'] == true) {
            return $data = [
                'status' => true,
                'message' => $result['message'],
                'data' => $result['data'],
            ];
        } else {
            return $data = [
                'status' => false,
                'message' => 'Không hỗ trợ page pro5 vui lòng cài máy chủ 1!',
            ];
        }
    }

    public function order($order_code)
    {
        $url = "https://thuycute.hoangvanlinh.vn/api/service/";
        $headers[] = 'Api-token: ' . $this->api_token;
        $headers[] = 'Content-Type: application/json';

        $uri = $url . $this->path . '/list';
        $data = [
            'code_orders' => $order_code,
        ];
        $result = $this->curlOrder($uri, $headers, $data);
        return $result;
    }

    public function curl($path, $token, $data = [])
    {
        $ch = curl_init();
        curl_setopt($ch, CURLOPT_URL, $path);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($ch, CURLOPT_POST, true);
        curl_setopt($ch, CURLOPT_HTTPHEADER, $token);
        curl_setopt($ch, CURLOPT_POSTFIELDS, json_encode($data));
        $result = curl_exec($ch);
        curl_close($ch);
        $httpcode = curl_getinfo($ch, CURLINFO_HTTP_CODE);
        $res = [];
        return json_decode($result, true);
    }

    public function curlOrder($path, $token, $data = [], $method = 'POST')
    {
        $ch = curl_init();
        curl_setopt($ch, CURLOPT_URL, $path);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($ch, CURLOPT_POST, true);
        curl_setopt($ch, CURLOPT_HTTPHEADER, $token);
        curl_setopt($ch, CURLOPT_CUSTOMREQUEST, $method);
        curl_setopt($ch, CURLOPT_POSTFIELDS, json_encode($data));
        $result = curl_exec($ch);
        curl_close($ch);
        $httpcode = curl_getinfo($ch, CURLINFO_HTTP_CODE);
        $res = [];
        return json_decode($result, true);
    }
}
