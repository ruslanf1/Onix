<?php

namespace App\Http\Controllers\Amo\Query\CountElements;

use App\Http\Controllers\Amo\Query\Elements\ElementsController;
use App\Http\Controllers\Controller;
use Exception;

class FilterAndCountController extends Controller
{
    /**
     * Принимает параметры фильтрации.
     * Делает дополнительную фильтрацию по времени создания и считает количество сущностей на всех страницах.
     * Возвращает количество сущностей. В случае ошибки выбрасывает исключение.
     *
     * @param $getSet
     * @return int
     * @throws Exception
     */
    public function filterCountElement($getSet): int {
        try {
            $method = str_replace('"', '', $getSet['method']);
            $result = 0;

            for ($page = 1; $array = ElementsController::eventQuery($page, $getSet); ++$page) {
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
}
