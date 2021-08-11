<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

class CreateMenuTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('menu', function (Blueprint $table) {
            $table->engine = 'InnoDB';
            $table->increments('id');
            $table->char('name', 32)->default('')->comment('角色名称');
            $table->char('api', 50)->default('')->comment('权限规则');
            $table->integer('parent_id')->default(0)->comment('上级ID');
            $table->tinyInteger('is_subordinate')->default(1)->comment('是否有下级:1=有,2=无');
            $table->integer('sort')->default(50)->comment('排序数值越大越靠前');
            $table->timestamps();
        });
        $prefix = DB::getConfig('prefix');
        DB::statement("ALTER TABLE `" . $prefix . "menu` comment '菜单权限表'");
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('menu');
    }
}
