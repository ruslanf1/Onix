<?php

namespace App\Http\Controllers\Amo\Elements;

use App\Http\Controllers\Amo\Query\Elements\ElementsController;
use App\Http\Controllers\Controller;
use Exception;

class UsersController extends Controller
{
    /**
     * Запрашивает id и имена пользователей. Ограничение в 200 пользователей.
     * Возвращает массив. В случае ошибки выбрасывает исключение.
     *
     * @return array
     * @throws Exception
     */
    public static function getUsers(): array {
        try {
            $getSet = [
                'method' => 'users',
                'limit' => 200,
            ];
            $result = [];
            $users = ElementsController::eventQuery(1, $getSet);
            foreach ($users->_embedded->users as $user) {
                $result[] = ['id' => $user->id, 'name' => $user->name];
            }
            return $result;
        } catch (Exception $e) {
            throw new Exception($e);
        }
    }
}
