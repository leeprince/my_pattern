<?php
/**
 * [Description]
 *
 * @Author  leeprince:2020-03-11 23:25
 */
class Request
{
    // 当前请求的请求地址
    private $self;
    
    /**
     * Request 在请求对象的构造函数中设置请求地址到请求对象的属性（即当解析该请求对象时即设置好请求地址到请求对象的属性）
     */
    public function __construct()
    {
        $this->self = $_SERVER['REQUEST_URI'];
    }
    
    /**
     * [获取所有请求的数据]
     *
     * @Author  leeprince:2020-03-12 02:22
     * @param null $method
     */
    public function input($key = null)
    {
        return $this->{$this->method()}($key);
    }
    
    /**
     * [获取 get 请求参数]
     *
     * @Author  leeprince:2020-03-12 02:29
     * @param null $key
     * @return mixed
     */
    public function get($key = null)
    {
        return (is_null($key))? $_GET : $_GET[$key];
    }
    
    /**
     * [获取 get 请求参数]
     *
     * @Author  leeprince:2020-03-12 02:29
     * @param null $key
     * @return mixed
     */
    public function post($key = null)
    {
        return (is_null($key))? $_POST : $_POST[$key];
    }
    
    /**
     * [获取当前请求的方法]
     *
     * @Author  leeprince:2020-03-12 02:30
     * @return string
     */
    public function method()
    {
        return strtolower($_SERVER['REQUEST_METHOD']);
    }
    
    /**
     * [设置当前请求的请求地址并返回该request 对象]
     *
     * @Author  leeprince:2020-03-12 02:33
     * @return $this
     */
    public function capture()
    {
        // $this->self = $_SERVER['PHP_SELF'];
        $this->self = $_SERVER['REQUEST_URI'];
    
        return $this;
    }
    
    /**
     * [获取当前请求的请求地址]
     *
     * @Author  leeprince:2020-03-12 02:34
     * @return mixed
     */
    public function getSelf()
    {
        return $this->self;
    }
}