<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class MenuTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $date = date('Y-m-d H:i:s');
        DB::table('menu')->insert([
            [
                'id' => 1,
                'name' => '所有权限',
                'api' => '*',
                'parent_id' => 0,
                'is_subordinate' => 2,
                'sort' => 100,
                'created_at' => $date,
                'updated_at' => $date
            ],
            [
                'id' => 2,
                'name' => '管理员管理',
                'api' => '',
                'parent_id' => 0,
                'is_subordinate' => 1,
                'sort' => 50,
                'created_at' => $date,
                'updated_at' => $date
            ],
            [
                'id' => 3,
                'name' => '管理员列表',
                'api' => 'admin/lists',
                'parent_id' => 2,
                'is_subordinate' => 2,
                'sort' => 50,
                'created_at' => $date,
                'updated_at' => $date
            ],
            [
                'id' => 4,
                'name' => '新增管理员',
                'api' => 'admin/add',
                'parent_id' => 2,
                'is_subordinate' => 2,
                'sort' => 50,
                'created_at' => $date,
                'updated_at' => $date
            ],
            [
                'id' => 5,
                'name' => '编辑管理员',
                'api' => 'admin/edit',
                'parent_id' => 2,
                'is_subordinate' => 2,
                'sort' => 50,
                'created_at' => $date,
                'updated_at' => $date
            ],
            [
                'id' => 6,
                'name' => '删除管理员',
                'api' => 'admin/del',
                'parent_id' => 2,
                'is_subordinate' => 2,
                'sort' => 50,
                'created_at' => $date,
                'updated_at' => $date
            ],
            [
                'id' => 7,
                'name' => '角色管理',
                'api' => '',
                'parent_id' => 0,
                'is_subordinate' => 1,
                'sort' => 50,
                'created_at' => $date,
                'updated_at' => $date
            ],
            [
                'id' => 8,
                'name' => '角色列表',
                'api' => 'role/lists',
                'parent_id' => 7,
                'is_subordinate' => 2,
                'sort' => 50,
                'created_at' => $date,
                'updated_at' => $date
            ],
            [
                'id' => 9,
                'name' => '新增角色',
                'api' => 'role/add',
                'parent_id' => 7,
                'is_subordinate' => 2,
                'sort' => 50,
                'created_at' => $date,
                'updated_at' => $date
            ],
            [
                'id' => 10,
                'name' => '编辑角色',
                'api' => 'role/edit',
                'parent_id' => 7,
                'is_subordinate' => 2,
                'sort' => 50,
                'created_at' => $date,
                'updated_at' => $date
            ],
            [
                'id' => 11,
                'name' => '删除角色',
                'api' => 'role/del',
                'parent_id' => 7,
                'is_subordinate' => 2,
                'sort' => 50,
                'created_at' => $date,
                'updated_at' => $date
            ],
            [
                'id' => 12,
                'name' => '权限管理',
                'api' => '',
                'parent_id' => 0,
                'is_subordinate' => 1,
                'sort' => 50,
                'created_at' => $date,
                'updated_at' => $date
            ],
            [
                'id' => 13,
                'name' => '权限列表',
                'api' => 'menu/lists',
                'parent_id' => 12,
                'is_subordinate' => 2,
                'sort' => 50,
                'created_at' => $date,
                'updated_at' => $date
            ],
            [
                'id' => 14,
                'name' => '新增权限',
                'api' => 'menu/add',
                'parent_id' => 12,
                'is_subordinate' => 2,
                'sort' => 50,
                'created_at' => $date,
                'updated_at' => $date
            ],
            [
                'id' => 15,
                'name' => '编辑权限',
                'api' => 'menu/edit',
                'parent_id' => 12,
                'is_subordinate' => 2,
                'sort' => 50,
                'created_at' => $date,
                'updated_at' => $date
            ],
            [
                'id' => 16,
                'name' => '删除权限',
                'api' => 'menu/del',
                'parent_id' => 12,
                'is_subordinate' => 2,
                'sort' => 50,
                'created_at' => $date,
                'updated_at' => $date
            ]
        ]);
    }
}
