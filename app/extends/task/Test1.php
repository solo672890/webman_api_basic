<?php

namespace app\extends\task;

use app\extends\task\factory\TaskEventInterface;

class Test1 implements TaskEventInterface {

    public function taskHandler($post): bool|array {
        sleep(10);
        var_dump('收到请求task1');


        return ['status'=>false];
    }


}