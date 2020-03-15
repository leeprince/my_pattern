<?php
/**
 * [控制器]
 *
 * @Author  leeprince:2020-03-12 04:22
 */

class LeeprinceController
{
    public function index()
    {
        dump('route config is Controller@function');
        dump("hello leeprince minilaravel");
        
        dump(app('FileLog')->write());
        dump(app()->make('FileLog')->write());
        dump(app()->make(LogContracts::class)->write());
        dump(app()->make('MysqlLog')->write());
        
        return '[return]hello leeprince ';
    }
}