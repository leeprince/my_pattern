<?php
/**
 * [管道（包洋葱的概念）
 *      在laravel 中是将要执行的控制器的方法做为洋葱心，
 *      而多个中间件（中间件提供了一种方便的机制过滤进入应用程序的 HTTP 请求。）做为洋葱皮的概念]
 *
 *      核心知识点：返回闭包函数、开始包洋葱: array_reduce()
 *
 * @Author  leeprince:2020-03-17 00:59
 */

use Couchbase\Cluster;

/**
 * [中间件 -  Middleware01]
 *
 * @Author  leeprince:2020-03-17 01:03
 */
class Middleware01 {
    public static function handle(Closure $next)
    {
        var_dump('Middleware01 - 我是控制器的前置操作');
        $next();
        var_dump('Middleware01 - 我是控制器的后置操作');
    }
}
/**
 * [中间件 -  Middleware02]
 *
 * @Author  leeprince:2020-03-17 01:03
 */
class Middleware02 {
    public static function handle(Closure $next)
    {
        var_dump('Middleware02 - 我是控制器的前置操作');
        $next();
        var_dump('Middleware02 - 我是控制器的后置操作');
    }
}

/**
 * [中间件 -  Middleware03]
 *
 * @Author  leeprince:2020-03-17 01:03
 */
class Middleware03 {
    public static function handle(Closure $next)
    {
        var_dump('Middleware03 - 我是控制器的前置操作');
        $next();
        var_dump('Middleware03 - 我是控制器的后置操作');
    }
}

Class Controller {
    public static function index()
    {
        var_dump('我是控制器');
    }
}

/**
 * [管道]
 *
 * @Author  leeprince:2020-03-17 01:09
 */
class Pipeline
{
    // 管道类的数组 - 即存放中间件
    protected $pipes = [
        'Middleware01',
        'Middleware02',
        'Middleware03',
    ];
    
    /**
     * [运行带有最终目标（洋葱心，即控制器的方法）回调的管道。]
     *
     * @Author  leeprince:2020-03-17 01:15
     * @param Closure $destination
     * @return mixed
     */
    public function then(Closure $destination)
    {
        $pipeline = array_reduce(
            array_reverse($this->pipes), $this->carry(), $this->prepareDestination($destination)
        );
        
        return $pipeline();
    }
    
    /**
     * [获取最后一块闭合的洋葱心，即控制器的方法]
     *
     * @Author  leeprince:2020-03-17 01:29
     * @param Closure $destination
     * @return mixed
     */
    public function prepareDestination(Closure $destination)
    {
        return function () use ($destination) {
            return $destination();
        };
    }
    
    /**
     * [获取一个代表洋葱切片的闭包。]
     *
     * @Author  leeprince:2020-03-17 01:24
     * @return Closure
     */
    protected function carry()
    {
        return function ($stack, $pipe) {
            return function () use ($stack, $pipe){
                return $pipe::handle($stack);
            };
        };
    }
}

// 调用管道
$pipe = new Pipeline();
$pipe->then(function () {
     return (new Controller())->index();
});