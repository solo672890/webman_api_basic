<?php

namespace app\extends\task\factory;

interface TaskEventInterface {


    /**
     * @param $post
     * @return bool|array
     * false The rep does not return
     * data Represents the data you want to return
     */
    public function taskHandler($post):bool|array;
}