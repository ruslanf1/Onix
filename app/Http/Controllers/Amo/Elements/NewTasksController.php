<?php

namespace App\Http\Controllers\Amo\Elements;

use App\Http\Controllers\Amo\Query\CountElements\FilterAndCountController;
use App\Http\Controllers\MainController;
use Exception;

class NewTasksController extends MainController
{
    /**
     * Принимает id пользователя. Содержит параметры фильтрации.
     * Возвращает колличество назначенных встреч. В случае ошибки выбрасывает исключение.
     *
     * @param $userId
     * @return int
     * @throws Exception
     */
    public function getNewTasks($userId): int {
        try {
            $getSet = [
                'method' => 'tasks',
                'limit' => 250,
                'filter[task_type]' => 2,
                'filter[responsible_user_id]' => $userId,
            ];
            return (new FilterAndCountController)->filterCountElement($getSet);
        } catch (Exception $e) {
            throw new Exception($e);
        }
    }
}
