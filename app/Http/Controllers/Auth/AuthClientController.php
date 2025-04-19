<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Http\Controllers\Custom\TelegramCustomController;
use App\Models\PasswordReset;
use App\Models\SiteData;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Session;
use Illuminate\Support\Facades\Mail;
use App\Mail\MailForgotPassword;
use Laravel\Socialite\Facades\Socialite;

class AuthClientController extends Controller
{
    public function __construct()
    {
        $this->middleware('xss');
    }

    public function LoginPage()
    {
        return view('Auth.login');
    }

    public function RegisterPage()
    {
        return view('Auth.register');
    }

    public function ForgotPasswordPage()
    {
        return view('Auth.forgot-password');
    }

    public function LoginGoogle()
    {
        return Socialite::driver('google')->redirect();
    }

    public function LoginGoogleCallback(Request $requsest)
    {
        $user = Socialite::driver('google')->user();
        $check = User::where('email', $user->email)->where('domain', getDomain())->first();
        if ($check) {
            Auth::login($check);
            return redirect()->route('home')->with('success', 'Đăng nhập thành công');
        } else {
            $newUser = User::create([
                'name' => $user->name,
                'username' => $user->id,
                'email' => strtolower($user->email),
                'password' => Hash::make(Str::random(8)),
                'balance' => 0,
                'total_recharge' => 0,
                'total_deduct' => 0,
                'referral_money' => 0,
                'position' => 'user',
                'avatar' => $user->avatar,
                'api_token' => encrypt($user->email . '|', $user->name . '|' . Str::random(32)),
                'domain' => getDomain(),
            ]);

            if ($newUser) {
                if (Auth::attempt(['email' => $user->email, 'password' => $user->password], true)) {
                    return redirect()->route('home')->with('success', 'Đăng nhập thành công');
                } else {
                    return redirect()->back()->with('error', 'Đăng nhập thất bại');
                }
            } else {
                return redirect()->back()->with('error', 'Đăng kí thất bại');
            }
        }
    }

    public function ForgotPassword(Request $request)
    {
        $valid = Validator::make($request->all(), [
            'email' => 'required|string|email|max:255',
        ]);

        if ($valid->fails()) {
            return redirect()->back()->withErrors($valid)->withInput();
        } else {
            $user = User::where('email', $request->email)->where('domain', getDomain())->first();
            if ($user) {
                $token = Str::random(60);
                $check = PasswordReset::where('email', $request->email)->where('domain', getDomain())->first();
                if ($check) {
                    $check->update([
                        'token' => $token
                    ]);
                } else {
                    PasswordReset::create([
                        'email' => $request->email,
                        'token' => $token,
                        'domain' => getDomain()
                    ]);
                }
                // thay đổi tên người gửi trong file config/mail.php
                Mail::to($request->email)->send(new MailForgotPassword(route('reset.password', $token)));
                return redirect()->back()->with('success', 'Vui lòng kiểm tra email để lấy lại mật khẩu');
            } else {
                return redirect()->back()->with('error', 'Email không tồn tại')->withInput(['email' => $request->email]);
            }
        }
    }

    public function ResetPasswordPage($token)
    {
        $token = PasswordReset::where('token', $token)->where('domain', getDomain())->first();
        if ($token) {
            return view('Auth.reset-password', compact('token'));
        } else {
            return redirect()->route('forgot.password')->with('error', 'Token không hợp lệ');
        }
    }

    public function ResetPassword($token, Request $request)
    {
        $token = PasswordReset::where('token', $token)->where('domain', getDomain())->first();
        if ($token) {
            $valid = Validator::make($request->all(), [
                'password' => 'required|string|min:8|confirmed',
                'password_confirmation' => 'required|string|min:8|same:password',
            ]);

            if ($valid->fails()) {
                return redirect()->back()->withErrors($valid)->withInput();
            } else {
                $user = User::where('email', $token->email)->where('domain', getDomain())->first();
                if ($user) {
                    $user->update([
                        'password' => Hash::make($request->password)
                    ]);
                    $token->delete();
                    return redirect()->route('login')->with('success', 'Đổi mật khẩu thành công');
                } else {
                    return redirect()->route('forgot.password')->with('error', 'Email không tồn tại');
                }
            }
        } else {
            return redirect()->route('forgot.password')->with('error', 'Token không hợp lệ');
        }
    }

    public function Login(Request $request)
    {
        $valid = Validator::make($request->all(), [
            'username' => 'required|string|max:255',
            'password' => 'required|string|min:8',
        ]);

        if ($valid->fails()) {
            return redirect()->back()->withErrors($valid)->withInput();
        } else {
            $user = User::where('username', $request->username)->where('domain', getDomain())->first();
            // sử dụng auth
            if ($user) {
                if (Auth::attempt(['username' => $request->username, 'password' => $request->password, 'domain' => getDomain()], $request->remember)) {
                    // thông báo đăng nhập cho người dùng có liên kết telegram
                    if (DataSite('notice_login') == 'on') {
                        if ($user->telegram_verified == 'yes') {
                            $tele = new TelegramCustomController();
                            $bot = $tele->bot();
                            $bot->sendMessage([
                                'chat_id' => $user->telegram_chat_id,
                                'text' => "🔔 Đăng nhập thành công vào tài khoản thành công. \n" . "Địa chỉ Ip Đăng nhập: " . $request->ip() . "\n" . "Thời gian đăng nhập: " . now() . "\n" . "Trình duyệt: " . $request->header('User-Agent'),
                            ]);
                        }
                    }
                    $user->update([
                        'ip' => $request->ip(),
                        'last_login' => now()
                    ]);
                    Auth::logoutOtherDevices($request->password);
                    return redirect()->route('home')->with('success', 'Đăng nhập thành công');
                } else {
                    return redirect()->back()->with('error', 'Sai mật khẩu')->withInput(['username' => $request->username]);
                }
            } else {
                return redirect()->back()->with('error', 'Tài khoản không tồn tại')->withInput(['username' => $request->username]);
            }
        }
    }

    public function Register(Request $request)
    {
        $valid = Validator::make($request->all(), [
            'name' => 'required|string|max:255',
            'email' => 'required|string|email|max:255|unique:users,email',
            'username' => 'required|string|max:255|unique:users,username',
            'password' => 'required|string|min:8|confirmed',
            'password_confirmation' => 'required|string|min:8|same:password',
            'referral_code' => 'nullable|string|exists:users,username', // Mã giới thiệu là username của người giới thiệu
        ]);
    
        if ($valid->fails()) {
            return redirect()->back()->withErrors($valid)->withInput();
        } else {
            // Kiểm tra mã giới thiệu
            $referrer = null;
            if ($request->filled('referral_code')) {
                $referrer = User::where('username', $request->referral_code)->where('domain', getDomain())->first();
            }
    
            // Tạo user mới
            $newUser = User::create([
                'name' => $request->name,
                'username' => $request->username,
                'email' => strtolower($request->email),
                'password' => Hash::make($request->password),
                'api_token' => encrypt($request->email . '|', $request->username . '|' . Str::random(32)),
                'balance' => 0,
                'total_recharge' => 0,
                'total_deduct' => 0,
                'referral_money' => 0,
                'domain' => getDomain(),
                'referrer_id' => $referrer ? $referrer->id : null, // Lưu ID người giới thiệu
            ]);
    
            // Nếu có người giới thiệu, cập nhật bảng affiliates
            if ($referrer) {
                $affiliate = \App\Models\Affiliate::firstOrCreate(
                    ['user_id' => $referrer->id],
                    ['visits' => 0, 'registrations' => 0, 'referrals' => 0, 'conversion_rate' => 0, 'total_earnings' => 0, 'available_earnings' => 0]
                );
    
                $affiliate->increment('referrals'); // Tăng số lượt giới thiệu
                $affiliate->increment('registrations'); // Tăng số lượt đăng ký
                $affiliate->conversion_rate = ($affiliate->visits > 0) ? ($affiliate->registrations / $affiliate->visits) * 100 : 0;
                $affiliate->save();
            }
    
            if ($newUser) {
                return redirect()->route('login')->with('success', 'Đăng ký thành công')->withInput(['username' => $request->username]);
            } else {
                return redirect()->back()->withErrors('error', 'Đăng ký thất bại')->withInput(['username' => $request->username]);
            }
        }
    }

    public function Logout()
    {
        Session::flush();
        Auth::logout(Auth::user());
        return redirect()->route('login')->with('success', 'Đăng xuất thành công');
    }

    public function InstallPage()
    {
        Auth::logout(Auth::user());
        return view('Auth.install');
    }

    public function Install(Request $request)
    {
        if (env('PARENT_SITE') == getDomain()) {
            $valid = Validator::make($request->all(), [
                'name' => 'required|string|max:255',
                'email' => 'required|string|email|max:255|unique:users,email',
                'username' => 'required|string|max:255|unique:users,username',
                'password' => 'required|string|min:8|confirmed',
                'password_confirmation' => 'required|string|min:8|same:password',
            ]);

            if ($valid->fails()) {
                return redirect()->back()->withErrors($valid)->withInput();
            } else {
                $token = Str::random(80);
                $newUser = User::create([
                    'name' => $request->name,
                    'username' => $request->username,
                    'email' => strtolower($request->email),
                    'password' => Hash::make($request->password),
                    'balance' => 0,
                    'total_recharge' => 0,
                    'total_deduct' => 0,
                    'referral_money' => 0,
                    'position' => 'admin',
                    'api_token' => $token,
                    'domain' => getDomain(),
                ]);

                if ($newUser) {

                    $site = SiteData::where('domain', getDomain())->first();
                    if (!$site) {
                        SiteData::create([
                            'namesite' => getDomain(),
                            'is_admin' => json_encode($newUser->only(['id', 'name', 'username', 'email', 'position', 'api_token', 'domain'])),
                            'token_web' => $newUser->api_token,
                            'username_web' => $newUser->username,
                            'status' => 'Active',
                            'domain' => getDomain(),
                        ]);
                    } else {
                        $site->update([
                            'is_admin' => json_encode($newUser->only(['id', 'name', 'username', 'email', 'position', 'api_token', 'domain'])),
                            'token_web' => $newUser->api_token,
                            'username_web' => $newUser->username,
                            'status' => 'Active',
                            'domain' => getDomain(),
                        ]);
                    }

                    return redirect()->route('login')->with('success', 'Đăng ký thành công')->withInput(['username' => $request->username]);
                } else {
                    return redirect()->back()->with('error', 'Đăng kí thất bại');
                }
            }
        } else {
            $valid = Validator::make($request->all(), [
                'api_token' => 'required|string',
                'name' => 'required|string|max:255',
                'email' => 'required|string|email|max:255|unique:users,email',
                'username' => 'required|string|max:255|unique:users,username',
                'password' => 'required|string|min:8|confirmed',
                'password_confirmation' => 'required|string|min:8|same:password',
            ]);

            if ($valid->fails()) {
                return redirect()->back()->withErrors($valid)->withInput();
            } else {
                $userParent = User::where('api_token', $request->api_token)->where('domain', env('PARENT_SITE'))->first();
                if ($userParent) {
                    $site = SiteData::where('domain', getDomain())->first();
                    if ($site) {
                        $site->update([
                            'is_admin' => json_encode($userParent->only(['id', 'name', 'username', 'email', 'position', 'api_token', 'domain'])),
                            'token_web' => $userParent->api_token,
                            'username_web' => $userParent->username,
                            'status' => 'Active',
                            'domain' => getDomain(),
                        ]);
                    } else {
                        SiteData::create([
                            'namesite' => getDomain(),
                            'is_admin' => json_encode($userParent->only(['id', 'name', 'username', 'email', 'position', 'api_token', 'domain'])),
                            'token_web' => $userParent->api_token,
                            'username_web' => $userParent->username,
                            'status' => 'Active',
                            'domain' => getDomain(),
                        ]);
                    }
                    $token = encrypt($request->email . '|', $request->username . '|' . Str::random(32));
                    $newUser = User::create([
                        'name' => $request->name,
                        'username' => $request->username,
                        'email' => strtolower($request->email),
                        'password' => Hash::make($request->password),
                        'balance' => 0,
                        'total_recharge' => 0,
                        'total_deduct' => 0,
                        'referral_money' => 0,
                        'position' => 'admin',
                        'api_token' => $token,
                        'domain' => getDomain(),
                    ]);

                    if ($newUser) {
                        return redirect()->route('login')->with('success', 'Đăng ký thành công')->withInput(['username' => $request->username]);
                    } else {
                        return redirect()->back()->with('error', 'Đăng kí thất bại');
                    }
                } else {
                    return redirect()->back()->with('error', 'API Token không hợp lệ');
                }
            }
        }
    }
}
