<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class AdminTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $date = date('Y-m-d H:i:s');
        DB::table('admin')->insert(
            [
                [
                    'id' => 1,
                    'name' => 'Admin',
                    'username' => 'admin',
                    'password' => md5(md5('123456') . $date),
                    'phone' => '',
                    'login_time' => 0,
                    'status' => 1,
                    'created_at' => $date,
                    'updated_at' => $date
                ],
                [
                    'id' => 2,
                    'name' => '人事主管',
                    'username' => 'renshi',
                    'password' => md5(md5('123456') . $date),
                    'phone' => '',
                    'login_time' => 0,
                    'status' => 1,
                    'created_at' => $date,
                    'updated_at' => $date
                ]
            ]
        );
    }
}
