<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Amo\Elements\EventsController;
use App\Http\Controllers\Amo\Elements\FinishTasksController;
use App\Http\Controllers\Amo\Elements\LeadsColdController;
use App\Http\Controllers\Amo\Elements\LeadsOfferController;
use App\Http\Controllers\Amo\Elements\NewTasksController;
use App\Http\Controllers\Telegram\CheckController;
use Exception;
use Illuminate\Http\JsonResponse;

class MainController extends Controller
{
    /**
     * Запрашивает по каждому пользователю количество сущностей. Составляет сообщение и отправляет в Телеграм.
     * Возвращает строку. В случае ошибок ловит исключения и возвращает ответ.
     *
     * @return JsonResponse|bool
     */
    public function __invoke(): JsonResponse|bool {
        try {
            $data = '';
            $users = [
                [
                    'name' => 'Виктор Жиляев',
                    'id' => 7326229,
                ],
                [
                    'name' => 'Константин',
                    'id' => 8353078,
                ]
            ];
            foreach ($users as $user) {
                $events = (new EventsController)->getEvents($user['id']);
                $newTasks = (new NewTasksController)->getNewTasks($user['id']);
                $finishTasks = (new FinishTasksController)->getFinishTasks($user['id']);
                $leadsOffer = (new LeadsOfferController)->getLeadsOffer($user['id']);
                $leadsCold = (new LeadsColdController)->getLeadsCold($user['id']);

                $data .=
                    "<b>Менеджер: " . $user['name'] . "</b>\n\n" .
                    "Кол-во событий: " . $events . "\n" .
                    "Кол-во назначенных встреч: " . $newTasks . "\n" .
                    "Кол-во проведённых встреч: " . $finishTasks . "\n" .
                    "Кол-во отправленных КП: " . $leadsOffer . "\n" .
                    "Обработано из холодной базы: " . $leadsCold . "\n\n\n";
            }
            return CheckController::checkTelegram($data);
        } catch (Exception $e) {
            return response()->json([
                'code' => $e->getCode(),
                'data' => $e->getMessage()
            ], 404);
        }
    }
}
