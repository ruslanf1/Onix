<?php

namespace App\Http\Controllers\Api\Amo\Authorization;

use App\Http\Controllers\Controller;
use App\Models\Access;
use Exception;

class GetController extends Controller
{
    // Запрашивает из БД токен доступа. Проверяет время его создания.
    // В случае если токен доступа истек, вызывает функция обновления, иначе возвращает токен доступа.
    /**
     * @return mixed
     * @throws Exception
     */
    public static function getToken(): mixed {
        try {
            $amo = Access::all()->last();
            if (isset($amo->expires_in) && $amo->expires_in <= time()) {
                return EditController::editToken($amo->refresh_token);
            } else {
                return $amo->access_token;
            }
        } catch (Exception $e) {
            throw new Exception($e);
        }
    }
}
