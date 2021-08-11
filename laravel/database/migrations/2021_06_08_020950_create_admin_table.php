<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

class CreateAdminTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('admin', function (Blueprint $table) {
            $table->engine = 'InnoDB';
            $table->increments('id');
            $table->char('name', 32)->default('')->comment('姓名');
            $table->char('username', 32)->default('')->comment('用户名');
            $table->char('password', 32)->default('')->comment('密码');
            $table->char('phone', 11)->default('')->comment('手机号');
            $table->integer('login_time')->default(0)->comment('上次登录时间');
            $table->tinyInteger('status')->default(1)->comment('状态:1=正常,2=禁用');
            $table->timestamps();
        });
        $prefix = DB::getConfig('prefix');
        DB::statement("ALTER TABLE `" . $prefix . "admin` comment '管理员表'");
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('admin');
    }
}
