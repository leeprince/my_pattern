<?php
/**
 * [自定义门面类]
 *
 * @Author  leeprince:2020-03-11 23:26
 */
class dbFacade extends Facade
{
    public static function getFacadeAccessor()
    {
        return 'db';
    }
}
class RouteFacade extends Facade
{
    public static function getFacadeAccessor()
    {
        return 'route';
    }
}
class RequestFacade extends Facade
{
    public static function getFacadeAccessor()
    {
        return 'request';
    }
}