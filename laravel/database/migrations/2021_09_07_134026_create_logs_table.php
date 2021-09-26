<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

class CreateLogsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('logs', function (Blueprint $table) {
            $table->engine = 'InnoDB';
            $table->increments('id');
            $table->char('path', 100)->default('')->comment('访问路径');
            $table->string('info', 100)->default('')->comment('操作描述');
            $table->longText('request')->comment('请求参数');
            $table->longText('response')->comment('响应结果');
            $table->char('ip', 15)->default('')->comment('IP地址');
            $table->char('token', 50)->default('')->comment('令牌');
            $table->integer('admin_id')->default(0)->comment('管理员ID');
            $table->integer('request_time')->default(0)->comment('请求时间');
            $table->timestamps();
        });
        $prefix = DB::getConfig('prefix');
        DB::statement("ALTER TABLE `" . $prefix . "logs` comment '操作日志表'");
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('logs');
    }
}
