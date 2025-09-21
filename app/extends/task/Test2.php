<?php

namespace app\extends\task;

use app\extends\task\factory\TaskEventInterface;

class Test2  implements TaskEventInterface {

    public function taskHandler($post): bool|array {

        var_dump('收到请求task2');
        sleep(10);

        return ['status'=>false];
    }


}