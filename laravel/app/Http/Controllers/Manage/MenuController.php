<?php

namespace App\Http\Controllers\Manage;

use App\Models\MenuModel;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class MenuController extends BaseController
{

    public function __construct()
    {
        $this->model = new MenuModel();
    }

    /**
     * 获取权限菜单列表
     * @param Request $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function lists(Request $request)
    {
        $list = (new MenuModel())->getList();
        return success(['items' => $list]);
    }


    /**
     * 新增权限菜单
     * @param Request $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function add(Request $request)
    {
        $name = (string)$request->input('name', '');
        $api = (string)$request->input('api', '');
        $sort = (integer)$request->input('sort', 50);
        $parentId = (integer)$request->input('parent_id', 0);
        $model = new MenuModel();
        $model->name = $name;
        $model->api = $api;
        $model->sort = $sort;
        $model->parent_id = $parentId;
        $model->is_subordinate = 2;
        if ($model->save()) {
            if ($parentId != 0) {
                $parent = MenuModel::find($parentId);
                $parent->is_subordinate = 1;
                $parent->save();
            }
            return success();
        } else {
            return error();
        }
    }

    /**
     * 编辑权限菜单
     * @param Request $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function edit(Request $request)
    {
        $id = (integer)$request->input('id', '');
        $name = (string)$request->input('name', '');
        $api = (string)$request->input('api', '');
        $sort = (integer)$request->input('sort', 50);
        $model = MenuModel::find($id);
        $model->name = $name;
        $model->api = $api;
        $model->sort = $sort;
        if ($model->save()) {
            return success();
        } else {
            return error();
        }
    }

    /**
     * 获取权限菜单详情
     * @param Request $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function details(Request $request)
    {
        $id = $request->input('id', 0);
        $field = array(
            'id',
            'name',
            'api',
            'sort',
            'parent_id',
            'is_subordinate'
        );
        $data = MenuModel::where('id', $id)->select($field)->first()->toArray();
        $data['parent_name'] = (string)MenuModel::where('id', $data['parent_id'])->value('name');
        return success(['item' => $data]);
    }

    /**
     * 删除权限菜单详情
     * @param Request $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function del(Request $request)
    {
        $id = $request->input('id', 0);
        DB::beginTransaction();
        $menu = MenuModel::where('id', $id)->first();
        if (!$menu->delete()) {
            DB::rollBack();
            return error('删除失败');
        }
        $count = (int)MenuModel::where('parent_id', $menu->parent_id)->count();
        if ($count == 0) {
            $parent = MenuModel::find($menu->parent_id);
            $parent->is_subordinate = 2;
            if (!$parent->save()) {
                DB::rollBack();
                return error('删除失败');
            }
        }
        DB::commit();
        return success();
    }
}
