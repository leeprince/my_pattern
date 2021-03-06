<?php
/**
 * [路由对象]
 *
 * @Author  leeprince:2020-03-11 23:24
 */

namespace Minilaravel\Route;

class Route
{
    // 所有的路由数组
    protected $routes = [];
    
    // 路由器支持的所有Http 动词
    public static $verbs = ['GET', 'HEAD', 'POST', 'PUT', 'PATCH', 'DELETE', 'OPTIONS'];
    
    /**
     * [get 请求]
     *
     * @Author  leeprince:2020-03-12 02:10
     * @param $method
     * @param $action
     * @return mixed
     */
    public function get($uri, $action)
    {
        return $this->addRoute(['GET'], $uri, $action);
    }
    /**
     * [post 请求]
     *
     * @Author  leeprince:2020-03-12 02:10
     * @param $method
     * @param $action
     * @return mixed
     */
    public function post($uri, $action)
    {
        return $this->addRoute(['POST'], $uri, $action);
    }
    
    /**
     * [所有请求的http 动词]
     *
     * @Author  leeprince:2020-03-12 02:13
     * @param $method
     * @param $action
     * @return mixed
     */
    public function any($uri, $action)
    {
        return $this->addRoute(static::$verbs, $uri, $action);
    }
    
    /**
     * [添加到路由数组中]
     *
     * @Author  leeprince:2020-03-12 02:15
     * @param $methods
     * @param $uri
     * @param $action
     */
    public function addRoute($methods, $uri, $action)
    {
        foreach($methods as $method) {
            $this->routes[$method][$uri] = $action;
        }
        return $this;
    }
    
    /**
     * [请求分发]
     *
     * @Author  leeprince:2020-03-12 02:16
     * @param $request
     */
    public function dispatch($request)
    {
        return $this->findRoute($request);
    }
    
    /**
     * [从路由数组中查找路由]
     *
     * @Author  leeprince:2020-03-12 02:17
     * @param $request
     */
    public function findRoute($request)
    {
        return $this->runRoute($request);
    }
    
    /**
     * [执行路由]
     *
     * @Author  leeprince:2020-03-12 02:18
     * @param $request
     */
    public function runRoute($request)
    {
        $route = substr($request->getSelf(), strripos($request->getSelf(), '/')+1);
        // 实例化类库
        $action = $this->routes['GET'][$route];
        if (is_callable($action)) {
            return $action();
        }
        list($controll, $function)= explode('@', $action);
        return (new $controll)->{$function}();
    }
}