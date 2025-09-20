<?php

namespace app\exception;

use app\exception\src\Exception\BaseException;

class ImgHttpException extends BaseException
{
    /**
     * @var int
     */
    public $statusCode = 200;

    /**
     * @var string
     */
    public $errorMessage = '图片上传错误';

    public $errorCode = 22000;
}