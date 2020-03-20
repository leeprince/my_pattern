<?php
/**
 * [门面：通过定义门面的抽象类，让子类可以「静态代理」应用的服务容器中可用的底层类]
 *
 *      1. 调用具体对象（路由、请求...）的门面类, 执行__callStatic() 魔术方法动态的调用静态对象
 *      2. 获取继承该抽象门面类的门面对象名称
 *      3. 通过获取门面名称从容器已绑定的类库中解析出门面要静态代理的基类，并返回
 *      4. 传入可变参数，并动态执行该具体对象的方法
 *
 *
 * @Author  leeprince:2020-03-11 23:24
 */
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