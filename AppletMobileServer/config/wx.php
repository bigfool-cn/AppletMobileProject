<?php
/**
 * Created by PhpStorm.
 * User: JS_chen
 * Date: 2018/11/22
 * Time: 14:13
 */

return[
    'AppID' => '',// 小程序AppID
    'AppSecret' => '', // 小程序AppSecret
    // 腾讯云cos与数据万象配置
    'region' => 'ap-guangzhou', #地域，如ap-guangzhou,ap-beijing-1
    'credentials' => array(
        'secretId' => '',// 存储桶secretId
        'secretKey' => '',// 存储桶secretKey
    ),
    'bucket'=>'',
    'cos_baseurl' => 'https://xxx.cos.ap-guangzhou.myqcloud.com',//cos存储桶地址
    'cos_baseurl2' => 'http\:\/\/xxx-1256191445\.cos\.ap-guangzhou\.myqcloud\.com',
    'cdn_baseurl' => "https://xxx.file.myqcloud.com",//关联了//cos存储桶的cdn地址
    'cdn_baseurl2' => "https\:\/\/xxx\.file\.myqcloud\.com",
    'cdn_imgbaseurl' => 'https://xxx.image.myqcloud.com',//关联了//cos存储桶的图片cdn地址
];