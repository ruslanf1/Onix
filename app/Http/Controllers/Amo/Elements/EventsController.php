<?php

namespace App\Http\Controllers\Amo\Elements;

use App\Http\Controllers\Amo\Query\CountElements\CountController;
use App\Http\Controllers\Controller;
use Exception;

class EventsController extends Controller
{
    /**
     * Принимает id пользователя. Содержит параметры фильтрации.
     * Возвращает колличество событий. В случае ошибки выбрасывает исключение.
     *
     * @param $userId
     * @return int
     * @throws Exception
     */
    public function getEvents($userId): int {
        try {
            $getSet = [
                'method' => 'events',
                'limit' => 100,
                'filter[created_by]' => $userId,
            ];
            return (new CountController)->countElement($getSet);
        } catch (Exception $e) {
            throw new Exception($e);
        }
    }
}
