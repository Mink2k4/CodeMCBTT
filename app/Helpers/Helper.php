<?php


function resApi($status = 'error', $message = 'Có lỗi xảy ra', $data = null)
{
    return response()->json([
        'status' => $status,
        'message' => $message,
        'data' => $data
    ]);
}

function DataSite($key)
{
    $data = \App\Models\SiteData::where('domain', request()->getHost())->first();
    //    dd($data);
    if ($data) {
        return $data->$key;
    } else {
        return null;
    }
}

function level($level, $html = true)
{
    if ($html) {
        switch ($level) {
            case 1:
                return '<span class="badge bg-primary badge-primary">Thành viên</span>';
                break;
            case 2:
                return '<span class="badge bg-success badge-success">Cộng tác viên</span>';
                break;
            case 3:
                return '<span class="badge bg-warning badge-warning">Đại lý</span>';
                break;
            case 4:
                return '<span class="badge bg-danger badge-danger">Nhà phân phối</span>';
                break;
            default:
                return '<span class="badge bg-secondary badge-secondary">Khách</span>';
                break;
        }
    } else {
        switch ($level) {
            case 1:
                return 'Thành viên';
                break;
            case 2:
                return 'Cộng tác viên';
                break;
            case 3:
                return 'Đại lý';
                break;
            case 4:
                return 'Nhà phân phối';
                break;
            default:
                return 'Khách';
                break;
        }
    }
}

function statusService($status, $html = true)
{
    if ($html) {
        switch ($status) {
            case 'Active':
                return '<span class="badge bg-success badge-success">Hoạt động</span>';
                break;
            default:
                return '<span class="badge bg-secondary badge-secondary">Bảo trì</span>';
                break;
        }
    } else {
        switch ($status) {
            case 'Active':
                return 'Hoạt động';
                break;
            default:
                return 'Bảo trì';
                break;
        }
    }
}

function statusCard($status)
{
    switch ($status) {
        case 'Pending':
            return '<span class="badge bg-warning badge-warning">Chờ xử lý</span>';
            break;
        case 'Processing':
            return '<span class="badge bg-primary badge-primary">Đang xử lý</span>';
            break;
        case 'Success':
            return '<span class="badge bg-success badge-success">Thành công</span>';
            break;
        case 'Cancel':
            return '<span class="badge bg-danger badge-danger">Đã Hủy</span>';
            break;
        case 'Error':
            return '<span class="badge bg-danger badge-danger">Thẻ Lỗi</span>';
            break;
        default:
            return '<span class="badge bg-secondary badge-secondary">Không xác định</span>';
            break;
    }
}

function statusOrder($status, $html = true)
{
    if ($html) {
        switch ($status) {
            case 'PendingOrder':
                return '<span class="badge bg-warning badge-warning">Chờ xử lý</span>';
                break;
            case 'Pending':
                return '<span class="badge bg-warning badge-warning">Chờ xử lý</span>';
                break;
            case 'Processing':
                return '<span class="badge bg-primary badge-primary">Đang xử lý</span>';
                break;
            case 'In progress':
                return '<span class="badge bg-primary badge-primary">Đang chạy</span>';
                break;
            case 'Active':
                return '<span class="badge bg-info badge-info">Đang chạy</span>';
                break;
            case 'Suspended':
                return '<span class="badge bg-secondary badge-secondary">Tạm dừng</span>';
                break;
            case 'Completed':
                return '<span class="badge bg-success badge-success">Hoàn thành</span>';
                break;
            case 'Success':
                return '<span class="badge bg-success badge-success">Thành công</span>';
                break;
            case 'Refunded':
                return '<span class="badge bg-danger badge-danger">Hoàn tiền</span>';
                break;
            case 'Failed':
                return '<span class="badge bg-danger badge-danger">Thất bại</span>';
                break;
            case 'Partial':
                return '<span class="badge bg-danger badge-danger">Chạy 1 phần</span>';
                break;
            case 'Canceled':
                return '<span class="badge bg-danger badge-danger">Đã hủy</span>';
                break;
            default:
                return '<span class="badge bg-secondary badge-secondary"Đang chạy</span>';
                break;
        }
    } else {
        switch ($status) {
            case 'PendingOrder':
                return 'Chờ xử lý';
                break;
            case 'Processing':
                return 'Đang xử lý';
                break;
            case 'Active':
                return 'Đang chạy';
                break;
            case 'Suspended':
                return 'Tạm dừng';
                break;
            case 'Completed':
                return 'Hoàn thành';
                break;
            case 'Success':
                return 'Thành công';
                break;
            case 'Refunded':
                return 'Hoàn tiền';
                break;
            case 'Failed':
                return 'Thất bại';
                break;
            case 'Cancelled':
                return 'Đã hủy';
                break;
            default:
                return 'Đang chạy';
                break;
        }
    }
}

function thesieure($partner_id, $telco, $code, $serial, $amount, $request_id, $sign, $command = 'charging')
{

    $curl = curl_init();

    curl_setopt_array($curl, array(
        CURLOPT_URL => 'https://thesieure.com/chargingws/v2',
        CURLOPT_RETURNTRANSFER => true,
        CURLOPT_ENCODING => '',
        CURLOPT_MAXREDIRS => 10,
        CURLOPT_TIMEOUT => 0,
        CURLOPT_FOLLOWLOCATION => true,
        CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
        CURLOPT_CUSTOMREQUEST => 'POST',
        CURLOPT_POSTFIELDS => array('telco' => $telco, 'code' => $code, 'serial' => $serial, 'amount' => $amount, 'request_id' => $request_id, 'partner_id' => $partner_id, 'sign' => $sign, 'command' => $command),
        CURLOPT_HTTPHEADER => array(
            'Content-Type: application/json'
        ),
    ));

    $response = curl_exec($curl);

    curl_close($curl);
    return json_decode($response, true);
}

function napthevip($partner_id, $telco, $code, $serial, $amount, $request_id, $sign, $command = 'charging')
{
    $ch = curl_init();
    curl_setopt($ch, CURLOPT_URL, 'https://napthevip.vn/api/charging');
    curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
    curl_setopt($ch, CURLOPT_POST, 1);
    curl_setopt($ch, CURLOPT_TIMEOUT, 60);
    $post = array(
        'telco' => $telco,
        'code' => $code,
        'serial' => $serial,
        'amount' => $amount,
        'request_id' => $request_id,
        'partner_id' => $partner_id,
        'sign' => $sign,
        'command' => $command
    );
    curl_setopt($ch, CURLOPT_POSTFIELDS, json_encode($post));

    $headers = array();
    $headers[] = 'Content-Type: application/json';
    curl_setopt($ch, CURLOPT_HTTPHEADER, $headers);

    $result = curl_exec($ch);
    if (curl_errno($ch)) {
        echo 'Error:' . curl_error($ch);
    }
    curl_close($ch);
    return json_decode($result, true);
}

function gachthe1s($partner_id, $telco, $code, $serial, $amount, $request_id, $sign, $command = 'charging')
{
    $url = "https://gachthe1s.com/chargingws/v2";
    $curl = curl_init();

    curl_setopt_array($curl, array(
        CURLOPT_URL => 'https://gachthe1s.com/chargingws/v2',
        CURLOPT_RETURNTRANSFER => true,
        CURLOPT_ENCODING => '',
        CURLOPT_MAXREDIRS => 10,
        CURLOPT_TIMEOUT => 0,
        CURLOPT_FOLLOWLOCATION => true,
        CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
        CURLOPT_CUSTOMREQUEST => 'POST',
        CURLOPT_POSTFIELDS => array('telco' => $telco, 'code' => $code, 'serial' => $serial, 'amount' => $amount, 'request_id' => $request_id, 'partner_id' => $partner_id, 'sign' => $sign, 'command' => $command),
    ));

    $response = curl_exec($curl);

    curl_close($curl);
    return json_decode($response, true);
}

function priceServer($server, $level)
{
    $server_service = \App\Models\ServerService::where('id', $server)->first();
    if ($server_service) {
        switch ($level) {
            case 5:
            case 1:
                return $server_service->price;
                break;
            case 2:
                return $server_service->price_collaborator;
                break;
            case 3:
                return $server_service->price_agency;
                break;
            case 4:
                return $server_service->price_distributor;
                break;
            default:
                return 0;
                break;
        }
    } else {
        return 0;
    }
}

function formatDomain($domain)
{
    $domain = str_replace('https://', '', $domain);
    $domain = str_replace('http://', '', $domain);
    $domain = str_replace('www.', '', $domain);

    return $domain;
}

function getDomain()
{
    return request()->getHost();
}

function whois($domain)
{
    $url = 'https://whois.inet.vn/api/whois/domainspecify/';
    $url .= $domain;
    $ch = curl_init();
    curl_setopt($ch, CURLOPT_URL, $url);
    curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
    $output = curl_exec($ch);
    curl_close($ch);
    $data = json_decode($output, true);
    // var_dump($data);
    $row = [];
    if (isset($data)) {
        if ($data['code'] == 0) {
            return $data;
        } else {
            return false;
        }
    }
}
function getUid($link)
{
    $ch = curl_init();
    curl_setopt($ch, CURLOPT_URL, 'https://id.traodoisub.com/api.php');
    curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
    curl_setopt($ch, CURLOPT_POST, 1);
    curl_setopt($ch, CURLOPT_POSTFIELDS, "link=$link");
    curl_setopt($ch, CURLOPT_ENCODING, 'gzip, deflate');
    $headers = array();
    $headers[] = 'Authority: id.traodoisub.com';
    $headers[] = 'Accept: application/json, text/javascript, */*; q=0.01';
    $headers[] = 'Accept-Language: vi,en;q=0.9,en-GB;q=0.8,en-US;q=0.7';
    $headers[] = 'Content-Type: application/x-www-form-urlencoded; charset=UTF-8';
    $headers[] = 'Origin: https://id.traodoisub.com';
    $headers[] = 'Referer: https://id.traodoisub.com/';
    $headers[] = 'Sec-Ch-Ua: \"Not?A_Brand\";v=\"8\", \"Chromium\";v=\"108\", \"Microsoft Edge\";v=\"108\"';
    $headers[] = 'Sec-Ch-Ua-Mobile: ?0';
    $headers[] = 'Sec-Ch-Ua-Platform: \"Windows\"';
    $headers[] = 'Sec-Fetch-Dest: empty';
    $headers[] = 'Sec-Fetch-Mode: cors';
    $headers[] = 'Sec-Fetch-Site: same-origin';
    $headers[] = 'User-Agent: Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/108.0.0.0 Safari/537.36 Edg/108.0.1462.46';
    $headers[] = 'X-Requested-With: XMLHttpRequest';
    curl_setopt($ch, CURLOPT_HTTPHEADER, $headers);

    $result = curl_exec($ch);
    if (curl_errno($ch)) {
        echo 'Error:' . curl_error($ch);
        die();
    }
    curl_close($ch);
    $data = json_decode($result, true);

    if ($data['code'] == 200) {
        return $data = [
            'status' => true,
            'id' => $data['id']
        ];
    } else {
        return $data = [
            'status' => false,
            'message' => $data['error']
        ];
    }
}
