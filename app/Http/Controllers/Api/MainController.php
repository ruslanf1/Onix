<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Api\Amo\Elements\EventsController;
use App\Http\Controllers\Api\Amo\Elements\FinishTasksController;
use App\Http\Controllers\Api\Amo\Elements\LeadsColdController;
use App\Http\Controllers\Api\Amo\Elements\LeadsOfferController;
use App\Http\Controllers\Api\Amo\Elements\NewTasksController;
use App\Http\Controllers\Api\Amo\Elements\UsersController;
use App\Http\Controllers\Api\Telegram\CheckController;
use App\Http\Controllers\Controller;
use Exception;
use Illuminate\Http\JsonResponse;

class MainController extends Controller
{
    // Запрашивает по каждому пользователю количество сущностей. Составляет сообщение и отправляет в Телеграм.
    // Возвращает строку. В случае ошибок ловит исключения и возвращает ответ.
    /**
     * @return JsonResponse|string
     */
    public function main(): JsonResponse|string {
        try {
            $data = '';
            $users = UsersController::getUsers();

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
            CheckController::checkTelegram($data);
            return 'Сообщения отправлены';
        } catch (Exception $e) {
            return response()->json([
                'code' => $e->getCode(),
                'data' => $e->getMessage()
            ], 404);
        }
    }
}
