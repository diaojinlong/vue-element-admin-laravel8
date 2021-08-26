<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class BaseModel extends Model
{
    protected $casts = [
        'created_at' => 'datetime:Y-m-d H:i:s',
        'updated_at' => 'datetime:Y-m-d H:i:s',
    ];


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
    
    protected function serializeDate(\DateTimeInterface $date)
    {
        return $date->format('Y-m-d H:i:s');
    }
}
