<?php

namespace App\Services\Api;

use App\Models\Access;
use Exception;
use Illuminate\Support\Facades\Http;

class Service
{
    public function getToken()
    {
        try {
            $amo = Access::all()->last();
            if (isset($amo->expires_in) && $amo->expires_in <= time()) {
                return $this->editToken($amo->refresh_token);
            } else {
                return $amo->access_token;
            }
        } catch (Exception $e) {
            throw new Exception($e);
        }
    }

    public function editToken($refresh_token)
    {
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

    public function eventQuery($page, $getSet)
    {
        try {
            $accessToken = $this->getToken();
            $subDomain = config('app.amo.sub_domain');
            $getSet += [
                'filter[created_at]' => strtotime("today"),
                'page' => $page,
            ];

            $response = Http::withHeaders(["Authorization" => "Bearer " . $accessToken, "Content-Type" => "application/json"])
                ->get("https://{$subDomain}.amocrm.ru/api/v4/{$getSet['method']}", $getSet);
            return json_decode($response);
        } catch (Exception $e) {
            throw new Exception($e);
        }

    }

    public function countElement($getSet): int
    {
        try {
            $method = str_replace('"', '', $getSet['method']);
            $result = 0;

            for ($page = 1; $array = $this->eventQuery($page, $getSet); ++$page) {
                $result += count($array->_embedded->$method);
            }
            return $result;
        } catch (Exception $e) {
            throw new Exception($e);
        }
    }

    public function filterCountElement($getSet): int
    {
        try {
            $method = str_replace('"', '', $getSet['method']);
            $result = 0;

            for ($page = 1; $array = $this->eventQuery($page, $getSet); ++$page) {
                foreach ($array->_embedded->$method as $element) {
                    if ($element->created_at >= strtotime("today")) {
                        $result++;
                    }
                }
            }
            return $result;
        } catch (Exception $e) {
            throw new Exception($e);
        }
    }

    public function checkAndSendTelegram($data)
    {
        try {
            if (mb_strlen($data) <= 4096) {
                return $this->sendTelegram($data);
            } else {
                $cutData = mb_substr($data, 0, 4096);
                $wholeData = mb_strripos($cutData, "\n\n\n", 0);
                $array = mb_str_split($data, $wholeData);
                foreach ($array as $data) {
                    $this->sendTelegram($data);
                }
                return $array;
            }
        } catch (Exception $e) {
            throw new Exception($e);
        }
    }

    public function sendTelegram($data)
    {
        try {
            $tgToken = config('app.amo.telegram_token');
            $chatId = config('app.amo.chat_id');

            return Http::post('https://api.telegram.org/bot' . $tgToken . '/' . 'sendMessage', [
                'chat_id' => $chatId,
                'text' => $data,
            ]);
        } catch (Exception $e) {
            throw new Exception($e);
        }
    }
}




