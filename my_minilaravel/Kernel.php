<?php
/**
 * [应用内核：处理 HTTP 请求，包括执行请求分发]
 *      1. 内核类通过构造函数将依赖（应用框架（容器））注入
 *      2. 并在构造函数中解析路由对象到内核的属性中
 *      3. 将请求对象传入到路由对象（在构造函数中解析路由对象到内核的属性中）的请求分发方法中
 *
 * @Author  leeprince:2020-03-12 01:55
 */

class Kernel implements KernelContracts
{
    private $app;
    private $route;
    
    public function __construct(Application $app)
    {
        $this-> app = $app;
        
        $this->route = $this->app->make('route');
    }
    
    /**
     * [处理请求]
     *
     * @Author  leeprince:2020-03-12 01:59
     * @param $request
     * @return mixed
     */
    public function handle($request)
    {
        return $this->dispatchToRoute($request);
    }
    
    /**
     * [请求分发到路由]
     *
     * @Author  leeprince:2020-03-12 02:05
     * @param $request
     */
    public function dispatchToRoute($request)
    {
        $this->route->dispatch($request);
    }
    
    
    
}