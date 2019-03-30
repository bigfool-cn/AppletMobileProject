<?php

/**
 * 获取ip地址详细信息
 * @param $ip
 * @return mixed
 */
function getIpConfig($ip){
    $url = 'http://ip.taobao.com/service/getIpInfo.php?ip='.$ip;
    $curl = curl_init();
     //设置抓取的url
    curl_setopt($curl, CURLOPT_URL, $url);
    //设置头文件的信息作为数据流输出
    curl_setopt($curl, CURLOPT_HEADER, 0);
    //设置获取的信息以文件流的形式返回，而不是直接输出。
    curl_setopt($curl, CURLOPT_RETURNTRANSFER, 1);
    //执行命令
    $data = curl_exec($curl);
    //关闭URL请求
    curl_close($curl);
    //显示获得的数据
    return json_decode($data,true);
}

/**
 * 返回json格式数据
 * @param $code 状态码
 * @param $msg 消息
 * @param array $data 数组数据
 * @return string json数据
 */

