<?php
/**
 * [控制器]
 *
 * @Author  leeprince:2020-03-12 04:22
 */

namespace Minilaravel\Controller;

use Minilaravel\Support\Facades\FileLogFacade;
use Minilaravel\Contracts\Log\LogContracts;

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
        dump('门面：'.FileLogFacade::write());
        
        return '[return]hello leeprince ';
    }
}