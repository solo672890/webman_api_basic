<?php

namespace app\middleware;

use Webman\Http\Request;
use Webman\Http\Response;
use Webman\MiddlewareInterface;

/**
 * @notes
 * @author ruby
 * 2025/4/20 12:30
 */
class CORSMiddleware implements MiddlewareInterface
{
    public function process(Request $request, callable $handler) : Response
    {
        $response = $request->method() == 'OPTIONS' ? response('') : $handler($request);
        $response->withHeaders([
            'Access-Control-Allow-Credentials' => 'true',
            'Access-Control-Allow-Origin' => $request->header('origin', '*'),
            'Access-Control-Allow-Methods' => $request->header('access-control-request-method', '*'),
            'Access-Control-Allow-Headers' => $request->header('access-control-request-headers', '*'),
        ]);

        return $response;
    }
}
