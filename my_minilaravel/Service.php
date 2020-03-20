<?php
/**
 * [应用服务]
 *      类似 laravel 中的服务提供者，不过具体现实不一样
 *
 * @Author  leeprince:2020-03-11 23:25
 */
class FileLog implements LogContracts
{
    private $type  = 'file';
    
    /**
     * [实现接口的 connection 方法]
     *
     * @Author  leeprince:2020-03-12 02:37
     * @return string
     */
    public function write()
    {
        return '当前操作的的日志类型是：'.$this->type;
    }
    
}
class MysqlLog implements LogContracts
{
    private $type  = 'mysql';
    
    /**
     * [实现接口的 connection 方法]
     *
     * @Author  leeprince:2020-03-12 02:37
     * @return string
     */
    public function write()
    {
        return '当前操作的的日志类型是：'.$this->type;
    }
    
}

class TestReflection
{
    public function __construct(MysqlLog $mysqllog, $a1)
    {
        var_dump("测试构造函数有参数且为基于类的依赖注入 - MysqlLog mysqllog");
        var_dump($mysqllog, $a1);
    }
    
    public function test()
    {
        return "TestReflection - test()\n";
    }
}
class TestNew
{
    public function __construct()
    {
        var_dump("测试容器绑定时直接实例化");
    }
    
    public function test()
    {
        return "TestNew - test()\n";
    }
}