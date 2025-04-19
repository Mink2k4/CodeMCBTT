<?php

namespace App\Http\Controllers\Api\Serivce;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

class Tuongtaccheo extends Controller
{
    public $usename = '';
    public $password = '';
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
        $this->usename = env('USER_TTC');
        $this->password = env('PASS_TTC');
    }


    public function createOrder()
    {
        $login = $this->login();
        if ($login == false) {
            return $data = [
                'status' => false,
                'message' => 'Đăng nhập thất bại'
            ];
            die();
        }else{
            $path = $this->path;
            $data = $this->data;
            if($data['comment']){
                // cắt comment

            }

            $dataPost = [
                'id' => $data['order_link'] ?? '',
                'link' => $data['order_link'] ?? '',
                'sl' => $data['quantity'] ?? '0',
                'is_album' => 'not',
                'dateTime' => now()->addDays(1)->format('Y-m-d H:i:s'),
                'speed' => 1,
                'server' => $data['server_order'] ?? 'null',
                'time_pack' => $data['days'] ?? '0',
                'post' => $data['post'] ?? '0',
                'packet' => $data['quantity'] ?? '0',
                'loaicx' => strtolower($data['reaction']) ?? 'like',
                'noidung' => json_encode(explode("\n", $data['comment'])) ?? '',
                'timeLive' => $data['minutes'] ?? '0',
                'delay' => 1.5,
            ];

            $dataPost = http_build_query($dataPost);
            $result = $this->sendRequest($path, $dataPost);
            $resultArray = json_decode($result, true);
            // dd($result);
            if (isset($resultArray['mess']) && $resultArray['mess'] == 'Mua thành công') {
                return $data = [
                    'status' => true,
                    'message' => 'Đặt hàng thành công',
                    'data' => $result
                ];
            }else{
                return $data = [
                    'status' => false,
                    'message' => $result,
                    'data' => $result
                ];
            }
        }
    }

    public function sendRequest($path, $data)
    {
        $curl = curl_init();
        curl_setopt_array($curl, array(
            CURLOPT_URL => 'https://tuongtaccheo.com/'. $path . '/themvip.php',
            CURLOPT_RETURNTRANSFER => true,
            CURLOPT_ENCODING => '',
            CURLOPT_MAXREDIRS => 10,
            CURLOPT_TIMEOUT => 0,
            CURLOPT_FOLLOWLOCATION => true,
            CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
            CURLOPT_CUSTOMREQUEST => 'POST',
            // set cookie
            CURLOPT_COOKIEJAR => __DIR__ . '/cookiettc.txt',
            CURLOPT_COOKIEFILE => __DIR__ . '/cookiettc.txt',
            CURLOPT_POSTFIELDS => $data,
            CURLOPT_HTTPHEADER => array(
                'authority: tuongtaccheo.com',
                'accept: */*',
                'accept-language: vi;q=0.6',
                'content-type: application/x-www-form-urlencoded; charset=UTF-8',
                'origin: https://tuongtaccheo.com',
                'referer: https://tuongtaccheo.com/tanglike/',
                'sec-ch-ua: "Not.A/Brand";v="8", "Chromium";v="114", "Brave";v="114"',
                'sec-ch-ua-mobile: ?0',
                'sec-ch-ua-platform: "Windows"',
                'sec-fetch-dest: empty',
                'sec-fetch-mode: cors',
                'sec-fetch-site: same-origin',
                'sec-gpc: 1',
                'user-agent: Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/114.0.0.0 Safari/537.36',
                'x-requested-with: XMLHttpRequest'
            ),
        ));
        $response = curl_exec($curl);
        curl_close($curl);
        return $response;
    }
    public function login()
    {

        $curl = curl_init();

        curl_setopt_array($curl, array(
            CURLOPT_URL => 'https://tuongtaccheo.com/login.php',
            CURLOPT_RETURNTRANSFER => true,
            CURLOPT_ENCODING => '',
            CURLOPT_MAXREDIRS => 10,
            CURLOPT_TIMEOUT => 0,
            CURLOPT_FOLLOWLOCATION => true,
            CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
            CURLOPT_CUSTOMREQUEST => 'POST',
            // lưu cookie
            CURLOPT_COOKIEJAR => __DIR__ . '/cookiettc.txt',
            CURLOPT_COOKIEFILE => __DIR__ . '/cookiettc.txt',
            CURLOPT_POSTFIELDS => 'username=' . $this->usename . '&password=' . $this->password . '&submit=ĐĂNG NHẬP',
            CURLOPT_HTTPHEADER => array(
                'authority: tuongtaccheo.com',
                'accept: text/html,application/xhtml+xml,application/xml;q=0.9,image/avif,image/webp,image/apng,*/*;q=0.8,application/signed-exchange;v=b3;q=0.7',
                'accept-language: en,vi-VN;q=0.9,vi;q=0.8,fr-FR;q=0.7,fr;q=0.6,en-US;q=0.5',
                'content-type: application/x-www-form-urlencoded',
                'origin: https://tuongtaccheo.com',
                'referer: https://tuongtaccheo.com/index.php',
                'sec-ch-ua: "Not_A Brand";v="8", "Chromium";v="120", "Google Chrome";v="120"',
                'sec-ch-ua-mobile: ?0',
                'sec-ch-ua-platform: "Windows"',
                'sec-fetch-dest: empty',
                'sec-fetch-mode: cors',
                'sec-fetch-site: same-origin',
                'sec-gpc: 1',
                'user-agent: Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/114.0.0.0 Safari/537.36',
                'x-requested-with: XMLHttpRequest'
            ),
        ));
        $response = curl_exec($curl);
        curl_close($curl);
        if (empty($response)) {
            return false;
        } else {
            if (strpos($response, 'Trang chủ tương tác chéo') !== false) {
                return true;
            } else {
                return false;
            }
        }
    }
}
