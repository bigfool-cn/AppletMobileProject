<?php
/**
 * Created by PhpStorm.
 * User: JS_chen
 * Date: 2019/3/2
 * Time: 14:56
 */

namespace app\helps;
use Elasticsearch\ClientBuilder;

class ElasticSearch
{
    private $client;
    public function __construct()
    {
        $this->client = ClientBuilder::create()->build();
    }

    /**
     * 获取数据
     * @param string $index 索引
     * @param string $type 类型
     * @param string $id id值，可有els分配
     * @return array 结果
     */
    public function get($index='',$type='')
    {
        $params = array('index' => $index, 'type' => $type);
        $res = $this->client->get($params);
        return $res;
    }

    /**
     * 添加数据
     * @param string $index
     * @param string $type
     * @param string $id
     * @param array $data 需要添加的数据
     * @return array
     */
    public function add($index='',$type='',$data=array())
    {
        $params = array(
            'index' => $index,
            'type' => $type,
            'body' => $data
        );
        $res = $this->client->index($params);
        return $res;
    }

    /**
     * 更新数据
     * @param string $index
     * @param string $type
     * @param string $id
     * @param array $data 需要更新的数据
     * @return array
     */
    public function update($index='',$type='',$data=array())
    {
        $params = array(
            'index' => $index,
            'type' => $type,
            'body' => $data
        );
        $res = $this->client->update($params);
        return $res;
    }

    /**
     * 删除数据
     * @param string $index
     * @param string $type
     * @param string $id
     * @return array
     */
    public function delete($index='',$type='')
    {
        $params = array(
            'index' => $index,
            'type' => $type
        );
        $res = $this->client->delete($params);
        return $res;
    }

    /**
     * 搜索数据
     * @param string $index
     * @param string $type
     * @param string $id
     * @param array $filter 搜索添加，这里只做了匹配搜索
     * @return array
     */
    public function search($index='',$type='',$filter=array())
    {
        $params = array(
            'index' => $index,
            'type' => $type,
            'body' => [
                'query' => [
                    'match' => $filter
                ]
            ]
        );
        $res = $this->client->search($params);
        return $res;
    }
}