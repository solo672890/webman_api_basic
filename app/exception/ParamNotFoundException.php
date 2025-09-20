<?php
/**
 * UserServer: 木鱼
 * Date: 2023-02-22 18:40
 * Ps:
 */

namespace app\exception;


use app\exception\src\Exception\BaseException;

class ParamNotFoundException extends BaseException{
    /**
     * @var int
     */
    public int $statusCode = 200;

    /**
     * @var string
     */
    public string $errorMessage = '不存在';

    public int $errorCode=0;
}
