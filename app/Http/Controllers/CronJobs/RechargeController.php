<?php

namespace App\Http\Controllers\CronJobs;

// use App\Console\Commands\RechargeCard;
use App\Http\Controllers\Controller;
use App\Models\DataHistory;
use App\Models\HistoryCard;
use App\Models\User;
use Illuminate\Http\Request;

class RechargeController extends Controller
{
    public function RechargeCard(Request $request)
    {
        if (isset($request->status)) {
            $status = $request->status;
            $request_id = $request->request_id;
            $declared_value = $request->declared_value;
            $card_value = $request->card_value;
            $value = $request->value;
            $amount = $request->amount;
            $code = $request->code;
            $serial = $request->serial;
            $telco = $request->telco;
            $trans_id = $request->trans_id;

            $callback_sign = $request->callback_sign;

            $cardRecharge = HistoryCard::where('tranid', $trans_id)->first();
            if ($cardRecharge) {
                $card_discount = DataSite('card_discount');
                $sign = md5(DataSite('partner_key') .  $code . $serial);
                if ($sign == $callback_sign) {
                    if ($status == 1 || $amount > 0) {
                        $tiennhan = $amount - ($amount * $card_discount / 100);
                        $user = User::where('username', $cardRecharge->username)->first();
                        if ($user) {
                            DataHistory::create([
                                'username' => $user->username,
                                'action' => 'Nạp thẻ',
                                'data' => $tiennhan,
                                'old_data' => $user->balance,
                                'new_data' => $user->balance + $tiennhan,
                                'description' => "Tài khoản đã nạp thẻ $code mệnh giá $amount và thực nhận được $tiennhan",
                                'ip' => $request->ip(),
                                'dataJson' => json_encode($request->all()),
                                'domain' => $user->domain
                            ]);

                            $user->balance = $user->balance + $tiennhan;
                            $user->total_recharge = $user->total_recharge + $tiennhan;
                            $user->save();

                            $cardRecharge->discount = $card_discount;
                            $cardRecharge->card_real_amount = $tiennhan;
                            $cardRecharge->status = 'Success';
                            $cardRecharge->save();
                        }
                    } else {
                        $cardRecharge->status = 'Error';
                        $cardRecharge->save();
                    }
                }
            }
        }
    }
    public function RechargeTransfer(Request $request)
    {
        if (isset($request->status)) {
            $status = $request->status;
            $request_id = $request->request_id;
            $declared_value = $request->declared_value;
            $card_value = $request->card_value;
            $value = $request->value;
            $amount = $request->amount;
            $code = $request->code;
            $serial = $request->serial;
            $telco = $request->telco;
            $trans_id = $request->trans_id;

            $callback_sign = $request->callback_sign;

            $cardRecharge = HistoryCard::where('tranid', $trans_id)->first();
            if ($cardRecharge) {
                $card_discount = DataSite('card_discount');
                $sign = md5(DataSite('partner_key') .  $code . $serial);
                if ($sign == $callback_sign) {
                    if ($status == 1 || $amount > 0) {
                        $tiennhan = $amount - ($amount * $card_discount / 100);
                        $user = User::where('username', $cardRecharge->username)->first();
                        if ($user) {
                            DataHistory::create([
                                'username' => $user->username,
                                'action' => 'Nạp thẻ',
                                'data' => $tiennhan,
                                'old_data' => $user->balance,
                                'new_data' => $user->balance + $tiennhan,
                                'description' => "Tài khoản đã nạp thẻ $code mệnh giá $amount và thực nhận được $tiennhan",
                                'ip' => $request->ip(),
                                'dataJson' => json_encode($request->all()),
                                'domain' => $user->domain
                            ]);

                            $user->balance = $user->balance + $tiennhan;
                            $user->total_recharge = $user->total_recharge + $tiennhan;
                            $user->save();

                            $cardRecharge->discount = $card_discount;
                            $cardRecharge->card_real_amount = $tiennhan;
                            $cardRecharge->status = 'Success';
                            $cardRecharge->save();
                        }
                    } else {
                        $cardRecharge->status = 'Error';
                        $cardRecharge->save();
                    }
                }
            }
        }
    }
}
