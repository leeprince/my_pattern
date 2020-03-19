<?php
/**
 * [自定义门面类  - 请求门面类]
 *
 * @Author  leeprince:2020-03-11 23:26
 */

namespace Minilaravel\Support\Facades;

class RequestFacade extends Facade
{
    public static function getFacadeAccessor()
    {
        return 'request';
    }
}