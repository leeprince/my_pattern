<?php
/**
 * [AOP - 切面编程
 *      把去要调用的重复代码提前抽离出来，然后注入到需要的业务执行里面
 * ]
 *
 * @Author  leeprince:2020-02-26 12:28
 */


/**
 * [sesssion 缓存]
 *
 * @Author  leeprince:2020-02-28 17:14
 */
class SessionCache
{
    protected $name;

    public function setName($name)
    {
        $this->name = $name;
    }

    /**
     * [Description]
     *
     * @Author  leeprince:2020-02-28 17:14
     * @return mixed
     */
    public function getName()
    {
        return $this->name;
    }
}


/**
 * [sesssion 缓存]
 *
 * @Author  leeprince:2020-02-28 17:14
 */
class FileCache
{
    protected $name;

    public function setName($name)
    {
        $this->name = $name;
    }

    /**
     * [Description]
     *
     * @Author  leeprince:2020-02-28 17:14
     * @return mixed
     */
    public function getName()
    {
        return $this->name;
    }
}

/**
 * [AOP 切面缓存类]
 *
 * @Author  leeprince:2020-02-28 17:17
 */
class Cache
{
    protected $obj;

    public function __construct($obj)
    {
        $this->obj = $obj;
    }

    public function __call($method, $args)
    {
        var_dump("method:{{$method}}--args:".json_encode($args));
        return call_user_func_array([$this->obj, $method], $args);
    }
}

$sessionCache = new Cache(new SessionCache());
$sessionCache->setName('leeprince');
var_dump($sessionCache->getName());




