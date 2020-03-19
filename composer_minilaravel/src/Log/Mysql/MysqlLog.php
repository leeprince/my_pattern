<?php
/**
 * [日志服务 - mysql 日志服务]
 *
 * @Author  leeprince:2020-03-11 23:25
 */

namespace Minilaravel\Log\Mysql;

use Minilaravel\Contracts\Log\LogContracts;

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