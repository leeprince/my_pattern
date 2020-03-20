<?php
/**
 * [控制器]
 *
 *  1. 执行控制器的方法的相应业务。
 *  2. 这里示例提供有数据库连接的服务，配合应用服务容器辅助函数并调用服务方法即可
 *
 * @Author  leeprince:2020-03-12 04:22
 */

class LeeprinceController
{
    public function index()
    {
        dump('route config is Controller@function');
        dump("hello leeprince minilaravel");
        
        // 直接通过容器解析
        dump('---------------直接通过容器解析服务 👇-----------------');
        dump(app('FileLog')->write());
        dump(app()->make('FileLog')->write());
        dump(app()->make(LogContracts::class)->write());
        dump(app()->make('MysqlLog')->write());
        
        // 通过门面
        dump('---------------门面调用服务 👇-----------------');
        dump('门面：', FileLogFacade::write());
        
        return '[return]hello leeprince ';
    }
}