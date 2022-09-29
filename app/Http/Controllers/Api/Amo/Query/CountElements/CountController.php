<?php

namespace App\Http\Controllers\Api\Amo\Query\CountElements;

use App\Http\Controllers\Api\Amo\Query\Elements\ElementsController;
use App\Http\Controllers\Controller;
use Exception;

class CountController extends Controller
{
    // Принимает параметры фильтрации. Считает количество сущностей на всех страницах.
    // Возвращает количество сущностей. В случае ошибки выбрасывает исключение.
    /**
     * @param $getSet
     * @return int
     * @throws Exception
     */
    public function countElement($getSet): int {
        try {
            $method = str_replace('"', '', $getSet['method']);
            $result = 0;

            for ($page = 1; $array = ElementsController::eventQuery($page, $getSet); ++$page) {
                $result += count($array->_embedded->$method);
            }
            return $result;
        } catch (Exception $e) {
            throw new Exception($e);
        }
    }
}
