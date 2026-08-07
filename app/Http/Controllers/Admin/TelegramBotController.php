<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Telegram\Bot\Laravel\Facades\Telegram;

class TelegramBotController extends Controller
{
    public function updatedActivity()
    {
        Telegram::sendMessage([
            'chat_id' => env('TELEGRAM_CHAT_ID'),
            'text' => "<b>Đơn hàng mới</b>\nGiá: <code>100000</code>\n<a href=\"https://example.com\">Xem chi tiết</a>",
            'parse_mode' => 'HTML',
            'disable_web_page_preview' => true,
        ]);
    }
}
