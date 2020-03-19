<?php
/**
 * [门面抽象类]
 *
 * @Author  leeprince:2020-03-11 23:24
 */

namespace Minilaravel\Support\Facades;

use Minilaravel\Foundation\Application;

abstract class Facade
{
    protected static $resolvedInstance = [];
    
    /**
     * [获取继承该抽象门面类的门面对象名称，并返回从容器已绑定的类库中解析出门面要静态代理的基类]
     *
     * @Author  leeprince:2020-03-12 02:44
     * @return mixed
     * @throws Exception
     */
    public static function getFacadeRoot()
    {
        return static::resolvedInstance(static::getFacadeAccessor());
    }
    
    /**
     * [获取继承该抽象门面类的门面对象名称]
     *
     * @Author  leeprince:2020-03-12 02:50
     * @throws Exception
     */
    public static function getFacadeAccessor()
    {
        throw new Exception('没有找到指定的门面类');
    }
    
    /**
     * [从容器已绑定的类库中解析出门面要静态代理的基类]
     *
     * @Author  leeprince:2020-03-12 02:50
     * @param $name
     * @return mixed
     * @throws Exception
     */
    public static function resolvedInstance($name)
    {
        if (is_object($name)) {
            return $name;
        }
        if (isset(static::$resolvedInstance[$name])) {
            return static::$resolvedInstance[$name];
        }
        return static::$resolvedInstance[$name] = Application::getInstance()->make($name);
    }
    
    /**
     * [动态的调用静态对象]
     *
     * @Author  leeprince:2020-03-12 02:53
     * @param $method
     * @param $args
     * @return mixed
     * @throws Exception
     */
    public static function __callStatic($method, $args)
    {
        $instance = static::getFacadeRoot();
        if (! $instance) {
            throw new Exception('要实例的门面对象不存在');
        }
        return $instance->{$method}(...$args);
    }
}