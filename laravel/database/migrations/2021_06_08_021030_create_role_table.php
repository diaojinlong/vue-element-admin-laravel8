<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

class CreateRoleTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {

        Schema::create('role', function (Blueprint $table) {
            $table->engine = 'InnoDB';
            $table->increments('id');
            $table->char('name', 32)->default('')->comment('角色名称');
            $table->text('menus')->comment('菜单权限');
            $table->tinyInteger('status')->default(1)->comment('状态:1=正常,2=禁用');
            $table->timestamps();
        });
        $prefix = DB::getConfig('prefix');
        DB::statement("ALTER TABLE `" . $prefix . "role` comment '角色表'");
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('role');
    }
}
