<?php

namespace App\Http\Controllers\Api;

use Exception;

class MainController extends BaseController
{
    public function main()
    {
        try {
            $data = '';
            $users = $this->getUsers();

            foreach ($users as $user) {
                $events = $this->getEvents($user['id']);
                $newTasks = $this->getNewTasks($user['id']);
                $finishTasks = $this->getFinishTasks($user['id']);
                $leadsOffer = $this->getLeadsOffer($user['id']);
                $leadsCold = $this->getLeadsCold($user['id']);

                $data .=
                    "<b>Менеджер: " . $user['name'] . "</b>\n\n" .
                    "Кол-во событий: " . $events . "\n" .
                    "Кол-во назначенных встреч: " . $newTasks . "\n" .
                    "Кол-во проведённых встреч: " . $finishTasks . "\n" .
                    "Кол-во отправленных КП: " . $leadsOffer . "\n" .
                    "Обработано из холодной базы: " . $leadsCold . "\n\n\n";
            }
            $this->service->checkAndSendTelegram($data);
            return 'Сообщения отправлены';
        } catch (Exception $e) {
            return response()->json([
                'code' => $e->getCode(),
                'data' => $e->getMessage()
            ], 404);
        }

    }

    public function getUsers()
    {
        try {
            $getSet = [
                'method' => 'users',
                'limit' => 200,
            ];
            $result = [];
            $users = $this->service->eventQuery(1, $getSet);
            foreach ($users->_embedded->users as $user) {
                $result[] = ['id' => $user->id, 'name' => $user->name];
            }
            return $result;
        } catch (Exception $e) {
            throw new Exception($e);
        }
    }

    public function getEvents($userId): int
    {
        $getSet = [
            'method' => 'events',
            'limit' => 100,
            'filter[created_by]' => $userId,
        ];
        return $this->service->countElement($getSet);
    }

    public function getNewTasks($userId): int
    {
        $getSet = [
            'method' => 'tasks',
            'limit' => 250,
            'filter[is_completed]' => 0,
            'filter[task_type]' => 2,
            'filter[responsible_user_id]' => $userId,

        ];
        return $this->service->filterCountElement($getSet);
    }

    public function getFinishTasks($userId): int
    {
        $getSet = [
            'method' => 'tasks',
            'limit' => 250,
            'filter[is_completed]' => 1,
            'filter[task_type]' => 2,
            'filter[responsible_user_id]' => $userId,

        ];
        return $this->service->filterCountElement($getSet);
    }

    public function getLeadsOffer($userId): int
    {
        $getSet = [
            'method' => 'leads',
            'limit' => 250,
            'filter[responsible_user_id]' => $userId,
            'filter[pipeline_id]' => 4542283,
            'filter[status_id]' => 41893999,
        ];
        return $this->service->countElement($getSet);
    }

    public function getLeadsCold($userId): int
    {
        $getSet = [
            'method' => 'events',
            'limit' => 250,
            'filter[created_by]' => $userId,
            'filter[entity]' => 'lead',
            'filter[type]' => 'lead_status_changed',
            'filter[before_pipeline_id]' => 4575370,
            'filter[before_status_id]' => 42130510,
        ];
        return $this->service->countElement($getSet);
    }
}
