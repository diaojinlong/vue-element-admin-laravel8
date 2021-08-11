<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

class CreateAdminRoleTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('admin_role', function (Blueprint $table) {
            $table->engine = 'InnoDB';
            $table->increments('id');
            $table->integer('admin_id')->default(0)->comment('管理员id');
            $table->integer('role_id')->default(0)->comment('角色id');
            $table->timestamps();
        });
        $prefix = DB::getConfig('prefix');
        DB::statement("ALTER TABLE `" . $prefix . "admin_role` comment '管理员角色关系表'");
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('admin_role');
    }
}
