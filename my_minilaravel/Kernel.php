<?php
/**
 * [应用内核]
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