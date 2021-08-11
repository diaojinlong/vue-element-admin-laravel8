<?php

namespace App\Http\Controllers\Manage;

use App\Http\Controllers\Controller;
use App\Models\AdminModel;
use App\Models\AdminRoleModel;
use App\Models\BaseModel;
use App\Models\MenuModel;
use App\Models\RoleModel;
use Illuminate\Http\Request;

class RoleController extends Controller
{

    /**
     * 获取角色列表
     * @param Request $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function lists(Request $request)
    {
        $status = $request->input('status', 0);
        $name = $request->input('name', '');
        $sort = $request->input('sort', '');
        $field = array(
            'id',
            'name',
            'status',
            'created_at'
        );
        $model = RoleModel::select($field);
        if ($status) {
            $model->where('status', $status);
        }
        if ($name) {
            $model->where('name', 'like', "%$name%");
        }
        if ($sort == '+id') {
            $sort = 'asc';
        } else {
            $sort = 'desc';
        }
        $model->orderBy('id', $sort);
        $data = BaseModel::page($model);
        return success($data);
    }

    /**
     * 新增角色
     * @param Request $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function add(Request $request)
    {
        $name = $request->input('name', '');
        $status = $request->input('status', 1);
        $menus = (array)$request->input('menus', []);
        if (empty($menus)) {
            return error('权限必选');
        }
        $menus = (new MenuModel())->findAllNode($menus);
        $model = new RoleModel();
        $model->name = $name;
        $model->menus = join(',', $menus);
        $model->status = $status;
        if ($model->save()) {
            return success();
        } else {
            return error();
        }
    }

    /**
     * 获取角色详情
     * @param Request $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function details(Request $request)
    {
        $id = $request->input('id', 0);
        $field = array(
            'id',
            'name',
            'menus',
            'status'
        );
        $data = RoleModel::where('id', $id)->select($field)->first()->toArray();
        $data['menus'] = (new MenuModel())->deleteAllNode(explode(',', $data['menus']));
        return success(['item' => $data]);
    }


    /**
     * 编辑角色
     * @param Request $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function edit(Request $request)
    {
        $id = $request->input('id', 0);
        if ($id === 1) {
            return error('角色不可编辑');
        }
        $name = $request->input('name', '');
        $status = $request->input('status', 1);
        $menus = (array)$request->input('menus', []);
        if (empty($menus)) {
            return error('权限必选');
        }
        $menus = (new MenuModel())->findAllNode($menus);
        $model = RoleModel::where('id', $id)->first();
        if (empty($model)) {
            return error('角色不存在');
        }
        $model->menus = join(',', $menus);
        $model->name = $name;
        $model->status = $status;
        if ($model->save()) {
            //查询所有该角色的管理员
            $adminIds = AdminRoleModel::where('role_id', $id)->pluck('admin_id');
            foreach ($adminIds as $adminId) {
                //清除以缓存的管理员权限
                (new AdminModel())->delPermission($adminId);
            }
            return success();
        } else {
            return error();
        }
    }


    /**
     * 删除角色
     * @param Request $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function del(Request $request)
    {
        $id = $request->input('id', 0);
        if ($id === 1) {
            return error('角色不可删除');
        }
        $model = RoleModel::where('id', $id)->first();
        if (empty($model)) {
            return error('角色不存在');
        }
        $count = AdminRoleModel::where('role_id', $id)->count();
        if ($count > 0) {
            return error('部分用户正在使用该角色，无法删除！');
        }
        if ($model->delete()) {
            return success();
        } else {
            return error();
        }
    }


}
