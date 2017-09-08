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

if (! function_exists('get_hongcha_phases'))
{
    /**
     * @desc 获取红茶交付期次
     * @param string payment_at: 付款时间
     * @param integer first_phase: 首期类型
     * @return array of date
     */
    function get_hongcha_phases($payment_at, $first_phase = 0)
    {
        $paymentTime = strtotime($payment_at);
        $paymentDate = date('m-d', $paymentTime);

        $firstYear = date('Y', $paymentTime);
        $lastYear = $firstYear + 10;

        $phases = [];
        for($i = $firstYear; $i <= $lastYear; $i++) {
            $phases[] = $i . '-04-30';
            $phases[] = $i . '-08-31';
            $phases[] = $i . '-12-31';
        }

        $index = 0;
        $num = 30;
        if($first_phase === 0) {
            if($paymentDate <= '03-31') { // 3月31日前全额付款的, 享受当年全年茶叶交付
                $index = 0;
            } else if($paymentDate <= '07-31') { // 享受当年2、3期
                $index = 1;
            } else if($paymentDate <= '11-30') { // 享受当年3期
                $index = 2;
            } else if($paymentDate >= '12-01') { // 从次年开始
                $index = 3;
            }
        } else {
            $index = $first_phase;
        }

        return array_slice($phases, $index, 30);
    }
}
