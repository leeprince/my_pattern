<?php
/**
 * [Description]
 *
 * @Author  leeprince:2020-03-11 19:28
 */
class Container
{
    // 绑定到容器的数组，相当于 bindings 与 $aliases
    protected $bind = [];

    // 当前实例，单例创建
    protected static $instance;

    // 绑定到容器的共享实例数组
    protected $instances = [];
    
    // 参数覆盖堆栈的数组
    protected $with = [];

    /**
     * [绑定到容器数组]
     *
     * @Author  leeprince:2020-03-11 19:36
     * @param $abstract 要绑定的抽象（标识） / 接口名
     *                  【注意 ：要绑定的到容器数组中的类或者接口名，使用「类型提示」 的方式在类的构造函数中注入依赖项时，会根据构造函数参数并反射后继续实例化，所以该值不可以随便定义，否则报错。
     *                  所以建议该参数使用绑定的的类 / 接口名】
     * @param $concrete 实例化对象 / 绑定的类名 / 实现接口的类名 / 闭包 / null(绑定自身)
     */
    public function bind($abstract, $concrete = null)
    {
        // 绑定自身
        if (is_null($concrete)) {
            $concrete = $abstract;
        }

        $this->bind[$abstract] = $concrete;
    }

    /**
     * [校验是否已绑定到容器]
     *
     * @Author  leeprince:2020-03-12 01:15
     */
    public function has($abstract)
    {
        return isset($this->bind[$abstract]);
    }

    /**
     * [绑定到共享实例中]
     *
     * @Author  leeprince:2020-03-12 01:17
     * @param $abstract
     * @param $instance
     */
    public function instance($abstract, $instance)
    {
        if (isset($this->bind[$abstract])) {
            unset($this->bind[$abstract]);
        }

        $this->instances[$abstract] = $instance;
    }

    /**
     * [获取当前实例]
     *
     * @Author  leeprince:2020-03-12 01:18
     * @return Container
     */
    public static function getInstance()
    {
        if (is_null(static::$instance)) {
            static::$instance = new static;
        }
        return static::$instance;
    }

    /**
     * [设置当前实例]
     *
     * @Author  leeprince:2020-03-12 01:19
     * @param $container
     * @return mixed
     */
    public static function setInstance($container)
    {
        return static::$instance = $container;
    }

    /**
     * [创建实例]
     *
     *      laravel 中是resolve 方法
     *
     * @Author  leeprince:2020-03-12 01:20
     * @param $abstract
     * @return mixed
     * @throws Exception
     */
    public function make($abstract, array $parameter = [])
    {
        // 判断是否在共享实例中，有直接返回
        if (isset($this->instances[$abstract])) {
            return $this->instances[$abstract];
        }
        
        $this->with[] = $parameter;
    
        // 再优化版本：通过反射机制继续递归解析具体类中的依赖注入：参考 IOC 的部分即可！
        if (isset($this->bind[$abstract])) {
            $concrete = $this->bind[$abstract];
            
            // 是对象时直接返回
            if (is_object($concrete)) {
                return $concrete;
            }
            
            // 如果具体实现是闭包那么直接执行闭包，也不必绑定到共享实例中，因为闭包函数本身也不是实例。
            // 下列有3中判断是否是否一个闭包的方式。显然第一种更加专业
            if ($concrete instanceof Closure) {
            // if (is_object($concrete)) {
            // if (is_callable($concrete)) {
                return $concrete();
            }
    
            /**
             * 有两个版本
             */
            
            /** 版本1： 这是不考虑基于类的依赖注入的简单版本 */
            // return $this->instances[$abstract] = (empty($parameter))? new $concrete() : new $concrete(...$parameter);
    
            /** 版本2：通过反射机制考虑基于类的依赖注入的优化版本 */
            // 通过反射类，反射出该具体的所有信息
            $reflection = new ReflectionClass($concrete);
            if (! $reflection->isInstantiable()) {
                throw new Exception('反射后判断该类无法实例化');
            }
    
            // 获取构造函数
            $constructor = $reflection->getConstructor();
            if (is_null($constructor)) {
                $object = $reflection->newInstance();
    
            } else {
                // 获取构造函数参数
                $depenen = $constructor->getParameters();
                $instances = $this->getDependencies($depenen);
    
                $object = $reflection->newInstanceArgs($instances);
            }
            
            /**
             * 删除参数覆盖堆栈的数组的最后一条记录。
             *      因为这可能是递归解析基于类的依赖注入，
             *      如果不删除会导致在递归的上一步没有获取到当前解析中的参数覆盖的参数数组
             */
            array_pop($this->with);
            
            return $this->instances[$abstract] = $object;
            
        }
        
        throw new Exception('没有找到实例'.$abstract);
    }
    
    /**
     * [根据构造函数中的类型提示参数（依赖注入：基于类的依赖注入或者非基于类的原始依赖注入）中继续实例化对象]
     *
     * @Author  leeprince:2020-03-15 13:12
     * @param array $depenen
     * @return array
     * @throws Exception
     */
    public function getDependencies(array $dependencies)
    {
        $results = [];
        foreach ($dependencies as $key => $dependency) {
            // 确定给定的依赖项是否具有参数替代
            if ($this->hasParameterOverride($dependency)) {
                $results[] = $this->getParameterOverride($dependency);
                continue;
            }
    
            // 考虑构造函数的参数是不是基于类的依赖注入
            $results[] = is_null($dependency->getClass())
                ? $this->resolvePrimitive($dependency, $key)
                : $this->resolveClass($dependency);
        }
        
        return $results;
    }
    
    /**
     * [解析非基于类的原始依赖]
     *
     * @Author  leeprince:2020-03-15 15:47
     * @param ReflectionParameter $parameter
     * @param $key
     * @return mixed
     * @throws ReflectionException
     */
    public function resolvePrimitive(ReflectionParameter $parameter, $key)
    {
        if ($parameter->isDefaultValueAvailable()) {
            return $parameter->getDefaultValue();
        }
        
        return null;
    }
    
    /**
     * [解析容器中基于类的依赖]
     *
     * @Author  leeprince:2020-03-15 15:29
     * @param ReflectionParameter $parameter
     * @return mixed
     * @throws Exception
     */
    public function resolveClass(ReflectionParameter $parameter)
    {
        return $this->make($parameter->getClass()->name, []);
    }
    
    
    /**
     * [确定给定的依赖项是否具有参数替代。]
     *
     * @Author  leeprince:2020-03-15 16:17
     * @param $dependency
     * @return bool
     */
    protected function hasParameterOverride(ReflectionParameter $dependency)
    {
        return array_key_exists(
            $dependency->name, $this->getLastParameterOverride()
        );
    }
    
    /**
     * [获取依赖项的参数覆盖。]
     *
     * @Author  leeprince:2020-03-15 16:18
     * @param ReflectionParameter $dependency
     * @return mixed
     */
    protected function getParameterOverride(ReflectionParameter $dependency)
    {
        return $this->getLastParameterOverride()[$dependency->name];
    }
    
    /**
     * [获取最后一个参数覆盖]
     *
     * @Author  leeprince:2020-03-15 16:23
     * @return array|mixed
     */
    protected function getLastParameterOverride()
    {
        return count($this->with) ? end($this->with) : [];
    }
}