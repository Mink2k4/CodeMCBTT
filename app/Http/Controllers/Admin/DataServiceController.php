<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Http\Controllers\Custom\TelegramCustomController;
use App\Models\Orders;
use App\Models\ServerService;
use App\Models\Service;
use App\Models\ServiceSocial;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\File;

class DataServiceController extends Controller
{
    public function __construct()
    {
        $this->middleware('xss')->except(['serverNew', 'serverEdit']);
    }

    public function serviceNew(Request $request)
    {
        $valid = Validator::make($request->all(), [
            'social' => 'required|string',
            'service' => 'required|string',
            'path_service' => 'required|string',
            'status' => 'required|string|in:show,hide',
            'category' => 'required|string',
        ]);

        if ($valid->fails()) {
            return redirect()->back()->with('error', $valid->errors()->first())->withInput();
        } else {
            $social = ServiceSocial::where('domain', getDomain())->where('slug', $request->social)->first();
            if ($social) {
                $slug_service = Str::slug($request->path_service, '-');
                $service = Service::where('slug', $slug_service)->where('service_social', $request->social)->where('domain', getDomain())->first();
                if ($service) {
                    return redirect()->back()->with('error', 'Dịch vụ đã tồn tại')->withInput();
                } else {

                    $action_type = '';
                    switch ($request->category) {
                        case 'default':
                            $file_view = storage_path('/app/public/code/default.lbd');
                            $action_type = 'default';
                            break;
                        case 'reaction':
                            $file_view = storage_path('/app/public/code/reaction.lbd');
                            $action_type = 'reaction';
                            break;
                        case 'reaction-speed':
                            $file_view = storage_path('/app/public/code/reaction-speed.lbd');
                            $action_type = 'reaction-speed';
                            break;
                        case 'comment':
                            $file_view = storage_path('/app/public/code/comment2.lbd');
                            $action_type = 'comment';
                            break;
                        case 'comment-quantity':
                            $file_view = storage_path('/app/public/code/comment.lbd');
                            $action_type = 'comment-quantity';
                            break;
                        case 'minutes':
                            $file_view = storage_path('/app/public/code/minutes.lbd');
                            $action_type = 'minutes';
                            break;
                        case 'time':
                            $file_view = storage_path('/app/public/code/time.lbd');
                            $action_type = 'time';
                            break;
                        default:
                            $file_view = storage_path('/app/public/code/default.lbd');
                            $action_type = 'default';
                            break;
                    }
                    $social_folder = $social->folder;
                    $file_view = File::get($file_view);
                    $file_service = $slug_service . Str::random(4);
                    if (!File::exists(resource_path('views/service/' . $social_folder))) {
                        File::makeDirectory(resource_path('views/service/' . $social_folder), 0777, true, true);
                    }

                    if (!File::exists(resource_path('views/service/' . $social_folder . '/' . $file_service . '.blade.php'))) {
                        File::put(resource_path('views/service/' . $social_folder . '/' . $file_service . '.blade.php'), $file_view);
                    }
                    Service::create([
                        'name' => $request->service,
                        'slug' => $slug_service,
                        'service_social' => $social->slug,
                        'status' => $request->status,
                        'file' => $file_service,
                        'category' => $request->category,
                        'domain' => getDomain(),
                    ]);

                    return redirect()->back()->with('success', 'Thêm thành công');
                }
            } else {
                return redirect()->back()->with('error', 'Dịch vụ MXH không tồn tại')->withInput();
            }
        }
    }

    public function serviceSocialEdit($id, Request $request)
    {
        $valid = Validator::make($request->all(), [
            'social_service' => 'required|string',
            'social_path' => 'required|string',
            'social_image' => 'required|string',
            'social_status' => 'required|string|in:show,hide',
        ]);

        if ($valid->fails()) {
            return redirect()->back()->with('error', $valid->errors()->first())->withInput();
        } else {
            $social_service = $request->social_service;
            $social_path = $request->social_path;
            $social_image = $request->social_image;
            $social_status = $request->social_status;

            $social_path = Str::slug($social_path, '-');

            $social = ServiceSocial::where('domain', getDomain())->where('slug', $social_path)->where('id', '!=', $id)->first();
            if ($social) {
                return redirect()->back()->with('error', 'Đường dẫn đã tồn tại')->withInput();
            } else {
                $social_folder = Str::slug($social_service, '-') . '-' . Str::random(10);
                $social_old = ServiceSocial::where('domain', getDomain())->where('id', $id)->first();
                if ($social_old) {
                    File::move(resource_path('views/service/' . $social_old->folder), resource_path('views/service/' . $social_folder));
                }
                ServiceSocial::where('domain', getDomain())->where('id', $id)->update([
                    'name' => $social_service,
                    'slug' => $social_path,
                    'image' => $social_image,
                    'folder' => $social_folder,
                    'status' => $social_status,
                ]);

                return redirect()->back()->with('success', 'Cập nhật thành công');
            }
        }
    }

    public function serviceNewSocial(Request $request)
    {
        $valid = Validator::make($request->all(), [
            'social_service' => 'required|string',
            'social_path' => 'required|string',
            'social_image' => 'required|string',
            'social_status' => 'required|string|in:show,hide',
        ]);

        if ($valid->fails()) {
            return redirect()->back()->with('error', $valid->errors()->first())->withInput();
        } else {
            $social_service = $request->social_service;
            $social_path = $request->social_path;
            $social_image = $request->social_image;
            $social_status = $request->social_status;

            $social_path = Str::slug($social_path, '-');

            $social = ServiceSocial::where('domain', getDomain())->where('slug', $social_path)->first();
            if ($social) {
                return redirect()->back()->with('error', 'Đường dẫn đã tồn tại')->withInput();
            } else {
                /* Làm việc với FIle */
                $social_folder = Str::slug($social_service, '-') . '-' . Str::random(10);
                // Storage::disk('public')->makeDirectory('service/' . $social_folder);

                // tạo folder trong resources/views/service
                File::makeDirectory(resource_path('views/service/' . $social_folder), 0777, true, true);

                ServiceSocial::create([
                    'name' => $social_service,
                    'slug' => $social_path,
                    'image' => $social_image,
                    'folder' => $social_folder,
                    'status' => $social_status,
                    'domain' => getDomain(),
                ]);

                return redirect()->back()->with('success', 'Thêm thành công');
            }
        }
    }

    public function serviceSocialDelete($id)
    {
        $social = ServiceSocial::where('domain', getDomain())->where('id', $id)->first();
        if ($social) {
            $service = Service::where('domain', getDomain())->where('service_social', $social->slug)->get();
            foreach ($service as $item) {
                $file = resource_path('views/service/' . $social->folder . '/' . $item->file . '.blade.php');
                File::delete($file);
                $item->delete();
            }
            $server = ServerService::where('domain', getDomain())->where('social_id', $social->id)->get();
            foreach ($server as $item) {
                $item->delete();
            }
            $social->delete();
            return resApi('success', 'Xóa thành công');
        } else {
            return resApi('error', 'Không tìm thấy dịch vụ');
        }
    }

    public function serviceEdit($id, Request $request)
    {
        $valid = Validator::make($request->all(), [
            'name' => 'required|string',
            'status' => 'required|string|in:show,hide',
        ]);

        if ($valid->fails()) {
            return redirect()->back()->with('error', $valid->errors()->first())->withInput();
        } else {
            $name = $request->name;
            $status = $request->status;

            $service = Service::where('domain', getDomain())->where('id', $id)->first();
            if ($service) {
                Service::where('domain', getDomain())->where('id', $id)->update([
                    'name' => $name,
                    'status' => $status,
                ]);
                return redirect()->back()->with('success', 'Cập nhật thành công');
            } else {
                return redirect()->back()->with('error', 'Không tìm thấy dịch vụ');
            }
        }
    }

    public function serviceDelete($id)
    {
        $service = Service::where('domain', getDomain())->where('id', $id)->first();
        if ($service) {
            // xoá file
            $social = ServiceSocial::where('domain', getDomain())->where('slug', $service->service_social)->first();
            if ($social) {
                $server = ServerService::where('domain', getDomain())->where('service_id', $service->id)->get();
                foreach ($server as $item) {
                    $item->delete();
                }
                File::delete(resource_path('views/service/' . $social->folder . '/' . $service->file . '.blade.php'));
            }
            $service->delete();
            return redirect()->back()->with('success', 'Xóa thành công');
        } else {
            return redirect()->back()->with('error', 'Không tìm thấy dịch vụ');
        }
    }

    public function serverNew(Request $request)
    {
        $valid = Validator::make($request->all(), [
            'social' => 'required|integer',
            'service' => 'required|integer',
            'server_service' => 'required|integer',
            'price' => 'required|numeric',
            'price_collaborator' => 'required|numeric',
            'price_agency' => 'required|numeric',
            'price_distributor' => 'required|numeric',
            'min' => 'required|integer',
            'max' => 'required|integer',
            'title' => 'required|string',
            'description' => 'required|string',
            'actual_service' => 'required|string',
            'actual_server' => 'required|string',
            'actual_path' => 'required|string',
            'action' => 'required|string',
            'order_type' => 'required|string',
            'warranty' => 'required|string|in:yes,no',
        ]);

        if ($valid->fails()) {
            return resApi('error', $valid->errors()->first());
        } else {
            //kiểm tra social có tồn tại không
            $social = ServiceSocial::where('domain', getDomain())->where('id', $request->social)->first();
            if ($social) {
                //kiêmtr tra service có tồn tại không
                $service = Service::where('domain', getDomain())->where('id', $request->service)->first();
                if ($service) {
                    // kiểm tra server
                    $server = ServerService::where('domain', getDomain())->where('social_id', $social->id)->where('service_id', $service->id)->where('server', $request->server_service)->first();
                    if ($server) {
                        return resApi('error', 'Máy chủ đã tồn tại');
                    } else {
                        // tạo server
                        ServerService::create([
                            'name' => $service->name,
                            'social_id' => $social->id,
                            'service_id' => $service->id,
                            'server' => $request->server_service,
                            'price' => $request->price,
                            'price_collaborator' => $request->price_collaborator,
                            'price_agency' => $request->price_agency,
                            'price_distributor' => $request->price_distributor,
                            'min' => $request->min,
                            'max' => $request->max,
                            'title' => $request->title,
                            'description' => $request->description,
                            'status' => 'Active',
                            'actual_service' => $request->actual_service,
                            'actual_server' => $request->actual_server,
                            'actual_path' => $request->actual_path,
                            'action' => $request->action,
                            'order_type' => $request->order_type,
                            'warranty' => $request->warranty,
                            'service_list' => $request->service_list,
                            'domain' => getDomain(),
                        ]);
                        return resApi('success', 'Thêm thành công');
                    }
                } else {
                    return resApi('error', 'Không tìm thấy dịch vụ');
                }
            } else {
                return resApi('error', 'Không tìm thấy dịch vụ MXH');
            }
        }
    }

    public function serverEdit($id, Request $request)
    {
        if (getDomain() == env('PARENT_SITE')) {
            $valid = Validator::make($request->all(), [
                'server_service' => 'required|integer',
                'price' => 'required|numeric',
                'price_collaborator' => 'required|numeric',
                'price_agency' => 'required|numeric',
                'price_distributor' => 'required|numeric',
                'min' => 'required|integer',
                'max' => 'required|integer',
                'title' => 'required|string',
                'description' => 'required|string',
                'actual_service' => 'required|string',
                'actual_server' => 'required|string',
                'actual_path' => 'required|string',
                'status' => 'required|string|in:Active,InActive',
                'action' => 'required|string',
                'order_type' => 'required|string',
                'warranty' => 'required|string|in:yes,no',
            ]);
        } else {
            $valid = Validator::make($request->all(), [
                'server_service' => 'required|integer',
                'price' => 'required|numeric',
                'price_collaborator' => 'required|numeric',
                'price_agency' => 'required|numeric',
                'price_distributor' => 'required|numeric',
                'min' => 'required|integer',
                'max' => 'required|integer',
                'title' => 'required|string',
                'description' => 'required|string',
                'status' => 'required|string|in:Active,InActive'
            ]);
        }

        if ($valid->fails()) {
            return resApi('error', $valid->errors()->first());
        } else {
            if (getDomain() == env('PARENT_SITE')) {
                ServerService::where('domain', getDomain())->where('id', $id)->update([
                    'server' => $request->server_service,
                    'price' => $request->price,
                    'price_collaborator' => $request->price_collaborator,
                    'price_agency' => $request->price_agency,
                    'price_distributor' => $request->price_distributor,
                    'min' => $request->min,
                    'max' => $request->max,
                    'title' => $request->title,
                    'description' => $request->description,
                    'status' => $request->status,
                    'actual_service' => $request->actual_service,
                    'actual_server' => $request->actual_server,
                    'actual_path' => $request->actual_path,
                    'action' => $request->action,
                    'order_type' => $request->order_type,
                    'warranty' => $request->warranty,
                    'service_list' =>$request->service_list,
                    'domain' => getDomain(),
                ]);
                // update
               ServerService::where('id_lienket', $id)->update([
                    'service_list' =>$request->service_list,
                    'server' => $request->server_service,
                    'price' => $request->price,
                    'price_collaborator' => $request->price_collaborator,
                    'price_agency' => $request->price_agency,
                    'price_distributor' => $request->price_distributor,
                    'min' => $request->min,
                    'max' => $request->max,
                    'title' => $request->title,
                    'description' => $request->description,
                    'status' => $request->status,
                    'actual_service' => $request->actual_service,
                    'actual_server' => $request->actual_server,
                    'actual_path' => $request->actual_path,
                    'actual_price' => $request->price_collaborator,
                    'action' => $request->action,
                    'order_type' => $request->order_type,
                    'warranty' => $request->warranty,
                    'service_list' =>$request->service_list,
                    // 'domain' => getDomain(),
                ]);
            } else {
                ServerService::where('domain', getDomain())->where('id', $id)->update([
                    'server' => $request->server_service,
                    'price' => $request->price,
                    'price_collaborator' => $request->price_collaborator,
                    'price_agency' => $request->price_agency,
                    'price_distributor' => $request->price_distributor,
                    'min' => $request->min,
                    'max' => $request->max,
                    'title' => $request->title,
                    'description' => $request->description,
                    'status' => $request->status,
                    'domain' => getDomain(),
                ]);
            }
            return resApi('success', 'Sửa thành công');
        }
    }
    public function serverDelete($id)
    {
        $server = ServerService::where('domain', getDomain())->where('id', $id)->first();
        if ($server) {
            $server->delete();
            return redirect()->back()->with('success', 'Xóa thành công');
        } else {
            return redirect()->back()->with('error', 'Không tìm thấy máy chủ');
        }
    }

    public function serviceChecking(Request $request)
    {
        $valid = Validator::make($request->all(), [
            'id' => 'required|integer',
        ]);

        if ($valid->fails()) {
            return resApi('error', $valid->errors()->first());
        } else {
            $social = ServiceSocial::where('domain', env('PARENT_SITE'))->where('id', $request->id)->first();
            if ($social) {
                $service_list = Service::where('domain', env('PARENT_SITE'))->where('service_social', $social->slug)->get();
                if ($service_list) {
                    return resApi('success', 'Thành công', $service_list);
                } else {
                    return resApi('error', 'Không tìm thấy dịch vụ');
                }
            } else {
                return resApi('error', 'Không tìm thấy dịch vụ');
            }
        }
    }

    public function serverNotificationTelegram(Request $request)
    {
        $valid = Validator::make($request->all(), [
            'social' => 'required|integer',
            'service' => 'required|integer',
            'content' => 'required|string',
        ]);

        if ($valid->fails()) {
            return resApi('error', $valid->errors()->first());
        } else {
            $tele = new TelegramCustomController();
            $bot = $tele->bot();

            $social = ServiceSocial::where('domain', getDomain())->where('id', $request->social)->first();
            if ($social) {
                $service = Service::where('domain', getDomain())->where('id', $request->service)->first();
                if ($service) {
                    $server = ServerService::where('domain', getDomain())->where('social_id', $social->id)->where('service_id', $service->id)->get();
                    if ($server) {
                        $users = User::where('domain', getDomain())->where('telegram_verified', 'yes')->where('telegram_notice', 'on')->get();
                        if ($users) {
                            foreach ($users as $user) {
                                $bot->sendMessage([
                                    'chat_id' => $user->telegram_chat_id,
                                    'text' => "Thông báo từ dịch vụ \n" . $social->name . " - " . $service->name . "\n" . $request->content,
                                    'parse_mode' => 'HTML',
                                ]);
                            }
                            return resApi('success', 'Gửi thông báo thành công');
                        } else {
                            return resApi('error', 'Không tìm thấy người dùng');
                        }
                    } else {
                        return resApi('error', 'Không tìm thấy máy chủ');
                    }
                } else {
                    return resApi('error', 'Không tìm thấy dịch vụ');
                }
            } else {
                return resApi('error', 'Không tìm thấy dịch vụ');
            }
        }
    }

    public function orderActive(Request $request)
    {
        $valid = Validator::make($request->all(), [
            'id' => 'required|integer',
        ]);

        if ($valid->fails()) {
            return resApi('error', $valid->errors()->first());
        } else {
            $order = Orders::where('domain', getDomain())->where('id', $request->id)->where('status', 'Pending')->update([
                'status' => 'Active',
            ]);

            if ($order) {
                return resApi('success', 'Thành công');
            } else {
                return resApi('error', 'Không tìm thấy đơn hàng');
            }
        }
    }

    public function orderCancel(Request $request)
    {
        $valid = Validator::make($request->all(), [
            'id' => 'required|integer',
        ]);

        if ($valid->fails()) {
            return resApi('error', $valid->errors()->first());
        } else {
            $order = Orders::where('domain', getDomain())->where('id', $request->id)->where('status', 'Pending')->update([
                'status' => 'Cancelled',
            ]);

            if ($order) {
                $user = User::where('domain', getDomain())->where('username', $order->username)->first();
                if ($user) {
                    $user->balance = $user->balance + $order->total_payment;
                    $user->total_deduct = $user->total_deduct - $order->total_payment;
                    $user->save();
                }
                return resApi('success', 'Thành công');
            } else {
                return resApi('error', 'Không tìm thấy đơn hàng');
            }
        }
    }
    public function approveAllOrders(Request $request)
    {
        try {
            // Lấy tất cả đơn hàng có trạng thái "Pending"
            $orders = Orders::where('domain', getDomain())->where('status', 'Pending')->get();
    
            if ($orders->isEmpty()) {
                return resApi('error', 'Không có đơn hàng nào cần duyệt.');
            }
    
            // Cập nhật trạng thái tất cả đơn hàng thành "Active"
            Orders::where('domain', getDomain())->where('status', 'Pending')->update([
                'status' => 'Active',
            ]);
    
            return resApi('success', 'Tất cả đơn hàng đã được duyệt thành công!');
        } catch (\Exception $e) {
            return resApi('error', 'Lỗi: ' . $e->getMessage());
        }
    }
}
