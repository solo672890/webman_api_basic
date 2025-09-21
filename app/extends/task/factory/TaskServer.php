<?php
declare(strict_types=1);
namespace app\extends\task\factory;


use Workerman\Worker;

class TaskServer {


    public function onWorkerStart(Worker $worker) {}

    public function onMessage($connection, $taskData) {
        $taskData=json_decode($taskData);
        $method=(new $taskData->method);
        if($method instanceof TaskEventInterface){
            $data=$method->taskHandler($taskData->data);
            if($data){
                $connection->send(json_encode($data));
            }
        }
    }

    public function onConnect($connection) {

    }

    public function onClose() {}




}