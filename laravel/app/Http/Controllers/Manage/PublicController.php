<?php

namespace App\Http\Controllers\Manage;

use App\Http\Controllers\Controller;
use App\Models\MenuModel;
use App\Models\RoleModel;
use Illuminate\Http\Request;

class PublicController extends Controller
{
    /**
     * 获取所有角色
     * @param Request $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function role(Request $request)
    {
        $status = $request->input('status', 0);
        $field = [
            'id',
            'name'
        ];
        $model = RoleModel::select($field);
        if ($status) {
            $model->where('status', $status);
        }
        $items = $model->get();
        return success(['items' => $items ? $items->toArray() : []]);
    }


    /**
     * 获取菜单tree
     * @param Request $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function menuTree(Request $request)
    {
        $tree = (new MenuModel())->getTree();
        return success(['items' => $tree]);
    }
}
