<?php

namespace app\exception;

use app\exception\src\Exception\BaseException;

/**
 * @notes
 * @author ruby
 * 2025/4/20 16:32
 */
class ConventionException extends BaseException
{
    /**
     * @var int
     */
    public int $statusCode = 200;

    /**
     * @var string
     */
    public string $errorMessage = '常规错误';

    public int $errorCode=0;


}