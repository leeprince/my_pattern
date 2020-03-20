<?php
/**
 * [契约：契约是一组接口，每一个契约都有框架提供的相应的实现]
 *
 *      应的实现
 *      容器契约
 *      内核契约
 *      服务提供者契约
 *      - 日志契约
 *
 * @Author  leeprince:2020-03-11 23:24
 */
interface KernelContracts
{
    public function handle($request);
}
interface LogContracts
{
    public function write();
}