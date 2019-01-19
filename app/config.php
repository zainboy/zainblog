<?php
/**
 * User: zain
 * Date: 2017/1/17
 * Time: 13:41
 */

return [
    'jwt'   =>  [
        'key'   =>  'z3e2ds#f1'
    ],
    //database
    'database'  =>  [
        'driver'    =>  'mysql',
        'host'      =>  '127.0.0.1',
        'port'      =>  3306,
        'database'  =>  'zainblog',
        'username'  =>  '',
        'password'  =>  '',
        'charset'   =>  'utf8',
        'collation' =>  'utf8_general_ci',
        'prefix'    =>  ''
    ],

    //cache
    'cache'                  => [
        //type (File Memcache Memcached Redis)
        'type'   => 'Redis',
        'path'   => CACHE_PATH,
        'prefix' => '',
        'expire' => 0,
    ],

    //session
    'session'                => [
        'id'             => '',
        'var_session_id' => '',
        'prefix'         => '',
        //type (redis memcache memcached)
        'type'           => 'redis',
        'auto_start'     => true,
    ],

    //cookie
    'cookie'                 => [
        'prefix'    => '',
        'expire'    => 0,
        'path'      => '/',
        'domain'    => '',
        'secure'    => false,
        'httponly'  => '',
        'setcookie' => true,
    ]
];