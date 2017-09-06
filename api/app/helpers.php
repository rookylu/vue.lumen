<?php

// 获取当前登录用户
if (! function_exists('auth_user')) {
    /**
     * Get the auth_user.
     *
     * @return mixed
     */
    function auth_user()
    {
        return app('Dingo\Api\Auth\Auth')->user();
    }
}

if (! function_exists('dingo_route')) {
    /**
     * 根据别名获得url.
     *
     * @param string $version
     * @param string $name
     * @param string $params
     *
     * @return string
     */
    function dingo_route($version, $name, $params = [])
    {
        return app('Dingo\Api\Routing\UrlGenerator')
            ->version($version)
            ->route($name, $params);
    }
}

if (! function_exists('trans')) {
    /**
     * Translate the given message.
     *
     * @param string $id
     * @param array  $parameters
     * @param string $domain
     * @param string $locale
     *
     * @return string
     */
    function trans($id = null, $parameters = [], $domain = 'messages', $locale = null)
    {
        if (is_null($id)) {
            return app('translator');
        }

        return app('translator')->trans($id, $parameters, $domain, $locale);
    }
}

if (! function_exists('get_unique_id'))
{
    /**
     * @desc 获取唯一id号
     * @param string $prefix 返回id的前缀
     * @return string unique id
     */
    function get_unique_id($prefix = '')
    {
        //$id = date('Ymd') . strtoupper(uniqid());
        $time = time();
        $year = date('Y', $time);
        $no = strtoupper(dechex($year)) . date('md', $time);
        $suffix = dechex($time);
        $id = $no . strtoupper($suffix);
        return $prefix ? $prefix . $id : $id;
    }
}
