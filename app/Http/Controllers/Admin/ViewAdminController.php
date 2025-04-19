<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\DataHistory;
use App\Models\HistoryCard;
use App\Models\HistoryRecharge;
use App\Models\Orders;
use App\Models\Ticket;
use App\Models\Refund;
use App\Models\ServerService;
use App\Models\Service;
use App\Models\ServiceSocial;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Carbon;
use Illuminate\Support\Facades\Validator;

class ViewAdminController extends Controller
{
    public function dashboard()
    {
        $domain = getDomain(); 
        $total_user = User::where('domain', $domain)->count();
        $total_balance = User::where('domain', $domain)->sum('balance');
        $total_recharge = User::where('domain', $domain)->sum('total_recharge');
        $total_order = Orders::where('domain', $domain)->count();
        $total_user_today = User::where('domain', $domain)->whereDate('created_at', Carbon::today())->count();
        $total_deduct_today = DataHistory::where('domain', $domain)->whereDate('created_at', Carbon::today())->where('action', 'Tạo đơn')->sum('data');
        $total_recharge_today = HistoryRecharge::where('domain', $domain)->whereDate('created_at', Carbon::today())->sum('real_amount') +
                                HistoryCard::where('domain', $domain)->whereDate('created_at', Carbon::today())->sum('card_real_amount');
        $total_order_today = Orders::where('domain', $domain)->whereDate('created_at', Carbon::today())->count();
    
        $order = Orders::where('domain', $domain)->get();
        $order_pending_order = Orders::where('domain', $domain)->where('status', 'Processing')->count();
        $order_processing = Orders::where('domain', $domain)->where('status', 'Processing')->count();
        $order_active = Orders::where('domain', $domain)->whereIn('status', ['Processing', 'Active'])->count();
        $order_suspended = Orders::where('domain', $domain)->where('status', 'Suspended')->count();
        $order_completed = Orders::where('domain', $domain)->whereIn('status', ['Completed', 'Success'])->count();
        $order_success = Orders::where('domain', $domain)->where('status', 'Success')->count();
        $order_failed = Orders::where('domain', $domain)->where('status', 'Failed')->count();
        $order_cancelled = Orders::where('domain', $domain)->where('status', 'Cancelled')->count();
    
        return view('Admin.dashboard', compact(
            'total_user', 'total_balance', 'total_recharge', 'total_order',
            'total_user_today', 'total_deduct_today', 'total_recharge_today', 'total_order_today',
            'order', 'order_pending_order', 'order_processing', 'order_active',
            'order_suspended', 'order_completed', 'order_success', 'order_failed', 'order_cancelled'
        ));
    }

    public function websiteConfig()
    {
        return view('Admin.Website.websiteConfig');
    }
    public function tickets()
    {
        $tickets = Ticket::orderBy('id', 'DESC')->get();
        return view('Admin.Tickets.index', compact('tickets'));
    }
    public function refundIndex()
    {
        $refunds = Refund::all(); // Lấy tất cả dữ liệu refund từ database
        return view('Admin.Refund.index', compact('refunds'));
    }
    public function getRefundList()
    {
        $refunds = Refund::join('users', 'users.id', '=', 'refunds.user_id')
            ->select(
                'refunds.id',
                'users.name as user_name', // Lấy tên thay vì ID
                'refunds.order_id',
                'refunds.reason',
                'refunds.status',
                'refunds.created_at'
            )->get();
    
        return response()->json(['data' => $refunds]);
    }
    public function deleteRefund($id)
    {
        $refund = Refund::find($id);
    
        if (!$refund) {
            return response()->json(['status' => 'error', 'message' => 'Yêu cầu hoàn tiền không tồn tại.']);
        }
    
        $orderId = $refund->order_id;
    
        $refund->delete();
    
        // Cập nhật trạng thái ticket về "completed" nếu title chứa "Hoàn tiền"
        Ticket::where('order_id', $orderId)
            ->where('title', 'LIKE', '%Hoàn tiền%') 
            ->update(['status' => 'completed']);
    
        return response()->json(['status' => 'success', 'message' => 'Xóa thành công.']);
    }
    public function userEditBalanceRefund(Request $request)
    {
        $valid = Validator::make($request->all(), [
            'username' => 'required|string|max:255',
            'balance' => 'required|numeric',
            'order_id' => 'required|string',
        ]);
    
        if ($valid->fails()) {
            return redirect()->back()->with('error', $valid->errors()->first());
        }
    
        $user = User::where('domain', getDomain())->where('username', $request->username)->first();
    
        if ($user) {
            $oldBalance = $user->balance;
            $refundAmount = $request->balance;
            
            // Định dạng số tiền, loại bỏ .00 nếu cần nhưng không có dấu phẩy ngăn cách hàng nghìn
            $formattedRefundAmount = (intval($refundAmount) == $refundAmount) 
                ? number_format($refundAmount, 0, '.', '') 
                : number_format($refundAmount, 2, '.', '');
            $newBalance = $oldBalance + $refundAmount;
    
            // Cập nhật số dư của user
            $user->balance = $newBalance;
            $user->save();
    
            // Ẩn dữ liệu cũ thay vì xóa
            Refund::where('order_id', $request->order_id)->update(['status' => 'archived']);
    
            // Lưu vào bảng lịch sử hoàn tiền
            Refund::create([
                'order_id'       => $request->order_id,
                'username'       => $user->username,
                'refund_amount'  => $formattedRefundAmount,
                'balance_before' => $oldBalance,
                'balance_after'  => $newBalance,
                'reason'         => 'Không có',
                'status'         => 'pending',
                'created_at'     => now(),
                'updated_at'     => now(),
            ]);
    
            // Lưu vào DataHistory
            DataHistory::create([
                'username'   => $user->username,
                'action'     => '+',
                'data'       => $formattedRefundAmount,
                'old_data'   => $oldBalance,
                'new_data'   => $newBalance,
                'ip'         => $request->ip(),
                'data_json'  => json_encode([
                    'username'   => $user->username,
                    'action'     => '+',
                    'data'       => $formattedRefundAmount,
                    'old_data'   => $oldBalance,
                    'new_data'   => $newBalance,
                    'ip'         => $request->ip(),
                ]),
                'description' => "Quản trị viên đã hoàn tiền cho đơn hàng #{$request->order_id}",
                'domain'      => getDomain(),
            ]);
    
            return redirect()->back()->with('success', 'Hoàn tiền thành công');
        } else {
            return redirect()->back()->with('error', 'Không tìm thấy người dùng');
        }
    }
    public function viewRefund($id)
    {
        $refund = Refund::find($id);
    
        if (!$refund) {
            return redirect()->back()->with('error', 'Yêu cầu hoàn tiền không tồn tại.');
        }
    
        $user = User::where('id', $refund->user_id)->first();
    
        if (!$user) {
            return redirect()->back()->with('error', 'Không tìm thấy người dùng.');
        }
    
        return view('Admin.Refund.viewRefund', compact('user', 'refund'));
    }
    public function ticketDetail($id)
    {
        $ticket = Ticket::with('order')->findOrFail($id);
        return view('Admin.Tickets.detail', compact('ticket'));
    }
    public function updateStatus(Request $request, $id)
    {
        $request->validate([
            'status' => 'required|in:pending,in_progress,completed,cancelled',
        ]);
    
        $ticket = Ticket::findOrFail($id);
        $ticket->update(['status' => $request->status]);
    
        return response()->json([
            'success' => true,
            'message' => 'Trạng thái đã được cập nhật!',
            'new_status' => $ticket->status
        ]);
    }
    public function editTicket($id)
    {
        $ticket = Ticket::findOrFail($id);
    
        // Nếu trạng thái hiện tại là 'pending', cập nhật thành 'in_progress'
        if ($ticket->status === 'pending') {
            $ticket->update(['status' => 'in_progress']);
        }
    
        return view('Admin.Tickets.edit', compact('ticket'));
    }
    
    public function updateTicket(Request $request, $id)
    {
        $ticket = Ticket::findOrFail($id);
        $ticket->description = $request->description;
        $ticket->save();
    
        return redirect()->route('admin.tickets.detail', $ticket->id)->with('success', 'Nội dung đã được cập nhật!');
    }
    public function websiteTheme()
    {
        return view('Admin.Website.websiteTheme');
    }

    public function userList()
    {
        return view('Admin.User.userList');
    }

    public function userEdit($id)
    {
        $user = User::where('domain', getDomain())->where('id', $id)->first();
        if ($user) {
            return view('Admin.User.userEdit', compact('user'));
        } else {
            return redirect()->back()->with('error', 'Không tìm thấy người dùng1');
        }
    }

    public function userEditBalance()
    {
        return view('Admin.User.userEditBalance');
    }

    public function notification()
    {
        return view('Admin.Notification.notification');
    }

    public function activity()
    {
        return view('Admin.Activity.activity');
    }

    /* SERVICE */
    public function serviceNewSocial()
    {
        if (getDomain() == env('PARENT_SITE')) {
            return view('Admin.Service.serviceNewSocial');
        } else {
            return abort(404);
        }
    }

    public function serviceSocialEdit($id)
    {
        if (getDomain() == env('PARENT_SITE')) {
            $social = ServiceSocial::where('domain', getDomain())->where('id', $id)->first();
            if ($social) {
                return view('Admin.Service.serviceSocialEdit', compact('social'));
            } else {
                return redirect()->back()->with('error', 'Không tìm thấy dịch vụ');
            }
        } else {
            return abort(404);
        }
    }

    public function serviceNew()
    {
        if (getDomain() == env('PARENT_SITE')) {
            $social = ServiceSocial::where('domain', getDomain())->get();
            $servers = ServerService::where('domain', getDomain())->get(); // Truy vấn thêm danh sách server
            return view('Admin.Service.serviceNew', compact('social', 'servers'));
        } else {
            return abort(404);
        }
    }

    public function serviceEdit($id)
    {
        if (getDomain() == env('PARENT_SITE')) {
            $service = Service::where('id', $id)->where('domain', getDomain())->first();
            if ($service) {
                return view('Admin.Service.serviceEdit', compact('service'));
            } else {
                return redirect()->back()->with('error', 'Không tìm thấy dịch vụ');
            }
        } else {
            return abort(404);
        }
    }

    public function serverList()
    {
        if (getDomain() == env('PARENT_SITE')) {
            $social = ServiceSocial::where('domain', getDomain())->get();
            return view('Admin.Server.serverList', compact('social'));
        } else {
            $social = ServiceSocial::where('domain', env('PARENT_SITE'))->get();
            return view('Admin.Server.serverSiteList', compact('social'));
        }
    }

    public function serverNew()
    {
        if (getDomain() == env('PARENT_SITE')) {
            $social = ServiceSocial::where('domain', getDomain())->get();
            return view('Admin.Server.serverNew', compact('social'));
        } else {
            return abort(404);
        }
    }

    public function serverEdit($id)
    {
        $server = ServerService::where('id', $id)->where('domain', getDomain())->first();
        if ($server) {
            return view('Admin.Server.serverEdit', compact('server'));
        } else {
            return redirect()->back()->with('error', 'Không tìm thấy dịch vụ');
        }
    }

    public function HistoryOrder()
    {
        return view('Admin.History.historyOrder');
    }

    public function HistoryUser()
    {
        return view('Admin.History.historyUser');
    }

    public function rechargeConfig()
    {
        return view('Admin.Recharge.config');
    }

    public function HistoryRecharge()
    {
        return view('Admin.History.historyRecharge');
    }

    public function HistoryCard()
    {
        return view('Admin.History.historyCard');
    }

    public function configTelegram()
    {
        return view('Admin.Config.configTelegram');
    }

    public function websiteChildList(){
        return view('Admin.Website.websiteChildList');
    }
}
