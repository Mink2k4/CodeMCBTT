<?php

namespace App\Http\Controllers\Custom;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

class CloudflareCustomController extends Controller
{
    public $email = "subngons1.me@gmail.com"; //email tài khoản cloudflade
    public $global_key = "eae2e212a496c5681912c7c19153ff5336f09"; //api key lấy từ https://dash.cloudflare.com/profile/api-tokens
    public $account_id = "935fb2029f79075d8cf8e051de0979b6"; // account id lấy click vào doamin rồi kéo xuống tìm acccount id
    public $token = "4021a_R_pjY9X8DjF4D54ae4yMxThA5wExOkvoyL"; //tạo token từ https://dash.cloudflare.com/profile/api-tokens
    public $ip_host = "103.200.23.139"; // ip của hosting

    public function profile()
    {
        $url = "https://api.cloudflare.com/client/v4/user";
        $curl = curl_init($url);
        curl_setopt($curl, CURLOPT_URL, $url);
        curl_setopt($curl, CURLOPT_RETURNTRANSFER, true);
        $headers = array(
            "X-Auth-Email: " . $this->email,
            "X-Auth-Key: " . $this->global_key,
            "Content-Type: application/json",
        );
        curl_setopt($curl, CURLOPT_HTTPHEADER, $headers);
        //for debug only!
        curl_setopt($curl, CURLOPT_SSL_VERIFYHOST, false);
        curl_setopt($curl, CURLOPT_SSL_VERIFYPEER, false);
        $resp = curl_exec($curl);
        curl_close($curl);
        return json_decode($resp, true);
    }

    public function addDomain($domainName)
    {
        $ch = curl_init();
        curl_setopt($ch, CURLOPT_URL, 'https://api.cloudflare.com/client/v4/zones');
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
        curl_setopt($ch, CURLOPT_POST, 1);
        curl_setopt($ch, CURLOPT_POSTFIELDS, json_encode([
            'accounts' => $this->account_id,
            'name' => $domainName,
            'jump_start' => true
        ]));

        $headers = array();
        $headers[] = 'X-Auth-Key: ' . $this->global_key;
        $headers[] = 'X-Auth-Email: ' . $this->email;
        $headers[] = 'Content-Type: application/json';
        curl_setopt($ch, CURLOPT_HTTPHEADER, $headers);
        $result = curl_exec($ch);
        if (curl_errno($ch)) {
            echo 'Error:' . curl_error($ch);
        }
        curl_close($ch);
        return json_decode($result, true);
    }

    public function deleteDomain($zone_id)
    {

        $url = "https://api.cloudflare.com/client/v4/zones/$zone_id";

        $curl = curl_init($url);
        curl_setopt($curl, CURLOPT_URL, $url);
        curl_setopt($curl, CURLOPT_CUSTOMREQUEST, "DELETE");
        curl_setopt($curl, CURLOPT_RETURNTRANSFER, true);

        $headers = array(
            "Content-Type: application/json",
            "X-Auth-Email: " . $this->email,
            "X-Auth-Key: " . $this->global_key,
        );
        curl_setopt($curl, CURLOPT_HTTPHEADER, $headers);
        //for debug only!
        curl_setopt($curl, CURLOPT_SSL_VERIFYHOST, false);
        curl_setopt($curl, CURLOPT_SSL_VERIFYPEER, false);

        $resp = curl_exec($curl);
        curl_close($curl);
        // var_dump($resp);
        return json_decode($resp, true);
    }

    public function findDomain($domain_name)
    {

        $url = "https://api.cloudflare.com/client/v4/zones";
        $curl = curl_init($url);
        curl_setopt($curl, CURLOPT_URL, $url);
        curl_setopt($curl, CURLOPT_RETURNTRANSFER, true);

        $headers = array(
            "Authorization: Bearer " . $this->token,
            "Content-Type: application/json",
        );
        curl_setopt($curl, CURLOPT_HTTPHEADER, $headers);
        //for debug only!
        curl_setopt($curl, CURLOPT_SSL_VERIFYHOST, false);
        curl_setopt($curl, CURLOPT_SSL_VERIFYPEER, false);
        $resp = curl_exec($curl);
        curl_close($curl);
        $data = json_decode($resp, true);
        $d = $domain_name;
        $v = [];
        foreach ($data['result'] as $v) {
            $id = $v['id'];
            $name = $v['name'];
            $check = strpos($name, $d);
            if ($check !== false) {
                $v['zone_id'] = $id;
                break;
            }
        }
        return $v;
    }

    public function infoDomain($zone_id)
    {
        $url = "https://api.cloudflare.com/client/v4/zones/$zone_id";

        $curl = curl_init($url);
        curl_setopt($curl, CURLOPT_URL, $url);
        curl_setopt($curl, CURLOPT_RETURNTRANSFER, true);

        $headers = array(
            "Content-Type: application/json",
            "Authorization: Bearer " . $this->token,
        );
        curl_setopt($curl, CURLOPT_HTTPHEADER, $headers);
        //for debug only!
        curl_setopt($curl, CURLOPT_SSL_VERIFYHOST, false);
        curl_setopt($curl, CURLOPT_SSL_VERIFYPEER, false);

        $resp = curl_exec($curl);
        curl_close($curl);
        return json_decode($resp, true);
    }

    public function recordDomain($zone_id)
    {
        $url = "https://api.cloudflare.com/client/v4/zones/$zone_id/dns_records";

        $curl = curl_init($url);
        curl_setopt($curl, CURLOPT_URL, $url);
        curl_setopt($curl, CURLOPT_RETURNTRANSFER, true);

        $headers = array(
            "X-Auth-Email: " . $this->email,
            "X-Auth-Key: " . $this->global_key,
            "Content-Type: application/json",
        );
        curl_setopt($curl, CURLOPT_HTTPHEADER, $headers);
        //for debug only!
        curl_setopt($curl, CURLOPT_SSL_VERIFYHOST, false);
        curl_setopt($curl, CURLOPT_SSL_VERIFYPEER, false);

        $resp = curl_exec($curl);
        curl_close($curl);
        return json_decode($resp, true);
    }

    public function dnsRecord($zone_id)
    {
        $ch = curl_init();

        curl_setopt($ch, CURLOPT_URL, "https://api.cloudflare.com/client/v4/zones/$zone_id/dns_records");
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
        curl_setopt($ch, CURLOPT_CUSTOMREQUEST, 'GET');


        $headers = array();
        $headers[] = 'X-Auth-Email: ' . $this->email;
        $headers[] = 'X-Auth-Key: ' . $this->global_key;
        $headers[] = 'Content-Type: application/json';
        curl_setopt($ch, CURLOPT_HTTPHEADER, $headers);

        $result = curl_exec($ch);
        if (curl_errno($ch)) {
            echo 'Error:' . curl_error($ch);
        }
        curl_close($ch);
        return json_decode($result, true);
        /* $url = "https://api.cloudflare.com/client/v4/zones/$zone_id/dns_records/$dns_record";

        $curl = curl_init($url);
        curl_setopt($curl, CURLOPT_URL, $url);
        curl_setopt($curl, CURLOPT_RETURNTRANSFER, true);

        $headers = array(
            "X-Auth-Email: " . $this->email,
            "X-Auth-Key: " . $this->global_key,
            "Content-Type: application/json",
        );
        curl_setopt($curl, CURLOPT_HTTPHEADER, $headers);
        //for debug only!
        curl_setopt($curl, CURLOPT_SSL_VERIFYHOST, false);
        curl_setopt($curl, CURLOPT_SSL_VERIFYPEER, false);

        $resp = curl_exec($curl);
        curl_close($curl);
        return json_decode($resp, true); */
    }

    public function scanDns($zone_id)
    {

        $url = "https://api.cloudflare.com/client/v4/zones/$zone_id/dns_records/scan";

        $curl = curl_init($url);
        curl_setopt($curl, CURLOPT_URL, $url);
        curl_setopt($curl, CURLOPT_POST, true);
        curl_setopt($curl, CURLOPT_RETURNTRANSFER, true);

        $headers = array(
            "X-Auth-Email: " . $this->email,
            "X-Auth-Key: " . $this->global_key,
            "Content-Type: application/json",
            "Content-Length: 0",
        );
        curl_setopt($curl, CURLOPT_HTTPHEADER, $headers);
        //for debug only!
        curl_setopt($curl, CURLOPT_SSL_VERIFYHOST, false);
        curl_setopt($curl, CURLOPT_SSL_VERIFYPEER, false);

        $resp = curl_exec($curl);
        curl_close($curl);
        return json_decode($resp, true);
    }

    public function createDns($zone_id)
    {
        $url = "https://api.cloudflare.com/client/v4/zones/$zone_id/dns_records";
        $ip_host = $this->ip_host;
        $curl = curl_init($url);
        curl_setopt($curl, CURLOPT_URL, $url);
        curl_setopt($curl, CURLOPT_POST, true);
        curl_setopt($curl, CURLOPT_RETURNTRANSFER, true);

        $headers = array(
            "X-Auth-Email: " . $this->email,
            "X-Auth-Key: " . $this->global_key,
            "Content-Type: application/json",
        );
        curl_setopt($curl, CURLOPT_HTTPHEADER, $headers);

        $data = [
            "type" => "A", "name" => '@', "content" => $this->ip_host, "ttl" => 1, "priority" => 10, "proxied" => true
        ];
        curl_setopt($curl, CURLOPT_POSTFIELDS, json_encode($data));

        //for debug only!
        curl_setopt($curl, CURLOPT_SSL_VERIFYHOST, false);
        curl_setopt($curl, CURLOPT_SSL_VERIFYPEER, false);

        $resp = curl_exec($curl);
        curl_close($curl);
        return json_decode($resp, true);
    }

    public function updateDns($zone_id, $dns_record)
    {

        $curl = curl_init();

        curl_setopt_array($curl, array(
            CURLOPT_URL => 'https://api.cloudflare.com/client/v4/zones/' .$zone_id.  '/dns_records/' . $dns_record,
            CURLOPT_RETURNTRANSFER => true,
            CURLOPT_ENCODING => '',
            CURLOPT_MAXREDIRS => 10,
            CURLOPT_TIMEOUT => 0,
            CURLOPT_FOLLOWLOCATION => true,
            CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
            CURLOPT_CUSTOMREQUEST => 'PUT',
            CURLOPT_POSTFIELDS => '{
            "content": "' . $this->ip_host . '",
            "name": ,
            "proxied": false,
            "type": "A",
            "comment": "' . getDomain() .' cập nhật IP tự động",
            "tags": [
                "owner:dns-team"
            ],
            "ttl": 3600
            }',
            CURLOPT_HTTPHEADER => array(
                'Content-Type: application/json',
                'X-Auth-Email: ' . $this->email,
                'X-Auth-Key: ' . $this->global_key,
            ),
        ));

        $response = curl_exec($curl);
        curl_close($curl);
        return json_decode($response, true);
    }

    public function deleteDns($zone_id, $dns_record)
    {

        $url = "https://api.cloudflare.com/client/v4/zones/$zone_id/dns_records/$dns_record";

        $curl = curl_init($url);
        curl_setopt($curl, CURLOPT_URL, $url);
        curl_setopt($curl, CURLOPT_CUSTOMREQUEST, "DELETE");
        curl_setopt($curl, CURLOPT_RETURNTRANSFER, true);

        $headers = array(
            "X-Auth-Email: " . $this->email,
            "X-Auth-Key: " . $this->global_key,
            "Content-Type: application/json",
        );
        curl_setopt($curl, CURLOPT_HTTPHEADER, $headers);
        //for debug only!
        curl_setopt($curl, CURLOPT_SSL_VERIFYHOST, false);
        curl_setopt($curl, CURLOPT_SSL_VERIFYPEER, false);

        $resp = curl_exec($curl);
        curl_close($curl);
        return json_decode($resp, true);
    }
}

#tips
#step 1: Thêm tên miền
/* $data = Cloudflare::addDomain('luongbinhduong.ga');
print_r($data); */

#step 2: Thông tin tên miền lấy data
// $data = Cloudflare::findDomain();
/* foreach($data['result'] as $v){
    $id = $v['id'];
    $name = $v['name'];
    $d = "shopcodengon.net";
    $check = strpos($name, $d);
    if($check !== false){
        echo $id . "|" . $name;
    }
} */
// $data = Cloudflare::infoDomain('abc3fd07df0d6fc1cf1157e3f0722d8c');

#step 3: Thông tin tên miền lấy data id để sử dụng step 4
// $data = Cloudflare::recordDomain('65d06f72552936b70aae43b2fb0b7098');
// $data = Cloudflare::dnsRecord('ea706bdc19de26348a6d90b32503a06f', 'abc3fd07df0d6fc1cf1157e3f0722d8c');

#step 4: Nhập zone id của tên miền và và id của tên miền lấy từ step 3
// $data = Cloudflare::deleteDns('75288884cd23824e712fe04946639f98', '044c123ed7501712089a9342c72bbb43');

#step 5: Nhập host cho domain site con
// $data = Cloudflare::createDns('75288884cd23824e712fe04946639f98');

#step 6: Cập nhật dns
// $data = Cloudflare::updateDns('65d06f72552936b70aae43b2fb0b7098', '65e9d9c19f37fdb95941178509d40d6a');

/* echo "<pre>";
print_r($data);
echo "</pre>"; */
