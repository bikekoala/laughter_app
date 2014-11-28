<?php
// 全局配置文件
$common = array(
    'URL_CASE_INSENSITIVE'  => true, // URL大小写
    'URL_MODEL'             => '2', // URL模式
    'URL_ROUTER_ON'         => true, // 开启路由
    'URL_ROUTE_RULES'       => array( // 定义路由规则
        'joke/:joke_id\d/[:user_id\d]' => 'joke/detail'
    ),

    // 数据库配置信息
    'DB_TYPE'   => 'mysql', // 数据库类型
    'DB_HOST'   => 'localhost', // 服务器地址
    'DB_NAME'   => 'laughter', // 数据库名
    'DB_USER'   => 'root', // 用户名
    'DB_PWD'    => 'fighting', // 密码
    'DB_PORT'   => 3306, // 端口
    'DB_CHARSET'=> 'utf8', // 字符集
);

// 项目配置文件
$custom = array(
    'PORTAL_URL' => 'http://www.jgxhb.com',
);

return array_merge($common, $custom);
