<?php
/**
 * Created by PhpStorm.
 * User: JS_chen
 * Date: 2019/3/5
 * Time: 18:40
 */

namespace app\helps;
require_once '/home/ubuntu/xunsearch/sdk/php/lib/XS.php';

class XunSearch
{
    private $xs,$doc,$index,$search;
    public function __construct($project)
    {
        // 建立 XS 对象，项目名称为：$project
        $dir ='/home/ubuntu/xunsearch/sdk/php/app/';
        $this->xs = new \XS($project);
        // 获取 索引对象
        $this->index = $this->xs->index;
        // 获取 搜索对象
        $this->search = $this->xs->search;
        // 创建文档对象
        $this->doc = new \XSDocument;
    }

    /**
     * 添加索引
     * @param $data
     */
    public function addIndex($data)
    {
        $this->doc->setFields($data);
        // 添加到索引数据库中
        $this->index->add($this->doc);
    }

    /**
     * 更新索引
     * @param $data
     */
    public function updateIndex($data)
    {
        $this->doc->setFields($data);
        // 添加到索引数据库中
        $this->index->update($this->doc);
    }

    /**
     * 删除索引
     * @param $id
     */
    public function deleteIndex($id)
    {
        $this->index->del($id);
    }

    /**
     * 清空索引
     */
    public function cleanIndex()
    {
        $this->index->clean();
    }

    /**
     * 搜索索引
     * @param $kw 关键词
     * @return mixed
     */
    public function searchIndex($kw,$name,$value)
    {
        $docs = $this->search->setFuzzy()->setQuery($kw)->addWeight($name,$value)->search();
        return $docs;
    }
}