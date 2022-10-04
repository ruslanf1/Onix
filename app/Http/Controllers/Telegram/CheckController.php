<?php

namespace App\Http\Controllers\Telegram;

use App\Http\Controllers\Controller;
use Exception;
use Illuminate\Http\Client\Response;

class CheckController extends Controller
{
    /**
     * Принимает строку. Проверяет на количество символов, если больше 4096, то разбивает ее и отправляет по частям.
     * Возвращает bool.
     *
     * @param $data
     * @return bool
     * @throws Exception
     */
    public static function checkTelegram($data): bool {
        try {
            if (mb_strlen($data) <= 4096) {
                return SendController::telegram($data);
            } else {
                $cutData = mb_substr($data, 0, 4096);
                $wholeData = mb_strripos($cutData, "\n\n\n", 0);
                $array = mb_str_split($data, $wholeData);
                foreach ($array as $data) {
                    SendController::telegram($data);
                }
                return true;
            }
        } catch (Exception) {
            return false;
        }
    }
}
