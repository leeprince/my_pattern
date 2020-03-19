<?php
/**
 * [入口文件]
 *
 * @Author  leeprince:2020-03-19 14:42
 */

// 测试 vendor/leeprince 组件
require_once __DIR__.'/vendor/autoload.php';

use Minilaravel\Foundation\Application;
use Minilaravel\Support\Facades\RouteFacade;
use Minilaravel\Log\Mysql\MysqlLog;

$app = new Application();

// var_dump($app->make(MysqlLog::class)->write());

$route = RouteFacade::get('go', 'Minilaravel\Controller\LeeprinceController@index');
// 闭包
// $route = RouteFacade::get('go', function() {
// dump('route config is Closure');
// }); // 注意web 服务器配置支持 pathinfo -- http://p.my_minilaravel.com/go

$app->bind(Minilaravel\Contracts\Http\KernelContracts::class, \Minilaravel\Foundation\Http\Kernel::class);
$kernel = $app->make(Minilaravel\Contracts\Http\KernelContracts::class, ['app' => $app]);

/** 设置请求地址到请求对象的属性中，并将请求对象传入到内核的处理请求方法handle 中。有2种方案： */
// 方案1：通过请求门面的方式执行请求对象的某个方法
// $kernel->handle(RequestFacade::capture());
// 方案2：通过容器解析请求对象，在请求对象的构造函数中设置请求地址到请求对象的属性
$kernel->handle($app->make('request'));