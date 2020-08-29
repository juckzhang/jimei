<?php
namespace common\helpers;

class TreeHelper {
    private $parentId = 'parent_id';
    private $id       = 'id';

    /**
     * 无限级分类树-初始化配置
     * @param  array $config array('parentId'=>'', 'id' => '')
     */
    public function __construct(array $config = [])
    {
        if (!is_array($config)) return false;
        $this->parentId = (isset($config['parentId'])) ? $config['parentId'] : $this->parentId;
        $this->id = (isset($config['id'])) ? $config['id'] : $this->id;
    }

    /**
     * 获取分类树
     * @param array $data
     * @param int $parentId
     * @return array
     */
    public function getTree(array $data,$parentId = 0)
    {
        $temp = [];
        foreach($data as $key => $item)
        {
            if($item[$this->parentId] == $parentId)
            {
                $item['child'] = $this->getTree($data,$item[$this->id]);
                $temp[] = $item;
            }
        }
        return $temp;
    }
}