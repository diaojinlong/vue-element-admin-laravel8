<?php


Route::post('admin/login', 'Manage\AdminController@login');
Route::post('admin/logout', 'Manage\AdminController@logout');

Route::middleware(['admin'])->group(function () {
    // 登录成功获取个人信息
    Route::get('admin/info', 'Manage\AdminController@info');

    // 修改密码
    Route::post('admin/edit_password', 'Manage\AdminController@editPassword');

    // 日志列表
    Route::get('logs/lists', 'Manage\LogsController@lists');

    // 人员管理
    Route::get('admin/details', 'Manage\AdminController@details')->middleware('permission:admin/edit|admin/add');
    Route::get('admin/lists', 'Manage\AdminController@lists')->middleware(['permission', 'manage.log:人员管理-查看']);
    Route::post('admin/add', 'Manage\AdminController@add')->middleware(['permission', 'manage.log:人员管理-新增']);
    Route::post('admin/edit', 'Manage\AdminController@edit')->middleware(['permission', 'manage.log:人员管理-编辑']);
    Route::post('admin/del', 'Manage\AdminController@del')->middleware(['permission', 'manage.log:人员管理-删除']);

    // 角色管理
    Route::get('role/details', 'Manage\RoleController@details')->middleware('permission:role/edit|role/add');
    Route::get('role/lists', 'Manage\RoleController@lists')->middleware(['permission', 'manage.log:角色管理-查看']);
    Route::post('role/add', 'Manage\RoleController@add')->middleware(['permission', 'manage.log:角色管理-新增']);
    Route::post('role/edit', 'Manage\RoleController@edit')->middleware(['permission', 'manage.log:角色管理-编辑']);
    Route::post('role/del', 'Manage\RoleController@del')->middleware(['permission', 'manage.log:角色管理-删除']);

    // 权限管理
    Route::get('menu/details', 'Manage\MenuController@details')->middleware('permission:menu/edit|menu/add');
    Route::get('menu/lists', 'Manage\MenuController@lists')->middleware(['permission', 'manage.log:权限管理-查看']);
    Route::post('menu/add', 'Manage\MenuController@add')->middleware(['permission', 'manage.log:权限管理-新增']);
    Route::post('menu/edit', 'Manage\MenuController@edit')->middleware(['permission', 'manage.log:权限管理-编辑']);
    Route::post('menu/del', 'Manage\MenuController@del')->middleware(['permission', 'manage.log:权限管理-删除']);

    Route::get('public/role', 'Manage\PublicController@role');
    Route::get('public/menu_tree', 'Manage\PublicController@menuTree');
});

