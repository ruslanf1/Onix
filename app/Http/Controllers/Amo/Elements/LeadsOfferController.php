<?php

namespace App\Http\Controllers\Amo\Elements;

use App\Http\Controllers\Amo\Query\CountElements\CountController;
use App\Http\Controllers\Controller;
use Exception;

class LeadsOfferController extends Controller
{
    /**
     * Принимает id пользователя. Содержит параметры фильтрации.
     * Возвращает колличество сделок, перешедших в этап "КП отправлено". В случае ошибки выбрасывает исключение.
     *
     * @param $userId
     * @return int
     * @throws Exception
     */
    public function getLeadsOffer($userId): int {
        try {
            $getSet = [
                'method' => 'leads',
                'limit' => 250,
                'filter[responsible_user_id]' => $userId,
                'filter[pipeline_id]' => 4542283,
                'filter[status_id]' => 41893999,
            ];
            return (new CountController)->countElement($getSet);
        } catch (Exception $e) {
            throw new Exception($e);
        }
    }
}
