<?php

namespace app\extends\rpc\src;

/**
 * @notes
 * @author ruby
 * 2025/1/27 05:24
 */
class Error implements \JsonSerializable {
    protected $code = 0;

    /**
     * @var string
     */
    protected $message = '';

    /**
     * @var mixed
     */
    protected $data;

    /**
     * @param int    $code
     * @param string $message
     * @param mixed  $data
     *
     * @return Error
     */
    public static function make(int $code, string $message, $data = null): self
    {
        $instance = new static();

        $instance->code    = $code;
        $instance->message = $message;
        $instance->data    = $data;

        return $instance;
    }

    /**
     * @return int
     */
    public function getCode(): int
    {
        return $this->code;
    }

    /**
     * @return string
     */
    public function getMessage(): string
    {
        return $this->message;
    }

    /**
     * @return mixed
     */
    public function getData()
    {
        return $this->data;
    }

    /**
     * @return array
     */
    public function jsonSerialize(): array
    {
        return [
            'code'    => $this->code,
            'message' => $this->message,
            'data'    => $this->data,
        ];
    }
}