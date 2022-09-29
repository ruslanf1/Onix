<?php

namespace App\Http\Controllers\Api\Amo\Elements;

use App\Http\Controllers\Api\Amo\Query\CountElements\FilterAndCountController;
use App\Http\Controllers\Controller;
use Exception;

class FinishTasksController extends Controller
{
    // Принимает id пользователя. Содержит параметры фильтрации.
    // Возвращает колличество проведенных встреч. В случае ошибки выбрасывает исключение.
    /**
     * @param $userId
     * @return int
     * @throws Exception
     */
    public function getFinishTasks($userId): int {
        try {
            $getSet = [
                'method' => 'tasks',
                'limit' => 250,
                'filter[is_completed]' => 1,
                'filter[task_type]' => 2,
                'filter[responsible_user_id]' => $userId,
            ];
            return (new FilterAndCountController)->filterCountElement($getSet);
        } catch (Exception $e) {
            throw new Exception($e);
        }
    }
}
