<?php

namespace App\Http\Controllers\Custom;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

class CpanelCustomController extends Controller
{
    private $cpanel_server = "https://host139.vietnix.vn:2083/cpsess8165361272/"; // link host: VD: https://web07.vn-server.com:2083/cpsess3957128501/
    private $username_cpanel = "subngons"; //username cpanel
    private $password_cpanel = "@zjviCYUMxkb2va"; //password cpanel


    public function createDomain($domain)
    {
        $url = $this->cpanel_server . "json-api/cpanel?cpanel_jsonapi_apiversion=2&cpanel_jsonapi_module=Park&cpanel_jsonapi_func=park&domain=$domain";

        $curl = curl_init();
        curl_setopt($curl, CURLOPT_POST, true);
        curl_setopt($curl, CURLOPT_SSL_VERIFYPEER, 0);
        curl_setopt($curl, CURLOPT_SSL_VERIFYHOST, 0);
        curl_setopt($curl, CURLOPT_HEADER, 0);
        curl_setopt($curl, CURLOPT_RETURNTRANSFER, 1);
        $header[0] = "Authorization: Basic " . base64_encode($this->username_cpanel . ":" . $this->password_cpanel) . "\n\r";
        curl_setopt($curl, CURLOPT_HTTPHEADER, $header);
        curl_setopt($curl, CURLOPT_URL, $url);
        $return = curl_exec($curl);
        curl_close($curl);
        return json_decode($return, true);
    }

    public function deleteDomain($domain)
    {
        $url = $this->cpanel_server . "json-api/cpanel?cpanel_jsonapi_apiversion=2&cpanel_jsonapi_module=Park&cpanel_jsonapi_func=unpark&domain=$domain";

        $curl = curl_init();
        curl_setopt($curl, CURLOPT_POST, true);
        curl_setopt($curl, CURLOPT_SSL_VERIFYPEER, 0);
        curl_setopt($curl, CURLOPT_SSL_VERIFYHOST, 0);
        curl_setopt($curl, CURLOPT_HEADER, 0);
        curl_setopt($curl, CURLOPT_RETURNTRANSFER, 1);
        $header[0] = "Authorization: Basic " . base64_encode($this->username_cpanel . ":" . $this->password_cpanel) . "\n\r";
        curl_setopt($curl, CURLOPT_HTTPHEADER, $header);
        curl_setopt($curl, CURLOPT_URL, $url);
        $return = curl_exec($curl);
        curl_close($curl);
        return json_decode($return, true);
    }
}
