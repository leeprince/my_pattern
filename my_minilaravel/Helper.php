<?php
/**
 * [Description]
 *
 * @Author  leeprince:2020-03-14 14:52
 */

if (! function_exists('app')) {
    /**
     * [Description]
     *
     * @Author  leeprince:2020-03-14 14:52
     * @param null $abstract
     * @param array $parameters
     * @return Container|mixed
     * @throws Exception
     */
    function app($abstract = null, array $parameters = [])
    {
        if (is_null($abstract)) {
            return Container::getInstance();
        }
        
        return Container::getInstance()->make($abstract, $parameters);
    }
}
if (! function_exists('dump')) {
    /**
     * [Description]
     *
     * @Author  leeprince:2020-03-14 14:52
     * @param null $abstract
     * @param array $parameters
     * @return Container|mixed
     * @throws Exception
     */
    function dump($data, $msg = '')
    {
        echo '<pre>';
        echo $msg;
        print_r($data);
    }
}

