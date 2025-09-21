<?php
namespace app\extends\task\factory;

use app\exception\DangersBugHttpException;
use Workerman\Connection\AsyncTcpConnection;

/**
 * Asynchronous delivery of data
 * @method bool send(array $data,callable $callable=null);
 */
class TaskClient {

    /**
     * @var TaskClient
     */
    protected static $instance = null;
    protected static AsyncTcpConnection $taskConnection;
    protected $taskHandlerClass;

    /**
     * @return TaskClient
     */
    public static function instance(): ?TaskClient {
        if (!static::$instance) {
            self::$instance = new self();

        }
        return static::$instance;
    }




    /**
     * set task handler class.
     * @param string $name
     * @return TaskClient
     */
    public static  function setHandlerClass(string $taskClass=''): TaskClient {

        if(!$taskClass){
            throw new DangersBugHttpException('$taskClass cannot empty');
        }


        static::instance()->taskHandlerClass=$taskClass;

        return static::$instance;
    }


    /**
     * @param string $name
     * @param array $arguments
     * @return mixed
     */
    public  function __call(string $name, array  $post=[]) {
        if(!$this->taskHandlerClass){
            throw new DangersBugHttpException('$taskHandlerClass cannot empty');
        }

        if(!isset($post[0]) || empty($post[0])){
            throw new DangersBugHttpException('Parameters1 are missing,its must be a array');
        }
        $callback=null;
        if(isset($post[1]) && is_callable($post[1]))  {
            $callback=$post[1];
        }

        $postData['method']=$this->taskHandlerClass;
        $postData['data']=$post[0];
        $taskConnection= new AsyncTcpConnection('text://127.0.0.1:12345');;
        $taskConnection->{$name}(json_encode($postData));
        $taskConnection->onMessage=function (AsyncTcpConnection $taskConnection,$result)use($callback){
            if($callback)$callback($result);
            $taskConnection->close();
        };
        $taskConnection->connect();
        $this->taskHandlerClass='';
    }
}
