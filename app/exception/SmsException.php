<?php
/**
 * UserServer: 木鱼
 * Date: 2023-02-19 4:22
 * Ps:
 */

namespace app\exception;


use app\exception\src\Exception\BaseException;

class SmsException extends BaseException
{
    /**
     * @var int
     */
    public int $statusCode = 200;

    /**
     * @var string
     */
    public string $errorMessage = '短信发送失败,请联系客服';

    public int $errorCode=0;


}
