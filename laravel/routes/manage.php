<?php


Route::post('admin/login', 'Manage\AdminController@login');
Route::post('admin/logout', 'Manage\AdminController@logout');

Route::middleware(['admin'])->group(function () {
    Route::get('admin/info', 'Manage\AdminController@info');
    Route::post('admin/edit_password', 'Manage\AdminController@editPassword');
    Route::get('admin/details', 'Manage\AdminController@details')->middleware('permission:admin/edit|admin/add');
    Route::get('admin/lists', 'Manage\AdminController@lists')->middleware('permission');
    Route::post('admin/add', 'Manage\AdminController@add')->middleware('permission');
    Route::post('admin/edit', 'Manage\AdminController@edit')->middleware('permission');
    Route::post('admin/del', 'Manage\AdminController@del')->middleware('permission');

    Route::get('role/details', 'Manage\RoleController@details')->middleware('permission:role/edit|role/add');
    Route::get('role/lists', 'Manage\RoleController@lists')->middleware('permission');
    Route::post('role/add', 'Manage\RoleController@add')->middleware('permission');
    Route::post('role/edit', 'Manage\RoleController@edit')->middleware('permission');
    Route::post('role/del', 'Manage\RoleController@del')->middleware('permission');

    Route::get('menu/details', 'Manage\MenuController@details')->middleware('permission:menu/edit|menu/add');
    Route::get('menu/lists', 'Manage\MenuController@lists')->middleware('permission');
    Route::post('menu/add', 'Manage\MenuController@add')->middleware('permission');
    Route::post('menu/edit', 'Manage\MenuController@edit')->middleware('permission');
    Route::post('menu/del', 'Manage\MenuController@del')->middleware('permission');

    Route::get('public/role', 'Manage\PublicController@role');
    Route::get('public/menu_tree', 'Manage\PublicController@menuTree');
});

