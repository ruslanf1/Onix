<?php

namespace App\Http\Controllers\Api\Amo\Authorization;

use App\Http\Controllers\Controller;
use App\Models\Access;
use Exception;
use Illuminate\Support\Facades\Http;

class EditController extends Controller
{
    // Принимает токен обнвления. Обновляет токен доступа по токену обновления.
    // Возвращает токен доступа. В случае ошибки выкидвает исключение.
    /**
     * @param $refresh_token
     * @return mixed
     * @throws Exception
     */
    public static function editToken($refresh_token): mixed {
        try {
            $data = [
                'client_id' => config('app.amo.client_id'),
                'client_secret' => config('app.amo.client_secret'),
                'grant_type' => 'refresh_token',
                'refresh_token' => $refresh_token,
                'redirect_uri' => config('app.amo.client_redirect'),
            ];
            $subDomain = config('app.amo.sub_domain');

            $response = json_decode(Http::post("https://{$subDomain}.amocrm.ru/oauth2/access_token", $data));

            if (isset($response->access_token) && $response->access_token != '' &&
                isset($response->refresh_token) && $response->refresh_token != '' &&
                isset($response->expires_in) && $response->expires_in > 0) {
                $amoModel = Access::all()->last();
                $amoModel->update([
                    'access_token' => $response->access_token,
                    'refresh_token' => $response->refresh_token,
                    'expires_in' => time() + $response->expires_in,
                ]);
                return $response->access_token;
            } else {
                return throw new Exception('Код доступа не обновлен', 404);
            }
        } catch (Exception $e) {
            throw new Exception($e);
        }
    }
}
