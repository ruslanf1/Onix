<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Access;
use Exception;
use Illuminate\Support\Facades\Http;

class AuthController extends Controller
{
    public function saveToken() {
        try {
            $data = [
                'client_id' => config('app.amo.client_id'),
                'client_secret' => config('app.amo.client_secret'),
                'grant_type' => 'authorization_code',
                'code' => config('app.amo.client_code'),
                'redirect_uri' => config('app.amo.client_redirect'),
            ];
            $subDomain = config('app.amo.sub_domain');
            $response = json_decode(Http::post("https://{$subDomain}.amocrm.ru/oauth2/access_token", $data));

            if (isset($response->access_token) && $response->access_token != '' &&
                isset($response->refresh_token) && $response->refresh_token != '' &&
                isset($response->expires_in) && $response->expires_in > 0) {

                Access::create([
                    'access_token' => $response->access_token,
                    'refresh_token' => $response->refresh_token,
                    'expires_in' => time() + $response->expires_in,
                ]);

                return $response->access_token;
            } else {
                return throw new Exception('Код доступа не сохранен', 404);
            }
        } catch (Exception $e) {
            throw new Exception($e);
        }
    }
}
