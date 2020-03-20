<?php
/**
 * [入口文件]
 *
 *      1. 实例化应用框架
 *      2. 通过路由门面类的静态方法执行路由配置。具体执行流程查看门面相应的内容即可
 *      3. 绑定内核契约的接口到实现
 *      4. 解析内核契约对象接口（即获取实现契约接口的具体实现类的实例化对象）
 *      5. 调用请求对象设置请求地址到请求对象的属性中，并将请求对象返回的方法
 *      6. 将请求对象传入到内核的处理请求方法中
 *
 *
 *
 * @Author  leeprince:2020-03-11 23:24
 */

require_once 'Container.php';
require_once 'Application.php';
require_once 'Contracts.php';
require_once 'Kernel.php';
require_once 'Service.php';
require_once 'Route.php';
require_once 'Request.php';
require_once 'Facade.php';
require_once 'MyFacade.php';
require_once 'LeeprinceController.php';
require_once 'Helper.php';

$app = new Application();

// 注意web 服务器配置支持 pathinfo -- http://p.my_minilaravel.com/go
// 路由配置到：控制器@方法或者闭包
// 控制器@方法
$route = RouteFacade::get('go', 'LeeprinceController@index');
// 闭包
// $route = RouteFacade::get('go', function() {
    // dump('route config is Closure');
// }); // 注意web 服务器配置支持 pathinfo -- http://p.my_minilaravel.com/go

$app->bind(KernelContracts::class, Kernel::class);
$kernel = $app->make(KernelContracts::class, ['app' => $app]);

/** 设置请求地址到请求对象的属性中，并将请求对象传入到内核的处理请求方法handle 中。有2种方案： */
// 方案1：通过请求门面的方式执行请求对象的某个方法
// $kernel->handle(RequestFacade::capture());
// 方案2：通过容器解析请求对象，在请求对象的构造函数中设置请求地址到请求对象的属性
$kernel->handle($app->make('request'));


/** ———————————————————————————————以下是测试代码——————————————————————————————————————— */
/** 测试闭包 */
// $app->bind('test', function() {
//     echo "1\n";
// });
// $app->make('test');
//
// /** 测试构造函数 */
// $app->bind('ref', TestReflection::class);
// dump($app->make('ref', ['a1' => 'va1'])->test());
//
// /** 测试构造函数 */
// $app->bind('testnew', new TestNew);
// dump($app->make('testnew')->test());

/** ———————————————————————————————以下是测试代码 - end——————————————————————————————————————— */

