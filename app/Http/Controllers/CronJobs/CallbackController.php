<?php

namespace App\Http\Controllers\CronJobs;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Http\Controllers\Custom\TelegramCustomController;
use App\Models\DataHistory;
use App\Models\User;
use Illuminate\Support\Facades\Storage;

class CallbackController extends Controller
{
    public function telegramV1(Request $request)
    {
        $input = json_decode(file_get_contents('php://input'), true);
        $tele = new TelegramCustomController();
        $bot = $tele->bot();


        if (isset($input['message'])) {
            $message = $input['message'];
            $chat_id = $message['chat']['id'];
            $text = $message['text'];

            if ($text == '/start') {
                $bot->sendMessage([
                    'chat_id' => $chat_id,
                    'text' => "Xin chào tôi là Bot của Website " . getDomain() . ". Bạn vui lòng chọn thao tác bên dưới",
                    'reply_markup' => [
                        'inline_keyboard' => [
                            [
                                ['text' => 'Xác thực tài khoản', 'callback_data' => 'verify_account'],
                            ],
                        ]
                    ]
                ]);
            } elseif (strpos($text, '/set') !== false) {
                $token = str_replace('/set ', '', $text);
                $token = str_replace(' ', '', $token);
                $token = str_replace("\n", '', $token);
                $token = str_replace("\r", '', $token);
                $user = User::where('api_token', $token)->first();
                // checkid chat có tồn tại trong hệ thống hay không
                $user_check = User::where('telegram_chat_id', $chat_id)->first();
                if ($user_check) {
                    $bot->sendMessage([
                        'chat_id' => $chat_id,
                        'text' => "Tài khoản của bạn đã được xác thực trước đó. Bạn vui lòng kiểm tra lại",
                    ]);
                    return;
                } else {
                    if ($user) {
                        if ($user->telegram_verified == 'no') {
                            $balance_telegram = DataSite('balance_telegram') ?? 0;
                            $balance = $user->balance + $balance_telegram;
                            $user->update([
                                'balance' => $balance,
                                'telegram_verified' => 'yes',
                                'telegram_chat_id' => "$chat_id",
                            ]);

                            $bot->sendMessage([
                                'chat_id' => $chat_id,
                                'text' => "Xác thực tài khoản thành công. Hệ thống sẽ cấp phần thưởng cho bạn sau vài phút",
                            ]);
                        } else {
                            $bot->sendMessage([
                                'chat_id' => $chat_id,
                                'text' => "Tài khoản của bạn đã được xác thực trước đó. Bạn vui lòng kiểm tra lại",
                            ]);
                        }
                    } else {
                        $bot->sendMessage([
                            'chat_id' => $chat_id,
                            'text' => "Xác thực tài khoản thất bại. Bạn vui lòng kiểm tra lại token API của mình",
                        ]);
                    }
                }
            } else {
                $bot->sendMessage([
                    'chat_id' => $chat_id,
                    'text' => "Xin lỗi chúng tôi không có quyền để trả lời tin nhắn của bạn",
                ]);
            }
        }

        if (isset($input['callback_query'])) {
            $callback_query = $input['callback_query'];
            $chat_id = $callback_query['message']['chat']['id'];
            $data = $callback_query['data'];

            if ($data == 'verify_account') {
                $img = storage_path('/app/images/cach-lay-api-token.png');
                $bot->sendPhoto([
                    'chat_id' => $chat_id,
                    'photo' => $img,
                    'caption' => "Bạn vui lòng truy cập vào thông tin tài khoản của bạn để lấy token API \n(Truy cập https://" . getDomain() . "/profile) \nLưu ý: sau khi đã coppy Token bạn vui lòng dùng lệnh /set <token> để xác thực tài khoản",
                ]);
            }
        }
    }
}
