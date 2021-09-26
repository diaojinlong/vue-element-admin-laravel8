<?php

namespace App\Http\Controllers\Manage;

use App\Http\Controllers\Controller;
use App\Models\LogsModel;
use Illuminate\Http\Request;

class LogsController extends Controller
{
    /**
     * 获取操作日志列表
     * @param Request $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function lists(Request $request)
    {
        $admin = $request->get('admin');
        $field = [
            'logs.id',
            'logs.path',
            'logs.info',
            'logs.ip',
            'logs.request_time',
            'admin.name as admin_name'
        ];
        $model = LogsModel::leftJoin('admin', 'logs.admin_id', '=', 'admin.id')->select($field);
        if ($admin['id'] != 1) {
            $model->where('logs.admin_id', $admin['id']);
        }

        $model->orderBy('logs.id', 'desc');
        $data = LogsModel::page($model);
        foreach($data['items'] as &$item){
            $item['request_time'] = format_time($item['request_time']);
        }
        return success($data);
    }
}
