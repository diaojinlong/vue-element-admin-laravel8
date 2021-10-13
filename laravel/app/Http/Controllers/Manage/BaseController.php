<?php

namespace App\Http\Controllers\Manage;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

class BaseController extends Controller
{
    protected $model = null;


    /**
     * 排序
     * @param Request $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function sort(Request $request)
    {
        $id = (int)$request->input('id');
        $sort = (int)$request->input('sort', 0);
        $data = $this->model->find($id);
        if (empty($data)) {
            return error('数据不存在');
        }
        $data->sort = $sort;
        if ($data->save()) {
            return success();
        } else {
            return error('编辑排序失败');
        }
    }
}
