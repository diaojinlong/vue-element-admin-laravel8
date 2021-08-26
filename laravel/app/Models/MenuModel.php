<?php

namespace App\Models;

class MenuModel extends BaseModel
{
    //表名
    protected $table = 'menu';


    // 存储列表数据
    public $listData = [];

    /**
     * 获取后台菜单列表
     * @param int $parentId
     * @param int $level
     * @return $this
     */
    public function getList($parentId = 0, $level = 1)
    {
        $field = array(
            'id',
            'name',
            'api',
            'sort',
            'is_subordinate',
            'created_at',
        );
        $data = $this->where('parent_id', $parentId)->select($field)
            ->orderBy('sort', 'desc')
            ->orderBy('id', 'asc')
            ->get()->toArray();
        foreach ($data as $item) {
            $item['level'] = $level;
            $this->listData[] = $item;
            if ($item['is_subordinate'] == 1) {
                $this->getList($item['id'], $level + 1);
            }
        }
        if ($parentId == 0) {
            return $this->listData;
        }
    }

    /**
     * 查询菜单tree
     * @param int $parentId
     * @return mixed
     */
    public function getTree($parentId = 0)
    {
        $field = array(
            'id',
            'name',
            'is_subordinate'
        );
        $data = $this->where('parent_id', $parentId)->select($field)
            ->orderBy('sort', 'desc')
            ->orderBy('id', 'asc')
            ->get()->toArray();
        foreach ($data as &$item) {
            if ($item['is_subordinate'] == 1) {
                $item['children'] = $this->getTree($item['id']);
            }
        }
        return $data;
    }

    /**
     * 递归查找所有的上级id
     * @param $id
     * @return array
     */
    public function parentIdAll($id)
    {
        $data = [];
        $parentId = $this->where('id', $id)->value('parent_id');
        if ($parentId > 0) {
            $data = array_merge($data, $this->parentIdAll($parentId));
        }
        $data[] = $id;
        return $data;
    }

    /**
     * 查找Vue的tree节点所有id
     * @param $ids
     * @return array
     */
    public function findAllNode($ids)
    {
        $idArr = [];
        foreach ($ids as $id) {
            $idArr = array_merge($idArr, $this->parentIdAll($id));
        }
        return array_values(array_unique($idArr));
    }

    /**
     * 去除节点ID
     * @param $ids
     * @return array
     */
    public function deleteAllNode($ids)
    {
        $data = $this->whereIn('id', $ids)->where('is_subordinate', 2)->pluck('id');
        return $data ? $data->toArray() : [];
    }
}
