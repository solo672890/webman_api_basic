<?php
/**
 * @desc ExceptionHandler
 * @author Tinywan(ShaoBo Wan)
 * @email 756684177@qq.com
 * @date 2022/3/6 14:08
 */
declare(strict_types=1);

namespace app\exception\src;

use app\exception\src\Exception\BaseException;
use app\exception\src\Exception\ServerErrorHttpException;
use FastRoute\BadRouteException;
use InvalidArgumentException;
use Throwable;
use Webman\Exception\ExceptionHandler;
use Webman\Http\Request;
use Webman\Http\Response;

class Handler extends ExceptionHandler {
    /**
     * 不需要记录错误日志.
     *
     * @var string[]
     */
    public $dontReport = [];

    /**
     * HTTP Response Status Code.
     *
     * @var array
     */
    public $statusCode = 200;

    /**
     * HTTP Response Header.
     *
     * @var array
     */
    public $header = [];

    /**
     * Business Error code.
     *
     * @var int
     */
    public $errorCode = 0;

    /**
     * Business Error message.
     *
     * @var string
     */
    public $errorMessage = 'no error';
    /**
     * ReportLog Error message.
     *
     * @var string
     */
    public $error = 'no error';
    /**
     * 响应结果数据.
     *
     * @var array
     */
    protected $responseData = [];
    /**
     * config下的配置.
     *
     * @var array
     */
    protected $config = [];

    /**
     * @param Throwable $exception
     */
    public function report(Throwable $exception) {
        $this->dontReport = config('exception.dont_report', []);
        $propertyHas = property_exists($exception, 'statusCode');
        if ($propertyHas && $exception->statusCode !== 500) {
            $this->dontReport[] = get_class($exception);
        }

        if ($this->shouldntReport($exception)) {
            return;
        }
        $this->writeLog($exception);

    }

    protected function shouldntReport(Throwable $e): bool {
        foreach ($this->dontReport as $type) {
            if ($e instanceof $type) {
                return true;
            }
        }
        return false;
    }

    /**
     * @param Request $request
     * @param Throwable $exception
     * @return Response
     */
    public function render(Request $request, Throwable $exception): Response {
        $this->addRequestInfoToResponse($request);
        $this->solveAllException($exception);
        $this->addDebugInfoToResponse($exception);
        $this->triggerDingDingNotifyEvent($exception);
        $this->triggerTraceEvent($exception);

        return $this->buildResponse();
    }

    /**
     * 请求的相关信息.
     *
     * @param Request $request
     * @return void
     */
    protected function addRequestInfoToResponse(Request $request): void {
        $this->responseData = array_merge($this->responseData, ['domain' => $request->host(), 'request_url' => $request->method() . ' ' . $request->uri(), 'timestamp' => date('Y-m-d H:i:s'), 'client_ip' => $request->getRealIp(), 'request_param' => $request->all(),]);
    }

    /**
     * 处理异常数据.
     *
     * @param Throwable $e
     */
    protected function solveAllException(Throwable $e) {
        if ($e instanceof BaseException) {
            $this->statusCode = $e->statusCode;
            $this->header = $e->header;
            $this->errorCode = $e->errorCode;
            $this->errorMessage = $e->errorMessage;
            $this->error = $e->error;
            if (isset($e->data)) {
                $this->responseData = array_merge($this->responseData, $e->data);
            }
            if (!$e instanceof ServerErrorHttpException) {
                return;
            }
        }
        $this->solveExtraException($e);
    }

    /**
     * @desc: 处理扩展的异常
     * @param Throwable $e
     * @author Tinywan(ShaoBo Wan)
     */
    protected function solveExtraException(Throwable $e): void {
//        $status = $this->config['status'];

        $this->errorMessage = 'sorry,we are make a mistake!';
        if (config('app.debug', false)) {
            $this->errorMessage = $e->getMessage();
        }

        if ($e instanceof BadRouteException) {
            $this->statusCode = 404;
        } elseif ($e instanceof InvalidArgumentException) {
            $this->statusCode = 415;
            $this->errorMessage = $e->getMessage();
        } elseif ($e instanceof ServerErrorHttpException) {
            $this->statusCode = 500;
        } else {
            $this->statusCode = 200;
            $this->error = $e->getMessage();
        }
    }

    /**
     * 调试模式：错误处理器会显示异常以及详细的函数调用栈和源代码行数来帮助调试，将返回详细的异常信息。
     * @param Throwable $e
     * @return void
     */
    protected function addDebugInfoToResponse(Throwable $e): void {
        if (config('app.debug', false)) {
            $this->responseData['error_trace'] = array_slice(explode("\n", $e->getTraceAsString()), 0, 4);
            $this->responseData['file'] = $e->getFile();
            $this->responseData['line'] = $e->getLine();
        }

    }

    /**
     * 触发通知事件.
     *
     * @param Throwable $e
     * @return void
     */
    protected function triggerDingDingNotifyEvent(Throwable $e): void {
//        if (!$this->shouldntReport($e) && $this->config['event_trigger']['enable'] ?? false) {
//            $responseData = $this->responseData;
//            $responseData['message'] = $this->errorMessage;
//            $responseData['error'] = $this->error;
//            $responseData['file'] = $e->getFile();
//            $responseData['line'] = $e->getLine();
//            DingTalkRobotEvent::dingTalkRobot($responseData, $this->config);
//        }
    }

    /**
     * 触发 trace 事件.
     *
     * @param Throwable $e
     * @return void
     */
    protected function triggerTraceEvent(Throwable $e): void {
        if (isset(request()->tracer) && isset(request()->rootSpan)) {
            $samplingFlags = request()->rootSpan->getContext();
            $this->header['Trace-Id'] = $samplingFlags->getTraceId();
            $exceptionSpan = request()->tracer->newChild($samplingFlags);
            $exceptionSpan->setName('exception');
            $exceptionSpan->start();
            $exceptionSpan->tag('error.code', (string)$this->errorCode);
            $value = ['event' => 'error', 'message' => $this->errorMessage, 'stack' => 'Exception:' . $e->getFile() . '|' . $e->getLine(),];
            $exceptionSpan->annotate(json_encode($value));
            $exceptionSpan->finish();
        }
    }

    /**
     * 构造 Response.
     *
     * @return Response
     */
    protected function buildResponse(): Response {
        $responseBody = ['code' => $this->errorCode, 'msg' => $this->errorMessage, 'data' => $this->responseData];
        $header = array_merge(['Content-Type' => 'application/json;charset=utf-8'], $this->header);
        return new Response($this->statusCode, $header, json_encode($responseBody));
    }

    protected function writeLog(Throwable $exception): void {
        $requestParams = [];
        $line = "\n-------------------------------------------------------------------\n";
        if (empty(request())) {
            $logInfo = $line;
        } else {
            $logInfo = '  [request_IP]:' . request()->getRealIp() .'  [visit_URL]:'. ltrim(request()->fullUrl(), '/'). $line;
            $requestParams = request()->all();
        }
        $error_message=$exception->getMessage();
        if(!$error_message && property_exists($exception,'errorMessage')){
            $error_message=$exception->errorMessage;
        }
        $logInfo.="error_message : ".$error_message."\n";
        $tempArr = array_filter([
                'request_params' => $requestParams,
                'exception' => ['file' => $exception->getFile(), 'line' => $exception->getLine()],
                'error_trace' => array_slice(explode("\n", $exception->getTraceAsString()), 0, 4),
            ]
        );

        $logInfo .= $tempArr ? json_encode($tempArr) . "\n" : '';
        $this->logger->error($logInfo);
    }
}
