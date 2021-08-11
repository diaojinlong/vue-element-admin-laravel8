<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class RoleTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $date = date('Y-m-d H:i:s');
        DB::table('role')->insert(
            [
                [
                    'id' => 1,
                    'name' => '超级管理员',
                    'menus' => '1',
                    'status' => 1,
                    'created_at' => $date,
                    'updated_at' => $date
                ],
                [
                    'id' => 2,
                    'name' => '人事主管',
                    'menus' => '2,3,4,5,6,7,8,9',
                    'status' => 1,
                    'created_at' => $date,
                    'updated_at' => $date
                ]
            ]

        );
    }
}
