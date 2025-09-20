<?php
/**
 * @desc BaseException
 * @author Tinywan(ShaoBo Wan)
 * @email 756684177@qq.com
 * @date 2022/3/6 14:14
 */

declare(strict_types=1);

namespace app\exception\src\Exception;

class BaseException extends \Exception
{
    /**
     * HTTP Response Status Code.
     * http code
     */
    public int $statusCode = 400;

    /**
     * HTTP Response Header.
     */
    public array $header = [];
    /**
     * Business data.
     * @var array|mixed
     */
    public array $data = [];
    /**
     * Business Error code.
     * 自定义code
     * @var int|mixed
     */
    public int $errorCode = 0;

    /**
     * Business Error message.
     * @var string
     */
    public string $errorMessage = 'The requested resource is not available or not exists';



    /**
     * Detail ReportLog Error message.
     * @var string
     */
    public string $error = '';

    /**
     * @param string $errorMessage 错误信息
     * @param int $errorCode 自定义错误码
     * @param int $statusCode  http码
     * @param array $data 要返回的扩展数据
     */
    public function __construct(string $errorMessage = '', int $errorCode=0, array $data=[],int $statusCode=0)
    {
        parent::__construct($errorMessage, $this->statusCode);
        if($data){
            $this->data = $data;
        }
        if($errorMessage){
            $this->errorMessage = $errorMessage;
        }
        if($errorCode){
            $this->errorCode = $errorCode;
        }
        if($statusCode){
            $this->statusCode = $statusCode;
        }


        if($this->errorCode !== 1){
            $this->errorMessage .= '-'.$this->errorCode;
        }
    }
}
