<?php

namespace App\Models;

class RoleModel extends BaseModel
{
    //表名
    protected $table = 'role';


    protected $casts = [
        'created_at' => 'datetime:Y-m-d H:i:s',
        'updated_at' => 'datetime:Y-m-d H:i:s',
    ];
}
