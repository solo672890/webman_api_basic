<?php

namespace app\middleware;

use app\exception\UnauthorizedHttpException;
use app\extends\jwt\JwtToken;
use app\extends\jwt\JwtTokenException;
use Webman\Http\Request;
use Webman\Http\Response;
use Webman\MiddlewareInterface;

/**
 * @notes
 * @author ruby
 * 2025/4/20 12:30
 */
class AuthMiddleware implements MiddlewareInterface {
    public function process(Request $request, callable $handler): Response {

        try {
            $request->user=JwtToken::getUser();
        }catch (JwtTokenException $exception){
            if($exception->getCode() === 30002){
                throw new UnauthorizedHttpException();
            }
            throw new JwtTokenException($exception->getMessage(), $exception->getCode());
        }

        return $handler($request);
    }
}
