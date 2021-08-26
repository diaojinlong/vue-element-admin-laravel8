# vue-element-admin-laravel8
基于服务端PHP 【laravel8.4】框架 与【vue-element-admin】框架搭建的角色权限管理基础系统。


#安装运行页面效果
![image](image/login.png)


#laravel安装
1.重命名.env.example文件为.env
2.编辑.env中的数据库配置及redis配置
3.命令行运行：composer install
4.命令行运行：php artisan key:generate
5.命令行运行：php artisan migrate
6.命令行运行：php artisan db:seed

#vue安装
1.命令行运行:npm install
2.配置.env开头的文件内的VUE_APP_BASE_API=自己服务端域名
3.命令行运行:npm run dev