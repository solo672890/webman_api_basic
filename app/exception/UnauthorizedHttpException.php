<?php

namespace app\exception;

use app\exception\src\Exception\BaseException;

class UnauthorizedHttpException extends BaseException {
    /**
     * @var int
     */
    public int $statusCode = 200;

    /**
     * @var string
     */
    public string $errorMessage = '登录校验已失效, 请重新登录';

    public int $errorCode=30002;
}