<?php

namespace App\Console\Commands;

use App\Models\AccountRecharge;
use App\Models\DataHistory;
use App\Models\SiteData;
use App\Models\User;
use Illuminate\Console\Command;
use App\Models\HistoryRecharge;
use Illuminate\Support\Carbon;

class RechargeTransfer extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'recharge:transfer {bank} {domain}';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Recharge transfer to bank';

    /**
     * Execute the console command.
     *
     * @return int
     */
    public function handle()
    {
        $bank = $this->argument('bank');
        $domain = $this->argument('domain');
        $account = AccountRecharge::where('type', $bank)->where('domain', $domain)->first();
        if ($account) {
            $api_token = $account->api_token;
            $stk = $account->account_number;
            $SiteData = SiteData::where('domain', $domain)->first();
            $code_tranfer = $SiteData->code_tranfer;
            if ($account->type == 'mbbank') {
                $dataPost = array(
                    "Loai_api" => "lsgdv2",
                );
                $curl = curl_init();
                curl_setopt_array($curl, array(
                    CURLOPT_URL => "https://api.dichvudark.vn/api/ApiMbBank",
                    CURLOPT_RETURNTRANSFER => true,
                    CURLOPT_SSL_VERIFYPEER => false,
                    CURLOPT_MAXREDIRS => 10,
                    CURLOPT_TIMEOUT => 30,
                    CURLOPT_CUSTOMREQUEST => "POST",
                    CURLOPT_POSTFIELDS => $dataPost,
                    CURLOPT_HTTPHEADER => array(
                        "Code: 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",
                        "Token: " . $api_token,
                        "Stk: " . $stk
                    )
                ));
                $response = curl_exec($curl);
                curl_close($curl);
                //hiện kết quả
                // print_r($response);
                $result = json_decode($response, true);
                if (isset($result)) {
                    $count = 0;
                    foreach ($result['transactionHistoryList'] as $key => $item) {
                        $benAccountName = $item['benAccountName'];
                        $refNo = $item['refNo'];
                        $description = $item['description'];
                        $creditAmount = $item['creditAmount'];
                        $debitAmount = $item['debitAmount'];
                        $description1 = str_replace(" ", "", $description);
                        if ($creditAmount > 0 || $debitAmount == 0) {
                            $checkId = strpos($description1, $code_tranfer);
                            if ($checkId !== false) {
                                preg_match('/nptm(\d+)/', $description1, $matches);
                                $idUser=null;
                                if (isset($matches[1])) {
                                    $idUser = $matches[1];
                                }
                                // $ch1 = explode($code_tranfer, $description1);
                                // $ch1 = strtolower($ch1[1]);
                                // $ch1 = str_replace("\n", "", $ch1);
                                // dd($ch1);
                                // $ch2 = explode('.', $ch1);
                                // $ch1 = $ch2[0];
                                // $ch2 = explode(' ', $ch1);
                                // $idUser = $ch2[0];
                                //name bank
                                $name = "Không xác định";
                                $user = User::find($idUser);
                                if ($user) {
                                    $refNo = base64_encode($refNo);
                                    $checkTranid = HistoryRecharge::where('tranid', $refNo)->first();
                                    if ($checkTranid) {
                                        continue;
                                    } else {
                                        $balance = $user->balance;
                                        $recharge_promotion = $SiteData->recharge_promotion;
                                        $start_promotion = $SiteData->start_promotion;
                                        $end_promotion = $SiteData->end_promotion;

                                        $promotion = 0;
                                        if (strtotime($start_promotion) <= strtotime(date('Y-m-d')) && strtotime($end_promotion) >= strtotime(date('Y-m-d'))) {
                                            $recharge_promotion = $SiteData->recharge_promotion;
                                            $promotion = $creditAmount * $recharge_promotion / 100;
                                            $amount = $creditAmount + $promotion;
                                        } else {
                                            $amount = $creditAmount;
                                            $recharge_promotion = 0;
                                        }
                                        // echo $creditAmount;
                                        $user->balance = $user->balance + $creditAmount;
                                        $user->total_recharge = $user->total_recharge + $creditAmount;
                                        DataHistory::create([
                                            'username' => $user->username,
                                            'action' => 'Nạp tiền',
                                            'data' => $amount,
                                            'old_data' => $balance,
                                            'new_data' => $user->balance + $amount,
                                            'ip' => $user->ip,
                                            'description' => "Nạp tiền qua Mbbank với số tiền: " . number_format($creditAmount) . " VNĐ được khuyến mãi " . $recharge_promotion . "% thực nhận: " . number_format($amount) . " VNĐ",
                                            'domain' => $user->domain,
                                        ]);
                                        HistoryRecharge::create([
                                            'username' => $user->username,
                                            'name_bank' => $name ?? 'Không xác định',
                                            'type_bank' => 'mbbank',
                                            'tranid' => $refNo,
                                            'amount' => $creditAmount,
                                            'promotion' => $recharge_promotion,
                                            'real_amount' => $amount,
                                            'status' => 'Success',
                                            'note' => $description,
                                            'domain' => $user->domain,
                                        ]);
                                        $user->save();
                                        $count++;
                                    }
                                }
                                else{
                                    $this->error('Không tìm thấy tài khoản');
                                }
                            }
                        }
                    }
                    $this->info('Nạp thành công ' . $count . ' tài khoản');
                }
                else {
                    $this->info('Lỗi: ' . $result['result']['message']);
                }
            } elseif ($account->type == 'momo') {
                $dataPost = array(
                    "Loai_api" => "lsgd1h",
                );
                $curl = curl_init();
                curl_setopt_array($curl, array(
                    CURLOPT_URL => "https://api.dichvudark.vn/api/ApiMomo",
                    CURLOPT_RETURNTRANSFER => true,
                    CURLOPT_SSL_VERIFYPEER => false,
                    CURLOPT_MAXREDIRS => 10,
                    CURLOPT_TIMEOUT => 30,
                    CURLOPT_CUSTOMREQUEST => "POST",
                    CURLOPT_POSTFIELDS => $dataPost,
                    CURLOPT_HTTPHEADER => array(
                        "Code: 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",
                        "Token: " . $api_token,
                        "Hour: 1"
                    )
                ));
                $response = curl_exec($curl);
                curl_close($curl);
                //hiện kết quả
                $result = json_decode($response, true);
                print_r($response);

                foreach ($result['tranList'] as $key => $item) {
                    $partnerName = $item['partnerName'];
                    $partnerId = $item['partnerId'];
                    $amount = $item['amount'];
                    $comment = $item['comment'];
                    $tranId = $item['tranId'];
                    $io = $item['io'];

                    $checkId = strpos($comment, $code_tranfer);
                    if ($checkId !== false) {
                        $ch1 = explode($code_tranfer, $comment);
                        $ch1 = strtolower($ch1[1]);
                        $ch1 = str_replace("\n", "", $ch1);
                        $ch2 = explode('.', $ch1);
                        $ch1 = $ch2[0];
                        $ch2 = explode(' ', $ch1);
                        $idUser = $ch2[0];
                        //name bank
                        $name = "Không xác định";
                        $user = User::find($idUser);
                        if ($user) {
                            $checkTranid = HistoryRecharge::where('tranid', $tranId)->first();
                            if ($checkTranid) {
                                continue;
                            } else {
                                $balance = $user->balance;
                                $recharge_promotion = $SiteData->recharge_promotion;
                                $start_promotion = $SiteData->start_promotion;
                                $end_promotion = $SiteData->end_promotion;

                                if ($io == 1) {
                                    $amount = $amount;
                                    $promotion = 0;
                                    if (strtotime($start_promotion) <= strtotime(date('Y-m-d')) && strtotime($end_promotion) >= strtotime(date('Y-m-d'))) {
                                        $recharge_promotion = $SiteData->recharge_promotion;
                                        $promotion = $amount * $recharge_promotion / 100;
                                        $amount = $amount + $promotion;
                                    } else {
                                        $amount = $amount;
                                        $recharge_promotion = 0;
                                    }
                                    // echo $creditAmount;
                                    $user->balance = $user->balance + $amount;
                                    $user->total_recharge = $user->total_recharge + $amount;
                                    DataHistory::create([
                                        'username' => $user->username,
                                        'action' => 'Nạp tiền',
                                        'data' => $amount,
                                        'old_data' => $balance,
                                        'new_data' => $user->balance + $amount,
                                        'ip' => $user->ip,
                                        'description' => "Nạp tiền qua Momo với số tiền: " . number_format($amount) . " VNĐ được khuyến mãi " . $recharge_promotion . "% thực nhận: " . number_format($amount) . " VNĐ",
                                        'domain' => $user->domain,
                                    ]);
                                    HistoryRecharge::create([
                                        'username' => $user->username,
                                        'name_bank' => $partnerName ?? 'Không xác định',
                                        'type_bank' => 'momo',
                                        'tranid' => $tranId,
                                        'amount' => $amount,
                                        'promotion' => $recharge_promotion,
                                        'real_amount' => $amount,
                                        'status' => 'Success',
                                        'note' => $comment,
                                        'domain' => $user->domain,
                                    ]);
                                    $user->save();
                                }
                            }
                        }
                    }
                }
            }
        } else {
            $this->info('Không tìm thấy tài khoản');
        }
    }
}
