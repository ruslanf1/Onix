<?php

namespace App\Http\Controllers\Api\Telegram;

use App\Http\Controllers\Controller;
use Exception;
use Illuminate\Http\Client\Response;
use Illuminate\Support\Facades\Http;

class SendController extends Controller
{
    // Принимает строку. Отправляет данные в Телеграм.
    // Возвращает ответ от Телеграма. В случае ошибки выбрасывает исключение.
    /**
     * @param $data
     * @return Response
     * @throws Exception
     */
    public static function telegram($data): Response {
        try {
            $tgToken = config('app.amo.telegram_token');
            $chatId = config('app.amo.chat_id');

            return Http::post('https://api.telegram.org/bot' . $tgToken . '/' . 'sendMessage', [
                'chat_id' => $chatId,
                'text' => $data,
                'parse_mode' => 'HTML',
            ]);
        } catch (Exception $e) {
            throw new Exception($e);
        }
    }
}
