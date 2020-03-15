<?php
/**
 * [DI - 依赖注入
 *      概念：应用的方法中不负责查找资源或者不负责查找其他依赖的协作对象, 而是通过参数，动态的向该方法提供其他所需要的对象。DI 是IOC 的具体表现，而IOC 不是一种设计模式而是一种思想
 * ]
 *
 * @Author  leeprince:2020-02-25 14:34
 */

// -----------------------------依赖注入到类------------------------
class DB
{
    public function select()
    {
        echo '查询数据库连接对象';
    }
}

class User
{
    protected $db;

    public function __construct(DB $db)
    {
        $this->db = $db;
    }

    public function find()
    {
        $this->db->select();
    }
}

$user = new User(new DB());
$user->find();
// -----------------------------依赖注入到类-01-end------------------------



// -----------------------------依赖注入到接口------------------------

/**
 * [日志接口]
 *
 * @Author  leeprince:2020-02-26 12:41
 */
interface iLog
{
    public function write();
}

/**
 * [实现文件日志]
 *
 * @Author  leeprince:2020-02-26 12:41
 */
class FileLog implements iLog
{
    public function write()
    {
        prt('已记录文件日志');
    }
}

/**
 * [实现数据库日志]
 *
 * @Author  leeprince:2020-02-26 12:41
 */
class DbLog implements iLog
{
    public function write()
    {
        prt('已记录数据库日志');
    }
}

/**
 * [根据依赖注入的实例来记录日志]
 *
 * @Author  leeprince:2020-02-26 12:47
 */
class Logger
{
    protected $log;

    public function __construct(iLog $log)
    {
        $this->log = $log;
    }

    public function index()
    {
        $this->log->write();
    }
}

// $logger = new Logger(new FileLog());
$logger = new Logger(new DbLog());
$logger->index();


// -----------------------------依赖注入到接口-end------------------------


function prt($data, $msg = '')
{
    echo '<pre>';
    echo $msg;
    print_r($data);
}
