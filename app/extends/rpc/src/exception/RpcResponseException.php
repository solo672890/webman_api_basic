<?php

namespace app\extends\rpc\src\exception;

use app\extends\rpc\src\Error;

/**
 * @notes
 * @author ruby
 * 2025/1/27 05:26
 */
class RpcResponseException extends \Exception
{
    protected $error;

    public function __construct(Error $error)
    {
        parent::__construct($error->getMessage(), $error->getCode());
        $this->error = $error;
    }

    public function getError(): Error
    {
        return $this->error;
    }
}