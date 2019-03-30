<?php
/**
 * Created by PhpStorm.
 * User: JS_chen
 * Date: 2019/3/12
 * Time: 10:25
 */

namespace app\helps;
use Redis;

class RedisHelper
{
    private $redis;
    public function __construct()
    {
        $this->redis = new Redis();
        $this->redis->connect('127.0.0.1', 6379);
        $this->redis->auth('csj1063944784?');
        $this->redis->select(2);
    }

    // 设置值
    public function set($key,$value)
    {
        return $this->set($key,$value);
    }

    // 根据键获取值
    public function get($key)
    {
        return $this->redis->get($key);
    }

    // 列表头部插入值
    public function lpush($key,$value)
    {
        return $this->redis->lpush($key,$value);
    }

    // 列表头部弹出值
    public function lpop($key)
    {
        return $this->redis->lpop($key);
    }

    // 列表尾部插入值
    public function rpush($key,$value)
    {
        return $this->redis->rPush($key,$value);
    }

    // 列表尾部弹出值
    public function rpop($key)
    {
        return $this->redis->rPop($key);
    }

    // 从表头开始向表尾搜索，移除与 VALUE 相等的元素，数量为 COUNT
    public function lrem($key,$count,$value)
    {
        return $this->redis->lRem($key,$count,$value);
    }

    // 保留指定区间的列表元素
    public function ltrim($key,$start,$end)
    {
        return $this->redis->lTrim($key,$start,$end);
    }
    // 获取列表头部
    public function llen($key)
    {
        return $this->redis->llen($key);
    }

    // 往集合添加元素
    public function sadd($key,$value)
    {
        return $this->redis->sAdd($key,$value);
    }

    // 查询元素是否存在于集合
    public function sismember($key,$value)
    {
        return $this->redis->sIsMember($key,$value);
    }
}