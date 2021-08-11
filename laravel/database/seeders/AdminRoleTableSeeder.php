<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class AdminRoleTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $date = date('Y-m-d H:i:s');
        DB::table('admin_role')->insert([
            [
                'id' => 1,
                'admin_id' => 1,
                'role_id' => 1,
                'created_at' => $date,
                'updated_at' => $date
            ],
            [
                'id' => 2,
                'admin_id' => 2,
                'role_id' => 2,
                'created_at' => $date,
                'updated_at' => $date
            ]
        ]);
    }
}
