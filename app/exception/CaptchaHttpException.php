<?php
/**
 * UserServer: 木鱼
 * Date: 2023-02-19 4:22
 * Ps:
 */

namespace app\exception;


use app\exception\src\Exception\BaseException;

class CaptchaHttpException extends BaseException
{
    /**
     * @var int
     */
    public int $statusCode = 200;

    /**
     * @var string
     */
    public string $errorMessage = '验证码错误';

    public int $errorCode=20002;


}
