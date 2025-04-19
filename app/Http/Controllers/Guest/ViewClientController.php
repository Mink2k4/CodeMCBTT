<?php

namespace App\Http\Controllers\Guest;

use App\Http\Controllers\Controller;
use App\Models\AccountRecharge;
use App\Models\Activities;
use App\Models\Ticket;
use App\Models\Notification;
use App\Models\SiteCon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Models\Refund;
use App\Models\Affiliate;
use App\Models\AffiliatePayout;
use Illuminate\Support\Facades\DB;

class ViewClientController extends Controller
{

    public function LandingPage(){
        return view('landing');
    }

    public function HomePage()
    {
        $notification = Notification::where('domain', getDomain())->orderBy('id', 'DESC')->get();
        $activities = Activities::where('domain', getDomain())->orderBy('id', 'DESC')->get();
        return view('Client.home', compact('notification', 'activities'));
    }

    public function ProfilePage()
    {
        return view('Client.profile');
    }

    public function TransferPage()
    {
        $account = AccountRecharge::where('domain', getDomain())->get();
        return view('Client.Recharge.transfer', compact('account'));
    }

    public function CardPage()
    {
        return view('Client.Recharge.card');
    }

    public function HistoryPage()
    {
        return view('Client.User.history');
    }

    public function LevelPage()
    {
        return view('Client.User.level');
    }
    // Affiliates
    public function affiliates()
    {
        $user = Auth::user();
        $affiliate = Affiliate::where('user_id', $user->id)->first();

        if (!$affiliate) {
            // Nếu user chưa có dữ liệu affiliate, tạo mới
            $affiliate = Affiliate::create([
                'user_id' => $user->id,
                'visits' => 0,
                'registrations' => 0,
                'referrals' => 0,
                'conversion_rate' => 0.00,
                'total_earnings' => 0.00,
                'available_earnings' => 0.00,
            ]);
        }

        $payouts = AffiliatePayout::where('user_id', $user->id)->get();

        return view('Client.Affiliates.index', [
            'user' => $user,
            'visits' => $affiliate->visits,
            'registrations' => $affiliate->registrations,
            'referrals' => $affiliate->referrals,
            'conversionRate' => $affiliate->conversion_rate,
            'totalEarnings' => $affiliate->total_earnings,
            'availableEarnings' => $affiliate->available_earnings,
            'payouts' => $payouts
        ]);
    }
    public function trackReferral($id)
    {
        // Kiểm tra user có tồn tại không
        $referrer = User::find($id);
        if (!$referrer) {
            return redirect('/')->with('error', 'Người giới thiệu không tồn tại.');
        }
    
        // Kiểm tra nếu đã có session tránh tính lại nhiều lần
        if (!session()->has('visited_referral_' . $id)) {
            session(['visited_referral_' . $id => true]);
    
            // Cập nhật số lượt visit trong bảng Affiliate
            $affiliate = Affiliate::where('user_id', $id)->first();
            if ($affiliate) {
                $affiliate->increment('visits');
            } else {
                $affiliate = Affiliate::create([
                    'user_id' => $id,
                    'visits' => 1,
                    'registrations' => 0,
                    'referrals' => 0,
                    'conversion_rate' => 0.00,
                    'total_earnings' => 0.00,
                    'available_earnings' => 0.00,
                ]);
            }
    
            // Cập nhật conversion rate
            $affiliate->updateConversionRate();
        }
    
        return redirect()->route('register')->with('referral_id', $id);
    }
    public function TicketPage() {
        $tickets = Ticket::where('user_id', Auth::id())
                         ->orderBy('id', 'DESC')
                         ->get();
    
        return view('Client.Tickets.index', compact('tickets'));
    }
    public function CreateTicketPage() {
        return view('Client.Tickets.create');
    }

    public function ViewTicket($id)
    {
        $ticket = Ticket::find($id);
    
        if (!$ticket) {
            return redirect()->route('tickets')->with('error', 'Ticket không tồn tại.');
        }
    
        return view('Client.Tickets.view', compact('ticket'));
    }
    public function store(Request $request)
    {
        $request->validate([
            'category' => 'required|string',
            'type' => 'required|string',
            'order_id' => 'required|string',
            'reason' => 'required|string',
            'image' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:2048'
        ]);
    
        // Lưu ảnh nếu có
        $imagePath = null;
        if ($request->hasFile('image')) {
            $imagePath = $request->file('image')->store('ticket_images', 'public');
        }
    
        // Tạo ticket mới
        $ticket = Ticket::create([
            'user_id' => Auth::id(),
            'title' => $request->category . " - " . $request->type,
            'order_id' => $request->order_id, // Lưu Order ID
            'description' => $request->reason, // Lý do
            'status' => 'Pending',
            'image' => $imagePath,
        ]);
    
        // Nếu loại là "Hoàn tiền" thì thêm vào bảng refunds
        if ($request->type === 'Hoàn tiền') {
            Refund::create([
                'user_id' => Auth::id(), // Thêm user_id
                'requester' => Auth::user()->name,
                'order_id' => $ticket->order_id,
                'reason' => $ticket->description,
                'status' => 'Pending',
                'request_date' => now(),
            ]);
        }
    
        return redirect()->route('tickets')->with('success', 'Ticket đã được gửi!');
    }
    public function update(Request $request, $id)
    {
        $ticket = Ticket::findOrFail($id); 
    
        // Cập nhật nội dung ticket
        $ticket->description = $request->description;
    
        // Nếu có ảnh mới, lưu ảnh và cập nhật đường dẫn
        if ($request->hasFile('image')) {
            $imagePath = $request->file('image')->store('ticket_images', 'public');
            $ticket->image = $imagePath;
        }
    
        // Chuyển trạng thái về 'pending'
        $ticket->status = 'pending';
        $ticket->save();
    
        return redirect()->back()->with('success', 'Cập nhật thành công!');
    }
    public function refundIndex()
    {
        return view('Client.Refund.index');
    }
    public function refundHistory()
    {
        $user = auth()->user();
        
        if (!$user) {
            return redirect()->route('login')->with('error', 'Bạn cần đăng nhập để xem lịch sử hoàn tiền.');
        }
    
        // Lấy lịch sử hoàn tiền từ bảng refunds
        $refunds = Refund::where('status', '!=', 'archived')->get();
        return view('Client.Refund.index', compact('refunds'));
    }
    public function CreateWebsite()
    {
        if (getDomain() == env('PARENT_SITE')) {
            $sitecon = SiteCon::where('domain', getDomain())->where('username', Auth::user()->username)->first();
            if(!$sitecon){
                // stdclass domain
                $sitecon = new \stdClass();
                $sitecon->domain_name = '';
            }
            return view('Client.Website.create', compact('sitecon'));
        } else{
            return abort(404);
        }
    }
    
    public function ToolUid()
    {
        return view('Client.Tool.getUid');
    }
}
