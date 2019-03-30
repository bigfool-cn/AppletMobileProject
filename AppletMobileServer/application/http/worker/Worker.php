<?php
/**
 * Created by PhpStorm.
 * User: JS_chen
 * Date: 2018/11/19
 * Time: 14:27
 */

namespace app\http\worker;

use  think\worker\Server;

class Worker extends Server
{
    protected $socket = 'http://0.0.0.0:2345';

    public function onMessage($connection,$data)
    {
        $datas = json_decode($data,true);
        $ip_addr = getIpConfig($datas['ip']);
        $data = [
            'username' => $datas['username'],
            'ip' => $datas['ip'],
            'address' => $ip_addr['data']['region'].'--'.$ip_addr['data']['city'],
            'login_time' => time()
        ];
        Db::table('admin_log')->insert($data);
        $connection->send('over');
    }
}