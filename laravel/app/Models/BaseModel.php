<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class BaseModel extends Model
{
    public static function page($model)
    {
        $limit = request()->input('limit', 20);
        $res = json_decode($model->paginate($limit)->toJson(), true);
        return array(
            'total' => $res['total'],
            'page' => $res['current_page'],
            'limit' => intval($res['per_page']),
            'page_count' => $res['to'],
            'items' => $res['data']
        );
        unset($res);
        return $data;
    }
}
