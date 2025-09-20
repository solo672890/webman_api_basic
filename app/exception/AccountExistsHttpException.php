<?php

namespace app\exception;

use app\exception\src\Exception\BaseException;

class AccountExistsHttpException extends BaseException
{
    /**
     * @var int
     */
    public int $statusCode = 200;

    /**
     * @var string
     */
    public string $errorMessage = '该账号已被注册';

    public int $errorCode = 10008;
}