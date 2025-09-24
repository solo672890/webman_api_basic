<?php
/**
 * This file is part of webman.
 *
 * Licensed under The MIT License
 * For full copyright and license information, please see the MIT-LICENSE.txt
 * Redistributions of files must retain the above copyright notice.
 *
 * @author    walkor<walkor@workerman.net>
 * @copyright walkor<walkor@workerman.net>
 * @link      http://www.workerman.net/
 * @license   http://www.opensource.org/licenses/mit-license.php MIT License
 */

use Webman\Route;


$POSTt=['POST','OPTIONS'];
$GETt=['GET','OPTIONS'];
Route::group('/v1/', function () use($POSTt,$GETt){
    Route::add($GETt,'index',[\app\controller\api\v1\IndexController::class,'index']);
    Route::add($GETt,'testException',[\app\controller\api\v1\IndexController::class,'testException']);
    Route::add($GETt,'testException1',[\app\controller\api\v1\IndexController::class,'testException1']);
    Route::any('ping',[\app\controller\api\v1\IndexController::class,'ping']);

})->middleware([\app\middleware\LimiterMiddleware::class]);



Route::fallback(function(){
    return view('404', ['error' => 'some error'])->withStatus(404);
});
Route::disableDefaultRoute();






