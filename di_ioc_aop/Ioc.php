<?php
/**
 * [Ioc - 控制反转 - 容器
 *      概念：将设计好的对象及对象的依赖关系交给容器管理，而不是传统的直接在应用组件内部直接控制
 *      步骤3：
 *          1. 【绑定】定义接口和实现类，并将对象绑定到容器数组，并返回可创建实例化对象的闭包函数
 *          2. 【反射】在返回的闭包函数中，根据反射机制（反射类）将要实例对象的构造函数反射出来，并根据构造函数是否包含参数返回实例对象
 *          3. 【执行】执行闭包函数，通过反射类获取实例对象
 * ]
 *
 * @Author  leeprince:2020-02-26 12:28
 */
define('IS_DEBUG', true);

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
        return '已记录文件日志';
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
        return '已记录数据库日志';
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
        return $this->log->write();
    }
}

/**
 * [容器]
 *
 * @Author  leeprince:2020-02-26 12:49
 */
class Ioc
{
    protected $bindings = [];

    /**
     * [绑定到容器数组，并返回可创建实例化对象的闭包函数]
     *      可以继续完善[已升级到bind 方法]：支持绑定闭包，进行闭包判断，如果不是闭包则创建闭包，如果是则直接绑定
     *
     * @Author  leeprince:2020-02-26 12:51
     * @param $abstract 要绑定的抽象（标识） / 接口名
     *                  【注意 ：要绑定的到容器数组中的类或者接口名，使用「类型提示」 的方式在类的构造函数中注入基于类依赖项时，会根据构造函数参数并反射后继续实例化，所以该值不可以随便定义，否则报错。
     *                  所以建议该参数使用绑定的的类 / 接口名】
     * @param $concrete 绑定的类名 / 接口的实现类名
     */
    public function bindNoClosure($abstract, $concrete)
    {
        prt('[bind 方法]');

        if (is_null($concrete)) {
            $concrete = $abstract;
        }
        // 绑定闭包
        $this->bindings[$abstract]['concrete'] = function ($ioc) use ($concrete) {
            prt('bindings:'.$concrete);

            return $ioc->build($concrete);
        };

    }

    /**
     * [绑定到容器数组，并返回可创建实例化对象的闭包函数]
     *
     * @Author  leeprince:2020-03-08 15:34
     * @param $abstract 要绑定的抽象（标识） / 接口名
     *                  【注意 ：要绑定的到容器数组中的类或者接口名，使用「类型提示」 的方式在类的构造函数中注入依赖项时，会根据构造函数参数并反射后继续实例化，所以该值不可以随便定义，否则报错。
     *                  所以建议该参数使用绑定的的类 / 接口名】
     * @param $concrete 实例化对象 / 绑定的类名 / 实现接口的类名 / 闭包 / null(绑定自身)
     */
    public function bind($abstract, $concrete = null)
    {
        prt('[bind 方法]');

        if (is_null($concrete)) {
            $concrete = $abstract;
        }

        if (! $concrete instanceof Closure) {
            $concrete =  $this-> getClosure($abstract, $concrete);
        }

        // 绑定闭包
        $this->bindings[$abstract]['concrete'] = $concrete;
    }

    /**
     * [执行闭包函数，创建实例化对象]
     *
     * @Author  leeprince:2020-02-26 13:05
     * @param $abstract
     * @return mixed
     */
    public function make($abstract)
    {
        $ioc = $this->bindings[$abstract]['concrete'];
        prt('[make]-$abstract:'.$abstract);
        // prt(debug_backtrace());
        return $ioc($this);
    }

    /**
     * [获取闭包函数]
     *
     * @Author  leeprince:2020-03-09 23:01
     * @param $abstract
     * @param $concrete
     * @return Closure
     */
    public function getClosure($abstract, $concrete)
    {
        return function ($ioc) use ($abstract, $concrete) {
            return $ioc->build($concrete);
        };
    }

    /**
     * [通过反射类获取实例（创建对象）]
     *
     * @Author  leeprince:2020-02-26 12:57
     * @param $concrete
     */
    public function build($concrete)
    {
        // 使用反射类反射出当前类的所有信息
        $reflection = new ReflectionClass($concrete);
        prt('[build]-$concrete:'.$concrete);

        // 判断是否可以实例化
        if (!$reflection->isInstantiable()) {
            throw new Exception('该类无法实例化', 1);
        }

        // 获取构造函数
        $constructor = $reflection->getConstructor();
        if (is_null($constructor)) {
            // 实例化对象
            return $reflection->newInstance();
        } else {
            // 获取构造函数的参数
            $depenen = $constructor->getParameters();
            $instances = $this->getDependencies($depenen);
            return $reflection->newInstanceArgs($instances);
        }

    }

    /**
     * [根据构造函数中的类型提示参数（依赖注入）中继续实例化对象]
     *
     * @Author  leeprince:2020-02-26 22:54
     * @param array $depenen
     * @return array
     */
    protected function getDependencies(array $depenen)
    {
        $depenencies = [];
        prt($depenen,  '[getDependencies]-参数$depenen:');
        foreach ($depenen as $parameter) {
            prt($parameter->getClass(), '[getDependencies]-$parameter->getClass():');
            prt($parameter->getClass()->name, '[getDependencies]-$parameter->getClass()->name:');

            $depenencies[] = $this->make($parameter->getClass()->name);
        }
        prt($depenencies, '[getDependencies]-$depenencies:');

        return $depenencies;
    }
}

// 实例化容器
$ioc = new Ioc();

// 绑定自身
$ioc->bind('DbLog', 'DbLog');
$ioc->bind('FileLog', 'FileLog');
$log = $ioc->make('FileLog'); // // $log = $ioc->make('DbLog');
echo $log->write();

// 绑定闭包
$ioc->bind('DbLog', function($ioc) {
   return new DbLog();
});
$log = $ioc->make('DbLog');
echo $log->write();

// 绑定接口到实现
$ioc->bind('iLog', 'DbLog'); // $ioc->bind('iLog', 'FileLog');
$ioc->bind('logger', 'Logger');
$logger = $ioc->make('logger');
echo $logger->index();


/**
 * [是否打印调试]
 *
 * @Author  leeprince:2020-03-15 12:21
 * @param $data
 * @param string $msg
 */
function prt($data, $msg = '')
{
    if (!IS_DEBUG) {
        echo '<pre>';
        echo $msg;
        print_r($data);
    }
}




