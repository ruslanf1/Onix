<?php

namespace App\Http\Controllers\Telegram;

use App\Http\Controllers\Controller;
use Exception;
use Telegram\Bot\Laravel\Facades\Telegram;

class SendController extends Controller
{
    /**
     * Принимает строку. Отправляет данные в Телеграм.
     * Возвращает bool.
     *
     * @param $data
     * @return bool
     * @throws Exception
     */
    public static function telegram($data): bool {
        try {
            $chatId = config('app.amo.chat_id');

            Telegram::sendMessage([
                'chat_id' => $chatId,
                'text' => $data,
                'parse_mode' => 'HTML',
            ]);
            return true;
        } catch (Exception) {
            return false;
        }
    }
}
