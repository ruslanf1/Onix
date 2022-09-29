<?php

namespace App\Http\Controllers\Api\Amo\Query\Elements;

use App\Http\Controllers\Api\Amo\Authorization\GetController;
use App\Http\Controllers\Controller;
use Exception;
use Illuminate\Support\Facades\Http;

class ElementsController extends Controller
{
    // Принимает номер страницы и параметры фильтрации. Делает запрос на AmoCRM.
    // Возвращает объект с элементами запроса. В случае ошибки выбрасывает исключение.
    /**
     * @param $page
     * @param $getSet
     * @return mixed
     * @throws Exception
     */
    public static function eventQuery($page, $getSet): mixed {
        try {
            $accessToken = GetController::getToken();
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
}
