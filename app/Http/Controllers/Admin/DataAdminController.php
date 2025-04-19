<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Api\Serivce\DinoController;
use App\Http\Controllers\Api\Serivce\JAPController;
use App\Http\Controllers\Api\Serivce\FlareController;
use App\Http\Controllers\Api\Serivce\CostDevideTwoPointTwo;
use App\Http\Controllers\Api\Serivce\CostDevideOnePointFive;
use App\Http\Controllers\Api\Serivce\CostNormal;
use App\Http\Controllers\Api\Serivce\CostEightPercent;
use App\Http\Controllers\Api\Serivce\CostTwoPerCent;
use App\Http\Controllers\Api\Serivce\CostTenPercent;
use App\Http\Controllers\Api\Serivce\Trumvip;
use App\Http\Controllers\Api\Serivce\N1panel;
use App\Http\Controllers\Controller;
use App\Http\Controllers\Custom\CloudflareCustomController;
use App\Models\AccountRecharge;
use App\Models\Activities;
use App\Models\DataHistory;
use App\Models\HistoryCard;
use App\Models\HistoryRecharge;
use App\Models\Notification;
use App\Models\Orders;
use App\Models\ServerService;
use App\Models\Service;
use App\Models\ServiceSocial;
use App\Models\SiteData;
use App\Models\User;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Validator;
use App\Http\Controllers\Custom\TelegramCustomController;
use App\Models\SiteCon;
use App\Http\Controllers\Custom\CpanelCustomController;
use Illuminate\Support\Facades\Log;


class DataAdminController extends Controller
{
    public function __construct()
    {
        // $this->middleware('xss')->only(['']);
    }

    public function websiteConfig(Request $request)
    {
        $DataSite = SiteData::where('domain', getDomain())->first();
        foreach ($request->all() as $key => $value) {
            if ($key != '_token') {
                $DataSite->$key = $value;
            }
            if (isset($request->notice_order)) {
                $DataSite->notice_order = 'on';
            } else {
                $DataSite->notice_order = 'off';
            }
            if (isset($request->notice_login)) {
                $DataSite->notice_login = 'on';
            } else {
                $DataSite->notice_login = 'off';
            }
        }
        $DataSite->save();
        return redirect()->back()->with('success', 'Cập nhật thành công');
    }

    public function websiteTheme(Request $request)
    {
        $DataSite = SiteData::where('domain', request()->getHost())->first();
        foreach ($request->only(['logo', 'logo_mini', 'favicon', 'image_seo']) as $key => $value) {
            if ($key != '_token') {
                $DataSite->$key = $value;
            }
        }
        $DataSite->save();
        return redirect()->back()->with('success', 'Cập nhật thành công');
    }

    public function userEdit($id, Request $request)
    {
        $valid = Validator::make($request->all(), [
            'name' => 'string|max:255',
            'email' => 'string|email|max:255',
            'username' => 'string|max:255',
            'balance' => 'numeric',
            'level' => 'numeric',
            'status' => 'string|in:active,banner',
        ]);

        if ($valid->fails()) {
            return redirect()->back()->with('error', $valid->errors()->first());
        } else {
            $user = User::where('domain', getDomain())->where('id', $id)->first();
            if ($user) {
                foreach ($request->only(['name', 'email', 'username', 'balance', 'level', 'status']) as $key => $value) {
                    if ($key != '_token') {
                        $user->$key = $value;
                    }
                }
                $user->save();
                return redirect()->back()->with('success', 'Cập nhật thành công');
            } else {
                return redirect()->back()->with('error', 'Không tìm thấy người dùng');
            }
        }
    }

    public function userChangePassword($id, Request $request)
    {
        $valid = Validator::make($request->all(), [
            'password' => 'required|string|min:6',
            'password_confirm' => 'required|string|min:6|same:password',
        ]);

        if ($valid->fails()) {
            return redirect()->back()->with('error', $valid->errors()->first());
        } else {
            $user = User::where('domain', getDomain())->where('id', $id)->first();
            if ($user) {
                $user->password = Hash::make($request->password);
                $user->save();
                return redirect()->back()->with('success', 'Cập nhật thành công');
            } else {
                return redirect()->back()->with('error', 'Không tìm thấy người dùng');
            }
        }
    }

    public function userEditBalance(Request $request)
    {
        $valid = Validator::make($request->all(), [
            'username' => 'required|string|max:255',
            'action' => 'required|string|in:plus,minus',
            'balance' => 'required|numeric',
        ]);

        if ($valid->fails()) {
            return redirect()->back()->with('error', $valid->errors()->first());
        } else {
            $action = $request->action === 'plus' ? '+' : '-';
            $user = User::where('domain', getDomain())->where('username', $request->username)->first();
            if ($user) {
                $balance = $user->balance;
                $user->balance = $request->action === 'plus' ? $user->balance + $request->balance : $user->balance - $request->balance;
                $user->total_recharge = $request->action === 'plus' ? $user->total_recharge + $request->balance : $user->total_recharge - $request->balance;
                $user->save();
                DataHistory::create([
                    'username' => $user->username,
                    'action' => $action,
                    'data' => $request->balance,
                    'old_data' => $balance,
                    'new_data' => $user->balance,
                    'ip' => $request->ip(),
                    'data_json' => json_encode([
                        'username' => $user->username,
                        'action' => $action,
                        'data' => $request->balance,
                        'old_data' => $balance,
                        'new_data' => $user->balance,
                        'ip' => $request->ip(),
                    ]),
                    'description' => "Quản trị viên đã thay đổi số dư tài khoản của bạn",
                    'domain' => getDomain(),
                ]);
                return redirect()->back()->with('success', 'Cập nhật thành công');
            } else {
                return redirect()->back()->with('error', 'Không tìm thấy người dùng');
            }
        }
    }

    public function userDelete($id)
    {
        $user = User::where('domain', getDomain())->where('id', $id)->first();
        if ($user) {
            $user->delete();
            return resApi('success', 'Xóa thành công');
        } else {
            return resApi('error', 'Không tìm thấy người dùng');
        }
    }

    public function notificationModal(Request $request)
    {
        $valid = Validator::make($request->all(), [
            'notice-modal' => 'required',
        ]);

        if ($valid->fails()) {
            return redirect()->back()->with('error', $valid->errors()->first());
        } else {
            $DataSite = SiteData::where('domain', getDomain())->first();
            $DataSite->notice = $request->input('notice-modal');
            $DataSite->save();
            return redirect()->back()->with('success', 'Cập nhật thành công');
        }
    }

    public function notification(Request $request)
    {
        $valid = Validator::make($request->all(), [
            'name' => 'required|string|max:255',
            'notice' => 'required|string',
        ]);

        if ($valid->fails()) {
            return redirect()->back()->with('error', $valid->errors()->first());
        } else {

            // class random primary, success, warning, danger, info
            $class = ['primary', 'success', 'warning', 'danger', 'info'];
            $class = $class[rand(0, 4)];

            Notification::create([
                'name' => $request->name,
                'content' => $request->notice,
                'domain' => getDomain(),
                'class' => $class,
            ]);

            return redirect()->back()->with('success', 'C?p nh?t thành công');
        }
    }

    public function notificationDelete($id)
    {
        $notification = Notification::where('domain', getDomain())->where('id', $id)->first();
        if ($notification) {
            $notification->delete();
            return resApi('success', 'Xóa thành công');
        } else {
            return resApi('error', 'Không tìm thấy thông báo');
        }
    }

    public function activity(Request $request)
    {
        $valid = Validator::make($request->all(), [
            'name' => 'required|string|max:255',
            'content' => 'required|string',
        ]);

        if ($valid->fails()) {
            return redirect()->back()->with('error', $valid->errors()->first());
        } else {
            $randClass = ['primary', 'success', 'warning', 'danger', 'info'];
            $randClass = $randClass[rand(0, 4)];
            Activities::create([
                'name' => $request->name,
                'content' => $request->content,
                'status' => $randClass,
                'domain' => getDomain(),
            ]);
            return redirect()->back()->with('success', 'C?p nh?t thành công');
        }
    }

    public function activityDelete($id)
    {
        $activity = Activities::where('domain', getDomain())->where('id', $id)->first();
        if ($activity) {
            $activity->delete();
            return resApi('success', 'Xóa thành công');
        } else {
            return resApi('error', 'Không tìm thấy hoạt động');
        }
    }


    public function rechargeConfig(Request $request)
    {
        $valid = Validator::make($request->all(), [
            'type' => 'required|string|in:mbbank,vietcombank,momo,perfectmoney,tether,bidv,cake',
            'name' => 'required|string',
            'account' => 'required|string',
            'stk' => 'nullable|string',
            'password' => 'required|string',
            'api_token' => 'required',
            'quy_doi' => 'nullable|string'
        ]);

        if ($valid->fails()) {
            return resApi('error', $valid->errors()->first());
        } else {
            $account_recharge = AccountRecharge::where('domain', getDomain())->where('type', $request->type)->first();
            if ($account_recharge) {
                return resApi('error', 'Tài khoản này đã tồn tại');
            } else {
                switch ($request->type) {
                    case 'mbbank':
                        $bank = 'Mbbank';
                        $logo = '/assets/images/bank/mbb.png';
                        $qr = "https://img.vietqr.io/image/" . 'mbbank-' . $request->stk . '-compact2.jpg?amount=10000&accountName=' . $request->name;
                        break;
                    case 'vietcombank':
                        $bank = 'Vietcombank';
                        $logo = '/assets/images/bank/vcb.png';
                        $qr = 'https://img.vietqr.io/image/vietcombank-' . $request->stk . '-compact2.jpg?amount=10000&accountName=' . $request->name;
                        break;
                    case 'momo':
                        $bank = 'Momo';
                        $logo = '/assets/images/bank/momo.png';
                        $qr = "https://chart.googleapis.com/chart?chs=480x480&cht=qr&choe=UTF-8&chl=2|99|" . $request->stk . "|%3C?=" . $request->name . "?%3E|tuanminh@gmail.com|0|0|0|";
                        break;
                    case 'perfectmoney':
                        $bank = 'perfectmoney';
                        $logo = '/assets/images/bank/perfectmoney.png';
                        $qr = '';
                        break;
                    case 'tether':
                        $bank = 'tether';
                        $logo = '/assets/images/bank/tether.jpg';
                        $qr = '';
                        break;
                    case 'bidv':
                        $bank = 'bidv';
                        $logo = '/assets/images/bank/bidv.png';
                        $qr = '';
                        break;
                    case 'cake':
                        $bank = 'cake';
                        $logo = '/assets/images/bank/cake.png';
                        $qr = '';
                        break;
                }

                AccountRecharge::create([
                    'type' => $request->type,
                    'account_name' => $request->name,
                    'account' => $request->account,
                    'account_number' => $request->stk,
                    'password' => $request->password,
                    'api_token' => $request->api_token,
                    'logo' => $logo,
                    'qr_code' => $qr,
                    'quy_doi' => $request->quy_doi,
                    'domain' => getDomain(),
                ]);
                return resApi('success', 'Cập nhật thành công');
            }
        }
    }

    public function rechargeDelete($id)
    {
        $account_recharge = AccountRecharge::where('domain', getDomain())->where('id', $id)->first();
        if ($account_recharge) {
            $account_recharge->delete();
            return redirect()->back()->with('success', 'Xóa thành công');
        } else {
            return resApi('error', 'Không tìm thấy tài khoản');
        }
    }

    public function rechargePromotion(Request $request)
    {
        $valid = Validator::make($request->all(), [
            'action' => 'required|string|in:show,hide',
            'promotion' => 'required|numeric',
            'start_promotion' => 'required',
            'end_promotion' => 'required',
        ]);

        if ($valid->fails()) {
            return redirect()->back()->with('error', $valid->errors()->first());
        } else {
            $dataSite = SiteData::where('domain', getDomain())->first();
            if ($dataSite) {
                $dataSite->update([
                    'recharge_promotion' => $request->promotion,
                    'start_promotion' => $request->start_promotion,
                    'end_promotion' => $request->end_promotion,
                    'show_promotion' => $request->action,
                ]);
                return redirect()->back()->with('success', 'Cập nhật thành công');
            } else {
                return redirect()->back()->with('error', 'Lỗi không mong muốn xảy ra');
            }
        }
    }

    public function configTelegram(Request $request)
    {
        $valid = Validator::make($request->all(), [
            'telegram_bot' => 'required|string|url',
            'telegram_token' => 'required|string',
            'telegram_chat_id' => 'required|numeric',
            'balance_telegram' => 'required|numeric',
        ]);

        if ($valid->fails()) {
            return redirect()->back()->with('error', $valid->errors()->first());
        } else {
            $dataSite = SiteData::where('domain', getDomain())->first();
            if ($dataSite) {

                $dataSite->update([
                    'telegram_bot' => $request->telegram_bot,
                    'telegram_token' => $request->telegram_token,
                    'telegram_chat_id' => $request->telegram_chat_id,
                    'balance_telegram' => $request->balance_telegram,
                ]);

                if (empty($dataSite->telegram_callback)) {
                    $tele = new TelegramCustomController();
                    $dta = $tele->setWebhook();
                    // get id bot
                    // $bot = $tele->getMe();
                    // $bot_id = $bot['result']['id'];

                    if ($dta) {
                        $dataSite->update([
                            'telegram_callback' => route('callback.telegram.v1'),
                            // 'telegram_chat_id' => $bot_id,
                        ]);
                    }
                }
                return redirect()->back()->with('success', 'Cập nhật thành công');
            } else {
                return redirect()->back()->with('error', 'Lỗi không mong muốn xảy ra');
            }
        }
    }

    public function serverAutoCreate(Request $request)
    {
        if (getDomain() != env('PARENT_SITE')) {
            $valid = Validator::make($request->all(), [
                'action' => 'required|string|in:update,add',
                'type' => 'required|string',
                'price' => 'required|numeric',
                'price_collaborator' => 'required|numeric',
                'price_agency' => 'required|numeric',
                'price_distributor' => 'required|numeric',
            ]);

            if ($valid->fails()) {
                return resApi('error', $valid->errors()->first());
            } else {
                $server_parent = ServerService::where('domain', env('PARENT_SITE'))->get();

                $count = 0;
                $serverParentCount = count($server_parent);
                for ($i = 0; $i < $serverParentCount; $i++) {
                    $server = $server_parent[$i];
                    $admin = User::where('username', DataSite('username_web'))->where('domain', env('PARENT_SITE'))->first();
                    $price = 0;


                    if ($admin) {
                        switch ($admin->level) {
                            case 1:
                                $price = $server->price;
                                break;
                            case 2:
                                $price = $server->price_collaborator;
                                break;
                            case 3:
                                $price = $server->price_agency;
                                break;
                            case 4:
                                $price = $server->price_distributor;
                                break;
                        }
                    }

                    $serverServices = ServerService::where('domain', getDomain())
                        ->where('social_id', $server->social_id)
                        ->where('service_id', $server->service_id)
                        ->get();

                    if ($request->action == 'update' && $serverServices) {
                        // $serverServicesCopy = clone $serverServices; // T?o b?n sao c?a d?i tu?ng
                        $serverServices[$i]->update([
                            // 'actual_price' => $price,
                            // 'status' => $server->status,
                            // 'server' => $server->server,
                            // 'actual_service' => $server->actual_service,
                            // 'actual_server' => $server->actual_server,
                            // 'actual_path' => $server->actual_path,
                            'id_lienket' => $server->id,
                        ]);

                        $count++;
                    } elseif ($request->action == 'add') {
                        $priceChange = $price;
                        $price_collaborator = 0;
                        $price_agency = 0;
                        $price_distributor = 0;
                        switch ($request->type) {
                            case 'default':
                                // Không thay d?i giá
                                $price_collaborator = $server->price_collaborator;
                                $price_agency = $server->price_agency;
                                $price_distributor = $server->price_distributor;

                                break;
                            case 'percent':
                                // Thay d?i theo ph?n tram
                                $priceChange += ($priceChange * ($request->price / 100));
                                $price_collaborator = ($server->price_collaborator + ($server->price_collaborator * ($request->price_collaborator / 100)));
                                $price_agency = $server->price_agency + ($server->price_agency * ($request->price_agency / 100));
                                $price_distributor = $server->price_distributor + ($server->price_distributor * ($request->price_distributor / 100));

                                break;
                            case 'add':
                                // Thay d?i b?ng cách c?ng thêm
                                $priceChange += $request->price;
                                $price_collaborator = $server->price_collaborator +  $request->price_collaborator;
                                $price_agency = $server->price_agency + $request->price_agency;
                                $price_distributor = $server->price_distributor + $request->price_distributor;
                                break;
                            default:
                                // Không thay d?i giá
                                break;
                        }


                        // Ki?m tra n?u d?ch v? dã t?n t?i, n?u không, thì t?o m?i.
                        $check_server = ServerService::where('domain', getDomain())
                            ->where('social_id', $server->social_id)
                            ->where('service_id', $server->service_id)
                            ->where('server', $server->server)
                            ->first();

                        if (!$check_server) {
                            ServerService::create([
                                'name' => $server->name,
                                'social_id' => $server->social_id,
                                'service_id' => $server->service_id,
                                'server' => $server->server,
                                'price' => $priceChange,
                                'price_collaborator' => $price_collaborator,
                                'price_agency' => $price_agency,
                                'price_distributor' => $price_distributor,
                                'min' => $server->min,
                                'max' => $server->max,
                                'title' => $server->title,
                                'description' => $server->description,
                                'status' => $server->status,
                                'actual_price' => $server->price,
                                'actual_service' => $server->actual_service,
                                'actual_server' => $server->actual_server,
                                'actual_path' => $server->actual_path,
                                'action' => $server->action,
                                'id_lienket' => $server->id,
                                'service_list' => $server->service_list,
                                'domain' => getDomain(),
                            ]);
                        }

                        $count++;
                    }
                }


                if ($request->action == 'update') {
                    return resApi('success', 'Cập nhật thành công ' . $count . ' dịch vụ');
                } elseif ($request->action == 'add') {
                    return resApi('success', 'Thêm thành công ' . $count . ' dịch vụ');
                } else {
                    return resApi('error', 'Dữ liệu không hợp lệ?');
                }
            }
        } else {
            return abort(404);
        }
    }


    public function websiteChildActive(Request $request)
    {
        $valid = Validator::make($request->all(), [
            'domain' => 'required|string',
        ]);

        if ($valid->fails()) {
            return resApi('error', $valid->errors()->first());
        } else {
            $data = SiteCon::where('domain_name', $request->domain)->first();
            if ($data) {
                $clf = new CloudflareCustomController();
                if ($data->status == 'Pending_Cloudflare') {
                    if ($data->status_cloudflare == 'pending') {
                        $rs = $clf->recordDomain($data->zone_id);
                        // $rs = $clf->createDns($data->zone_id);
                        // $id = $rs['result'][0]['id'];
                        // die();
                        if ($rs['success'] == true) {
                            // if ($rs['result'][0]['type'] == 'A') {
                            //     $rs = $clf->updateDns($data->zone_id, $rs['result'][0]['id']);
                            //     var_dump($rs);
                            //     die();
                            //     if ($rs['success'] == true) {
                            //         $data->update([
                            //             'status_cloudflare' => 'active',
                            //         ]);
                            //         return resApi('success', 'C?p nh?t record thành công');
                            //     } else {
                            //         return resApi('error', 'C?p nh?t record th?t b?i');
                            //     }
                            // } else {
                            // }
                            $rs = $clf->createDns($data->zone_id);
                            if ($rs['success'] == true) {
                                $data->update([
                                    'status_cloudflare' => 'active',
                                ]);
                                $cpanel = new CpanelCustomController();
                                $cpanel->createDomain($data->domain_name);
                                return resApi('success', 'Tạo record thành công');
                            } else {
                                if ($rs['errors'][0]['code'] == 81057) {
                                    $data->update([
                                        'status_cloudflare' => 'active',
                                    ]);
                                    $cpanel = new CpanelCustomController();
                                    $cpanel->createDomain($data->domain_name);
                                    return resApi('success', 'Tạo record thành công');
                                } else {
                                    return resApi('error', $rs['errors'][0]['message']);
                                }
                            }
                        }
                    } else {
                        return resApi('error', 'Domain đã được duyệt');
                    }
                } else {
                    $site = SiteData::where('domain', $request->domain)->first();
                    $rs = $clf->addDomain($request->domain);
                    // var_dump($rs);
                    if ($rs['success'] == true) {
                        $zone_id = $rs['result']['id'];
                        $status = $rs['result']['status'];
                        $data->update([
                            'zone_id' => $zone_id,
                            'status_cloudflare' => $status,
                        ]);
                        if ($site) {
                            $site->update([
                                'status' => 'Pending',
                            ]);
                        } else {
                            $user = User::where('username', $data->username)->first();
                            if ($user) {
                                SiteData::create([
                                    'namesite' => getDomain(),
                                    'is_admin' => json_encode($user->only(['id', 'name', 'username', 'email', 'position', 'api_token', 'domain'])),
                                    'token_web' => $user->api_token,
                                    'username_web' => 'null',
                                    'status' => 'Pending',
                                    'domain' => $request->domain,
                                ]);
                            }
                            $data->update([
                                'status' => 'Pending_Cloudflare',
                            ]);
                        }
                        return resApi('success', 'Kích hoạt thành công');
                    } else {
                        return resApi('error', $rs['result']['errors'][0]['message']);
                    }
                }
            } else {
                return resApi('error', 'Không tìm thấy website');
            }
        }
    }

    public function listAction($action, Request $request)
    {
        $start = $request->start ?? 0;
        $length = $request->length ?? 10;
        $search = $request->search['value'] ?? '';
        $order = $request->order[0] ?? [];
        $dir = $request->order[0]['dir'] ?? 'DESC';
        $justanotherpanels = new JAPController();
        $smmflares = new FlareController();
        $smmcosttimcheapests = new CostDevideTwoPointTwo();
        $smmcosttimcheaps = new CostDevideOnePointFive();
        $smmcostnormals = new CostNormal();
        $smmcostlikes = new CostTenPercent();
        $smmcosttimcheap1s = new CostEightPercent();
        $smmcosttimcheap2s = new CostTwoPerCent();
        $dnoxsmms = new DinoController();
        $n1panels = new N1panel();
        if ($action == 'list-user') {
            if (!empty($search)) {
                //search s? d?ng function match trong mysql
                $data = User::where('domain', getDomain())->where(function ($query) use ($search) {
                    $query->where('username', 'like', '%' . $search . '%')
                        // ->orWhere('email', 'like', '%' . $search . '%')
                        // ->orWhere('name', 'like', '%' . $search . '%')
                        // ->orWhere('balance', 'like', '%' . $search . '%')
                        ->orWhere('id', 'like', '%' . $search . '%');
                    // ->orWhere('total_recharge', 'like', '%' . $search . '%')
                    // ->orWhere('created_at', 'like', '%' . $search . '%');
                })->orderBy('id', $dir)->offset($start)->limit($length)->get();
                $total = $data->count();
            } else {
                $data = User::where('domain', getDomain())->orderBy('id', $dir)->offset($start)->limit($length)->get();
                $total = User::where('domain', getDomain())->count();
            }

            $data = $data->map(function ($item) {
                $item->level = level($item->level);
                return $item;
            });

            return response()->json([
                'data' => $data,
                'recordsTotal' => $total,
                'recordsFiltered' => $total
            ]);
        }

        if ($action == 'history-notification') {
            if (!empty($search)) {
                //search s? d?ng function match trong mysql
                $data = Notification::where('domain', getDomain())->where(function ($query) use ($search) {
                    $query->where('name', 'like', '%' . $search . '%')
                        ->orWhere('content', 'like', '%' . $search . '%')
                        ->orWhere('created_at', 'like', '%' . $search . '%');
                })->orderBy('id', $dir)->offset($start)->limit($length)->get();
                $total = $data->count();
            } else {
                $data = Notification::where('domain', getDomain())->orderBy('id', $dir)->offset($start)->limit($length)->get();
                $total = Notification::where('domain', getDomain())->count();
            }

            return response()->json([
                'data' => $data,
                'recordsTotal' => $total,
                'recordsFiltered' => $total
            ]);
        }

        if ($action == 'history-activity') {
            if (!empty($search)) {
                //search s? d?ng function match trong mysql
                $data = Activities::where('domain', getDomain())->where(function ($query) use ($search) {
                    $query->where('name', 'like', '%' . $search . '%')
                        ->orWhere('content', 'like', '%' . $search . '%')
                        ->orWhere('created_at', 'like', '%' . $search . '%');
                })->orderBy('id', $dir)->offset($start)->limit($length)->get();
                $total = $data->count();
            } else {
                $data = Activities::where('domain', getDomain())->orderBy('id', $dir)->offset($start)->limit($length)->get();
                $total = Activities::where('domain', getDomain())->count();
            }

            return response()->json([
                'data' => $data,
                'recordsTotal' => $total,
                'recordsFiltered' => $total
            ]);
        }

        if ($action == 'list-social') {
            if (!empty($search)) {
                //search s? d?ng function match trong mysql
                $data = ServiceSocial::where('domain', getDomain())->where(function ($query) use ($search) {
                    $query->where('name', 'like', '%' . $search . '%')
                        ->orWhere('slug', 'like', '%' . $search . '%')
                        ->orWhere('created_at', 'like', '%' . $search . '%');
                })->orderBy('id', $dir)->offset($start)->limit($length)->get();
                $total = $data->count();
            } else {
                $data = ServiceSocial::where('domain', getDomain())->orderBy('id', $dir)->offset($start)->limit($length)->get();
                $total = ServiceSocial::where('domain', getDomain())->count();
            }

            return response()->json([
                'data' => $data,
                'recordsTotal' => $total,
                'recordsFiltered' => $total
            ]);
        }

        if ($action == 'list-service') {
            if (!empty($search)) {
                //search s? d?ng function match trong mysql
                $data = Service::where('domain', getDomain())->where(function ($query) use ($search) {
                    $query->where('name', 'like', '%' . $search . '%')
                        ->orWhere('slug', 'like', '%' . $search . '%')
                        ->orWhere('created_at', 'like', '%' . $search . '%');
                })->orderBy('id', $dir)->offset($start)->limit($length)->get();
                $total = $data->count();
            } else {
                $data = Service::where('domain', getDomain())->orderBy('id', $dir)->offset($start)->limit($length)->get();
                $total = Service::where('domain', getDomain())->count();
            }

            $data = $data->map(function ($item) {
                $social = ServiceSocial::where('domain', getDomain())->where('slug', $item->service_social)->first();
                $item->social = $social->name ?? '';
                return $item;
            });

            return response()->json([
                'data' => $data,
                'recordsTotal' => $total,
                'recordsFiltered' => $total
            ]);
        }

        if ($action == 'list-server') {
            $total = 0;
            if (!empty($search)) {
                $data = ServerService::where('domain', getDomain())->where(function ($query) use ($search) {
                    $query->where('name', 'like', '%' . $search . '%')
                        ->orWhere('server', 'like', '%' . $search . '%')
                        ->orWhere('price', 'like', '%' . $search . '%')
                        ->orWhere('price_collaborator', 'like', '%' . $search . '%')
                        ->orWhere('price_agency', 'like', '%' . $search . '%')
                        ->orWhere('price_distributor', 'like', '%' . $search . '%')
                        ->orWhere('min', 'like', '%' . $search . '%')
                        ->orWhere('max', 'like', '%' . $search . '%')
                        ->orWhere('title', 'like', '%' . $search . '%')
                        ->orWhere('description', 'like', '%' . $search . '%')
                        ->orWhere('created_at', 'like', '%' . $search . '%');
                })->orderBy('id', $dir)->offset($start)->limit($length)->get();
                $total = $data->count();
            } else {
                $data = ServerService::where('domain', getDomain())->orderBy('id', $dir)->offset($start)->limit($length)->get();
                $total = ServerService::where('domain', getDomain())->count();
            }

            $data = $data->map(function ($item) {
                $item->service = Service::where('domain', env('PARENT_SITE'))->where('id', $item->service_id)->first()->name ?? '';
                if (getDomain() != env('PARENT_SITE')) {
                    unset($item->actual_path);
                    // unset($item->actual_price);
                    unset($item->actual_server);
                    unset($item->actual_service);
                }
                return $item;
            });

            return response()->json([
                'data' => $data,
                'recordsTotal' => $total,
                'recordsFiltered' => $total
            ]);
        }

        if ($action == 'history-user') {
            if (!empty($search)) {
                $data = DataHistory::where('domain', getDomain())->where(function ($query) use ($search) {
                    $query->where('username', 'like', "%$search%")
                        ->orWhere('action', 'like', "%$search%")
                        ->orWhere('data', 'like', "%$search%")
                        ->orWhere('old_data', 'like', "%$search%")
                        ->orWhere('new_data', 'like', "%$search%")
                        ->orWhere('description', 'like', "%$search%");
                })->orderBy('id', $dir)->offset($start)->limit($length)->get();
                $total = $data->count();
            } else {
                $data = DataHistory::where('domain', getDomain())->orderBy('id', $dir)->offset($start)->limit($length)->get();
                $total = DataHistory::where('domain', getDomain())->count();
            }
            $data = $data->map(function ($item) {
                return $item;
            });

            return response()->json([
                'data' => $data,
                'recordsTotal' => $total,
                'recordsFiltered' => $total
            ]);
        }

        if ($action == 'list-order') {
            if (getDomain() == env('PARENT_SITE')) {
                if (!empty($search)) {
                    $data = Orders::where(function ($query) use ($search) {
                        $query->where('username', 'like', '%' . $search . '%')
                            // ->orWhere('server_service', 'like', '%' . $search . '%')
                            // ->orWhere('price', 'like', '%' . $search . '%')
                            // ->orWhere('quantity', 'like', '%' . $search . '%')
                            // ->orWhere('total_payment', 'like', '%' . $search . '%')
                            // ->orWhere('order_link', 'like', '%' . $search . '%')
                            ->orWhere('id', 'like', '%' . $search . '%');
                        // ->orWhere('start', 'like', '%' . $search . '%')
                        // ->orWhere('buff', 'like', '%' . $search . '%')
                        // ->orWhere('status', 'like', '%' . $search . '%')
                        // ->orWhere('created_at', 'like', '%' . $search . '%');
                    })->orderBy('id', $dir)->offset($start)->limit($length)->get();
                    $total = $data->count();
                } else {
                    $justanotherpanel = Orders::orderBy('id', $dir)->where("actual_service", 'justanotherpanel')->where("status", '!=', 'Completed')->offset($start)->limit($length)->get();
                    $smmcostnormal = Orders::orderBy('id', $dir)->where("actual_service", 'Smm(Quantity-0%)')->where("status", '!=', 'Completed')->offset($start)->limit($length)->get();
                    $smmcosttimcheapest = Orders::orderBy('id', $dir)->where("actual_service", 'Smm(Quantity/2.2)')->where("status", '!=', 'Completed')->offset($start)->limit($length)->get();
                    $smmcosttimcheap = Orders::orderBy('id', $dir)->where("actual_service", 'Smm(Quantity/1.5)')->where("status", '!=', 'Completed')->offset($start)->limit($length)->get();
                    $smmcosttimcheap1 = Orders::orderBy('id', $dir)->where("actual_service", 'costtimcheap1')->where("status", '!=', 'Completed')->offset($start)->limit($length)->get();
                    $smmcosttimcheap2 = Orders::orderBy('id', $dir)->where("actual_service", 'costtimcheap2')->where("status", '!=', 'Completed')->offset($start)->limit($length)->get();
                    $dnoxsmm = Orders::orderBy('id', $dir)->where("actual_service", 'dnoxsmm')->where("status", '!=', 'Completed')->offset($start)->limit($length)->get();
                    $trumvip = Orders::orderBy('id', $dir)->where("actual_service", 'trumvip')->where("status", '!=', 'Completed')->offset($start)->limit($length)->get();
                    $smmcostlike = Orders::orderBy('id', $dir)->where("actual_service", 'costlike')->where("status", '!=', 'Completed')->offset($start)->limit($length)->get();
                    $n1panel = Orders::orderBy('id', $dir)->where("actual_service", 'costlike')->where("status", '!=', 'Completed')->offset($start)->limit($length)->get();
                    for ($i = 0; $i < count($justanotherpanel); $i++) {
                        if ($justanotherpanel[$i]->order_code && $justanotherpanel[$i]->status != "Completed") {
                            
                            $dataStatus = $justanotherpanels->status($justanotherpanel[$i]->order_code);
                    
                            if ($dataStatus['status']) {
                                // Kiểm tra từng key trước khi cập nhật để tránh lỗi
                                $justanotherpanel[$i]->update([
                                    'start' => $dataStatus['start_count'] ?? 0,  // Nếu không có thì mặc định là 0
                                    'buff' => isset($dataStatus['remains']) ? intval($justanotherpanel[$i]->quantity) - $dataStatus['remains'] : 0,
                                    'status' => $dataStatus['status'] ?? 'Unknown',
                                ]);
                            } else {
                                Log::warning("Lỗi lấy trạng thái đơn hàng: " . ($dataStatus['message'] ?? 'Không rõ lỗi'));
                            }
                        }
                    }
                    for ($i = 0; $i < count($smmcostnormal); $i++) {
                        if ($smmcostnormal[$i]->order_code && $smmcostnormal[$i]->status != "Completed") {
                            
                            $dataStatus = $smmcostnormals->status($smmcostnormal[$i]->order_code);
                    
                            if ($dataStatus['status']) {
                                // Kiểm tra từng key trước khi cập nhật để tránh lỗi
                                $smmcostnormal[$i]->update([
                                    'start' => $dataStatus['start_count'] ?? 0,  // Nếu không có thì mặc định là 0
                                    'buff' => isset($dataStatus['remains']) ? intval($smmcostnormal[$i]->quantity) - $dataStatus['remains'] : 0,
                                    'status' => $dataStatus['order_status'] ?? 'Unknown',
                                ]);
                            } else {
                                Log::warning("Lỗi lấy trạng thái đơn hàng: " . ($dataStatus['message'] ?? 'Không rõ lỗi'));
                            }
                        }
                    }
                    for ($i = 0; $i < count($smmcosttimcheapest); $i++) {
                        if ($smmcosttimcheapest[$i]->order_code && $smmcosttimcheapest[$i]->status != "Completed") {
                            
                            $dataStatus = $smmcosttimcheapests->status($smmcosttimcheapest[$i]->order_code);
                    
                            if ($dataStatus['status']) {
                                // Kiểm tra từng key trước khi cập nhật để tránh lỗi
                                $smmcosttimcheapest[$i]->update([
                                    'start' => $dataStatus['start_count'] ?? 0,  // Nếu không có thì mặc định là 0
                                    'buff' => isset($dataStatus['remains']) ? intval($smmcosttimcheapest[$i]->quantity) - $dataStatus['remains'] : 0,
                                    'status' => $dataStatus['status'] ?? 'Unknown',
                                ]);
                            } else {
                                Log::warning("Lỗi lấy trạng thái đơn hàng: " . ($dataStatus['message'] ?? 'Không rõ lỗi'));
                            }
                        }
                    }
                    for ($i = 0; $i < count($smmcosttimcheap); $i++) {
                        if ($smmcosttimcheap[$i]->order_code && $smmcosttimcheap[$i]->status != "Completed") {
                            
                            $dataStatus = $smmcosttimcheaps->status($smmcosttimcheap[$i]->order_code);
                    
                            if ($dataStatus['status']) {
                                // Kiểm tra từng key trước khi cập nhật để tránh lỗi
                                $smmcosttimcheap[$i]->update([
                                    'start' => $dataStatus['start_count'] ?? 0,  // Nếu không có thì mặc định là 0
                                    'buff' => isset($dataStatus['remains']) ? intval($smmcosttimcheap[$i]->quantity) - $dataStatus['remains'] : 0,
                                    'status' => $dataStatus['status'] ?? 'Unknown',
                                ]);
                            } else {
                                Log::warning("Lỗi lấy trạng thái đơn hàng: " . ($dataStatus['message'] ?? 'Không rõ lỗi'));
                            }
                        }
                    }
                    for ($i = 0; $i < count($smmcosttimcheap1); $i++) {
                        if ($smmcosttimcheap1[$i]->order_code && $smmcosttimcheap1[$i]->status != "Completed") {
                            
                            $dataStatus = $smmcosttimcheap1s->status($smmcosttimcheap1[$i]->order_code);
                    
                            if ($dataStatus['status']) {
                                // Kiểm tra từng key trước khi cập nhật để tránh lỗi
                                $smmcosttimcheap1[$i]->update([
                                    'start' => $dataStatus['start_count'] ?? 0,  // Nếu không có thì mặc định là 0
                                    'buff' => isset($dataStatus['remains']) ? intval($smmcosttimcheap1[$i]->quantity) - $dataStatus['remains'] : 0,
                                    'status' => $dataStatus['status'] ?? 'Unknown',
                                ]);
                            } else {
                                Log::warning("Lỗi lấy trạng thái đơn hàng: " . ($dataStatus['message'] ?? 'Không rõ lỗi'));
                            }
                        }
                    }
                    for ($i = 0; $i < count($smmcosttimcheap2); $i++) {
                        if ($smmcosttimcheap2[$i]->order_code && $smmcosttimcheap2[$i]->status != "Completed") {
                            
                            $dataStatus = $smmcosttimcheap2s->status($smmcosttimcheap2[$i]->order_code);
                    
                            if ($dataStatus['status']) {
                                // Kiểm tra từng key trước khi cập nhật để tránh lỗi
                                $smmcosttimcheap2[$i]->update([
                                    'start' => $dataStatus['start_count'] ?? 0,  // Nếu không có thì mặc định là 0
                                    'buff' => isset($dataStatus['remains']) ? intval($smmcosttimcheap2[$i]->quantity) - $dataStatus['remains'] : 0,
                                    'status' => $dataStatus['status'] ?? 'Unknown',
                                ]);
                            } else {
                                Log::warning("Lỗi lấy trạng thái đơn hàng: " . ($dataStatus['message'] ?? 'Không rõ lỗi'));
                            }
                        }
                    }
                    for ($i = 0; $i < count($dnoxsmm); $i++) {
                        if ($dnoxsmm[$i]->order_code && $dnoxsmm[$i]->status != "Completed") {
                            
                            $dataStatus = $dnoxsmms->status($dnoxsmm[$i]->order_code);
                    
                            if ($dataStatus['status']) {
                                // Kiểm tra từng key trước khi cập nhật để tránh lỗi
                                $dnoxsmm[$i]->update([
                                    'start' => $dataStatus['start_count'] ?? 0,  // Nếu không có thì mặc định là 0
                                    'buff' => isset($dataStatus['remains']) ? intval($dnoxsmm[$i]->quantity) - $dataStatus['remains'] : 0,
                                    'status' => $dataStatus['status'] ?? 'Unknown',
                                ]);
                            } else {
                                Log::warning("Lỗi lấy trạng thái đơn hàng: " . ($dataStatus['message'] ?? 'Không rõ lỗi'));
                            }
                        }
                    }
                    for ($i = 0; $i < count($trumvip); $i++) {
                        if ($trumvip[$i]->order_code && $trumvip[$i]->status != "Completed") {
                            
                            $dataStatus = $trumvips->status($trumvip[$i]->order_code);
                    
                            if ($dataStatus['status']) {
                                // Kiểm tra từng key trước khi cập nhật để tránh lỗi
                                $trumvip[$i]->update([
                                    'start' => $dataStatus['start_count'] ?? 0,  // Nếu không có thì mặc định là 0
                                    'buff' => isset($dataStatus['remains']) ? intval($trumvip[$i]->quantity) - $dataStatus['remains'] : 0,
                                    'status' => $dataStatus['status'] ?? 'Unknown',
                                ]);
                            } else {
                                Log::warning("Lỗi lấy trạng thái đơn hàng: " . ($dataStatus['message'] ?? 'Không rõ lỗi'));
                            }
                        }
                    }
                    for ($i = 0; $i < count($smmcostlike); $i++) {
                        if ($smmcostlike[$i]->order_code && $smmcostlike[$i]->status != "Completed") {
                            
                            $dataStatus = $smmcostlikes->status($smmcostlike[$i]->order_code);
                    
                            if ($dataStatus['status']) {
                                // Kiểm tra từng key trước khi cập nhật để tránh lỗi
                                $smmcostlike[$i]->update([
                                    'start' => $dataStatus['start_count'] ?? 0,  // Nếu không có thì mặc định là 0
                                    'buff' => isset($dataStatus['remains']) ? intval($smmcostlike[$i]->quantity) - $dataStatus['remains'] : 0,
                                    'status' => $dataStatus['status'] ?? 'Unknown',
                                ]);
                            } else {
                                Log::warning("Lỗi lấy trạng thái đơn hàng: " . ($dataStatus['message'] ?? 'Không rõ lỗi'));
                            }
                        }
                    }
                    $data = Orders::orderBy('id', $dir)->offset($start)->limit($length)->get();

                    $total = Orders::count();
                }
            } else {
                if (!empty($search)) {
                    $data = Orders::where('domain', getDomain())->where(function ($query) use ($search) {
                        $query->where('username', 'like', '%' . $search . '%')
                            ->orWhere('server_service', 'like', '%' . $search . '%')
                            ->orWhere('price', 'like', '%' . $search . '%')
                            ->orWhere('quantity', 'like', '%' . $search . '%')
                            ->orWhere('total_payment', 'like', '%' . $search . '%')
                            ->orWhere('order_link', 'like', '%' . $search . '%')
                            ->orWhere('start', 'like', '%' . $search . '%')
                            ->orWhere('buff', 'like', '%' . $search . '%')
                            ->orWhere('status', 'like', '%' . $search . '%')
                            ->orWhere('created_at', 'like', '%' . $search . '%');
                    })->orderBy('id', $dir)->offset($start)->limit($length)->get();
                    $total = $data->count();
                } else {
                    $data = Orders::where('domain', getDomain())->orderBy('id', $dir)->offset($start)->limit($length)->get();
                    $total = Orders::where('domain', getDomain())->count();
                }
            }

            $data = $data->map(function ($item) {
                $item->status_order = statusOrder($item->status);
                if (getDomain() != env('PARENT_SITE')) {
                    $item->actual_service= "";
                    $item->actual_path = "";
                    $item->actual_server = "";
                }
                return $item;
            });

            return response()->json([
                'data' => $data,
                'recordsTotal' => $total,
                'recordsFiltered' => $total
            ]);
        }
        if ($action == 'order-tay') {
            if (getDomain() == env('PARENT_SITE')) {
                if (!empty($search)) {
                    $data = Orders::where('status', 'Pending')->where(function ($query) use ($search) {
                        $query->where('username', 'like', '%' . $search . '%')
                            ->orWhere('server_service', 'like', '%' . $search . '%')
                            ->orWhere('price', 'like', '%' . $search . '%')
                            ->orWhere('quantity', 'like', '%' . $search . '%')
                            ->orWhere('total_payment', 'like', '%' . $search . '%')
                            ->orWhere('id', 'like', '%' . $search . '%')
                            ->orWhere('order_link', 'like', '%' . $search . '%')
                            ->orWhere('start', 'like', '%' . $search . '%')
                            ->orWhere('buff', 'like', '%' . $search . '%')
                            ->orWhere('status', 'like', '%' . $search . '%')
                            ->orWhere('created_at', 'like', '%' . $search . '%');
                    })->orderBy('id', $dir)->offset($start)->limit($length)->get();
                    $total = $data->count();
                } else {
                    $data = Orders::where('status', 'Pending')->orderBy('id', $dir)->offset($start)->limit($length)->get();
                    $total = Orders::where('status', 'Pending')->count();
                }
            } else {
                if (!empty($search)) {
                    $data = Orders::where('domain', getDomain())->where('status', 'Pending')->where(function ($query) use ($search) {
                        $query->where('username', 'like', '%' . $search . '%')
                            ->orWhere('server_service', 'like', '%' . $search . '%')
                            ->orWhere('price', 'like', '%' . $search . '%')
                            ->orWhere('quantity', 'like', '%' . $search . '%')
                            ->orWhere('total_payment', 'like', '%' . $search . '%')
                            ->orWhere('order_link', 'like', '%' . $search . '%')
                            ->orWhere('start', 'like', '%' . $search . '%')
                            ->orWhere('buff', 'like', '%' . $search . '%')
                            ->orWhere('status', 'like', '%' . $search . '%')
                            ->orWhere('created_at', 'like', '%' . $search . '%');
                    })->orderBy('id', $dir)->offset($start)->limit($length)->get();
                    $total = $data->count();
                } else {
                    $data = Orders::where('domain', getDomain())->where('status', 'Pending')->orderBy('id', $dir)->offset($start)->limit($length)->get();
                    $total = Orders::where('domain', getDomain())->where('status', 'Pending')->count();
                }
            }

            $data = $data->map(function ($item) {
                $item->status_order = statusOrder($item->status);
                if (getDomain() != env('PARENT_SITE')) {
                    unset($item->actual_service);
                    unset($item->actual_path);
                    unset($item->actual_server);
                }
                return $item;
            });

            return response()->json([
                'data' => $data,
                'recordsTotal' => $total,
                'recordsFiltered' => $total
            ]);
        }


        if ($action == 'list-recharge') {
            if (!empty($search)) {
                $data = AccountRecharge::where('domain', getDomain())->where(function ($query) use ($search) {
                    $query->where('type', 'like', '%' . $search . '%')
                        ->orWhere('account_name', 'like', '%' . $search . '%')
                        ->orWhere('account', 'like', '%' . $search . '%')
                        ->orWhere('account_number', 'like', '%' . $search . '%')
                        ->orWhere('created_at', 'like', '%' . $search . '%');
                })->orderBy('id', $dir)->offset($start)->limit($length)->get();
                $total = $data->count();
            } else {
                $data = AccountRecharge::where('domain', getDomain())->orderBy('id', $dir)->offset($start)->limit($length)->get();
                $total = AccountRecharge::where('domain', getDomain())->count();
            }

            $data = $data->map(function ($item) {
                return $item;
            });

            return response()->json([
                'data' => $data,
                'recordsTotal' => $total,
                'recordsFiltered' => $total
            ]);
        }

        if ($action == 'history-recharge') {
            if (!empty($search)) {
                $data = HistoryRecharge::where('domain', getDomain())->where(function ($query) use ($search) {
                    $query->where('username', 'like', '%' . $search . '%')
                        ->orWhere('name_bank', 'like', '%' . $search . '%')
                        ->orWhere('type_bank', 'like', '%' . $search . '%')
                        ->orWhere('tranid', 'like', '%' . $search . '%')
                        ->orWhere('amount', 'like', '%' . $search . '%')
                        ->orWhere('promotion', 'like', '%' . $search . '%')
                        ->orWhere('real_amount', 'like', '%' . $search . '%')
                        ->orWhere('created_at', 'like', '%' . $search . '%');
                })->orderBy('id', $dir)->offset($start)->limit($length)->get();
                $total = $data->count();
            } else {
                $data = HistoryRecharge::where('domain', getDomain())->orderBy('id', $dir)->offset($start)->limit($length)->get();
                $total = HistoryRecharge::where('domain', getDomain())->count();
            }

            $data = $data->map(function ($item) {
                if ($item->type_bank == 'mbbank') {
                    $item->tranid = base64_decode($item->tranid);
                }
                return $item;
            });

            return response()->json([
                'data' => $data,
                'recordsTotal' => $total,
                'recordsFiltered' => $total
            ]);
        }

        if ($action == 'history-card') {
            if (!empty($search)) {
                $data = HistoryCard::where('domain', getDomain())->where(function ($query) use ($search) {
                    $query->where('username', 'like', '%' . $search . '%')
                        ->orWhere('card_type', 'like', '%' . $search . '%')
                        ->orWhere('card_amount', 'like', '%' . $search . '%')
                        ->orWhere('card_serial', 'like', '%' . $search . '%')
                        ->orWhere('card_code', 'like', '%' . $search . '%')
                        ->orWhere('status', 'like', '%' . $search . '%')
                        ->orWhere('created_at', 'like', '%' . $search . '%');
                })->orderBy('id', $dir)->offset($start)->limit($length)->get();
                $total = $data->count();
            } else {
                $data = HistoryCard::where('domain', getDomain())->orderBy('id', $dir)->offset($start)->limit($length)->get();
                $total = HistoryCard::where('domain', getDomain())->count();
            }

            $data = $data->map(function ($item) {
                $item->status = statusCard($item->status);
                return $item;
            });

            return response()->json([
                'data' => $data,
                'recordsTotal' => $total,
                'recordsFiltered' => $total
            ]);
        }

        if ($action == 'list-site') {
            if (!empty($search)) {
                $data = SiteCon::where('domain', getDomain())->where(function ($query) use ($search) {
                    $query->where('username', 'like', '%' . $search . '%')
                        ->orWhere('domain_name', 'like', '%' . $search . '%')
                        ->orWhere('created_at', 'like', '%' . $search . '%');
                })->orderBy('id', $dir)->offset($start)->limit($length)->get();
                $total = $data->count();
            } else {
                $data = SiteCon::where('domain', getDomain())->orderBy('id', $dir)->offset($start)->limit($length)->get();
                $total = SiteCon::where('domain', getDomain())->count();
            }

            $data = $data->map(function ($item) {
                $item->status = $item->status;
                return $item;
            });

            return response()->json([
                'data' => $data,
                'recordsTotal' => $total,
                'recordsFiltered' => $total
            ]);
        }
    }

    public function deleteData($type, Request $request)
    {
        if ($type == 'delete-site') {
            $valid = Validator::make($request->all(), [
                'domain' => 'required'
            ]);

            if ($valid->fails()) {
                return response()->json([
                    'status' => 'error',
                    'message' => $valid->errors()->first()
                ]);
            } else {
                $check = SiteCon::where('domain_name', $request->domain)->first();
                if ($check) {
                    $site = SiteData::where('domain', '!=', env('PARENT_SITE'))->where('domain', $request->domain)->first();
                    if ($site) {
                        $site->delete();
                    }
                    if ($check->status_cloudflare == 'active') {
                        $clf = new CloudflareCustomController();
                        $clf->deleteDomain($request->domain);
                        $cpanel = new CpanelCustomController();
                        $cpanel->deleteDomain($request->domain);
                    }

                    $check->delete();
                    return response()->json([
                        'status' => 'success',
                        'message' => 'Xóa thành công'
                    ]);
                } else {
                    return response()->json([
                        'status' => 'error',
                        'message' => 'Không tìm thấy tên miền này'
                    ]);
                }
            }
        }
    }

    public function serverDeleteAll()
    {
        $server = ServerService::where('domain', getDomain())->get();
        foreach ($server as $item) {
            $item->delete();
        }
        return redirect()->back()->with('success', 'Xóa thành công');
    }
}
