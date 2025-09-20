<?php

namespace app\exception;

use app\exception\src\Exception\BaseException;

class ValidateHttpException extends BaseException {
    public int $statusCode = 200;

    /**
     * @var string
     */
    public string $errorMessage = '请求参数错误';


    public int $errorCode = 20000;
}