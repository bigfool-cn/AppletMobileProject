<?php
/**
 * Created by PhpStorm.
 * User: JS_chen
 * Date: 2019/3/14
 * Time: 10:25
 * 雪花算法
 */

namespace app\helps;


class SnowFlake
{
    const EPOCH = 1552531115092;    //开始时间,固定一个小于当前时间的毫秒数
    const max12bit = 4095;
    const max41bit = 1088123637775;
    static $machineId = null;      // 机器id

    public static function machineId($mId = 0)
    {
        self::$machineId = $mId;
    }

    public static function createOnlyId()
    {
        // 时间戳
        $time = floor(microtime(true) * 1000);
        // 当前时间 与 开始时间 差值
        $time -= self::EPOCH;
        // 二进制的 毫秒级时间戳 42字节
        $base = decbin(self::max41bit + $time);
        if(!self::$machineId) {
            $machineid = self::$machineId;
        }else {
            // 机器id  10 字节 左侧补0
            $machineid = str_pad(decbin(self::$machineId), 10, "0", STR_PAD_LEFT);
        }
        // 序列数 12字节
        $random = str_pad(decbin(mt_rand(0, self::max12bit)), 12, "0", STR_PAD_LEFT);
        // 拼接
        $base = $base.$machineid.$random;
        // 转化为 十进制 返回
        return bindec($base);
    }
}