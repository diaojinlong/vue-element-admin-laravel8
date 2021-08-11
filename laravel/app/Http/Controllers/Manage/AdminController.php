<?php

namespace App\Http\Controllers\Manage;

use App\Http\Controllers\Controller;
use App\Models\AdminModel;
use App\Models\AdminRoleModel;
use App\Models\BaseModel;
use Illuminate\Http\Request;
use Illuminate\Support\Str;

class AdminController extends Controller
{
    /**
     * 管理员登录接口
     * @param Request $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function login(Request $request)
    {
        $username = (string)$request->input('username', '');
        $password = (string)$request->input('password', '');
        $model = new AdminModel();
        $admin = $model->getRowByUsername($username);
        if (empty($admin)) {
            return error('管理员不存在');
        }
        if ($admin['status'] == 2) {
            return error('管理员已禁用');
        }
        if ($model->verifyPassword($admin, $password) === false) {
            return error('密码不正确');
        }
        $time = time();
        $model->updateLoginTime($admin['id'], $time);
        $admin['login_time'] = $time;
        $token = (string)Str::uuid();
        $model->setToken($admin['id'], $token);
        $admin['token'] = $token;
        $model->setInfo($admin);
        $data = [
            'token' => $token
        ];
        return success($data);
    }

    /**
     * 获取管理员信息及权限
     * @param Request $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function info(Request $request)
    {
        $admin = $request->get('admin');
        $permission = (new AdminModel())->getPermission($admin['id']);
        $data = [
            'name' => $admin['name'],
            'permissions' => $permission,
            'avatar' => config('app.url') . '/img/portrait.jpg',
            'introduction' => $admin['id'] == 1 ? '我是一个超级管理员' : '我是一个普通管理员'
        ];
        return success($data);
    }


    /**
     * 获取管理员列表
     * @param Request $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function lists(Request $request)
    {
        $status = $request->input('status', 0);
        $username = $request->input('username', '');
        $name = $request->input('name', '');
        $sort = $request->input('sort', '');
        $field = array(
            'id',
            'name',
            'phone',
            'username',
            'status',
            'login_time',
            'created_at'
        );
        $model = AdminModel::select($field);
        if ($status) {
            $model->where('status', $status);
        }
        if ($username) {
            $model->where('username', 'like', "%$username%");
        }
        if ($name) {
            $model->where('name', 'like', "%$name%");
        }
        if ($sort == '+id') {
            $sort = 'asc';
        } else {
            $sort = 'desc';
        }
        $model->orderBy('id', $sort);
        $data = BaseModel::page($model);
        foreach ($data['items'] as &$item) {
            $item['login_time'] = format_time($item['login_time']);
            $item['role'] = (new AdminRoleModel())->getRoleName($item['id']);
        }
        return success($data);
    }

    /**
     * 新增管理员
     * @param Request $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function add(Request $request)
    {
        $username = $request->input('username', '');
        $phone = (string)$request->input('phone', '');
        $password = $request->input('password', '');
        $name = $request->input('name', '');
        $status = $request->input('status', 1);
        $role = (array)$request->input('role', []);
        $count = AdminModel::where('username', $username)->count();
        if ($count > 0) {
            return error('用户名重复');
        }
        $date = format_time(time());
        $model = new AdminModel();
        $model->username = $username;
        $model->name = $name;
        $model->phone = $phone;
        $model->password = md5(md5($password) . $date);
        $model->status = $status;
        $model->created_at = $date;
        if ($model->save()) {
            (new AdminRoleModel())->updateAdminRole($model->id, $role);
            return success();
        } else {
            return error();
        }
    }

    /**
     * 获取用户详情
     * @param Request $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function details(Request $request)
    {
        $id = $request->input('id', 0);
        $field = array(
            'id',
            'username',
            'name',
            'phone',
            'password',
            'status'
        );
        $data = AdminModel::where('id', $id)->select($field)->first()->toArray();
        $data['role'] = (new AdminRoleModel())->getRoleId($data['id']);
        return success(['item' => $data]);
    }


    /**
     * 编辑管理员
     * @param Request $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function edit(Request $request)
    {
        $id = $request->input('id', 0);
        if ($id === 1) {
            return error('超级管理员不可编辑');
        }
        $username = $request->input('username', '');
        $phone = (string)$request->input('phone', '');
        $password = $request->input('password', '');
        $name = $request->input('name', '');
        $status = $request->input('status', 1);
        $role = (array)$request->input('role', []);
        $model = AdminModel::where('id', $id)->first();
        if (empty($model)) {
            return error('用户不存在');
        }
        $count = AdminModel::where('id', '!=', $id)->where('username', $username)->count();
        if ($count > 0) {
            return error('用户名重复');
        }
        $model->username = $username;
        $model->phone = $phone;
        $model->name = $name;
        if ($password !== $model->password) {
            $createdAt = $model->created_at->format('Y-m-d H:i:s');
            $model->password = md5(md5($password) . $createdAt);
        }
        $model->status = $status;
        if ($model->save()) {
            //更新角色
            (new AdminRoleModel())->updateAdminRole($model->id, $role);
            //清除已有权限
            (new AdminModel())->delPermission($model->id);
            return success();
        } else {
            return error();
        }
    }


    /**
     * 删除管理员
     * @param Request $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function del(Request $request)
    {
        $id = $request->input('id', 0);
        if ($id === 1) {
            return error('超级管理员不可删除');
        }
        $model = AdminModel::where('id', $id)->first();
        if (empty($model)) {
            return error('用户不存在');
        }
        if ($model->delete()) {
            $admin = (new AdminModel())->getInfo($model->id);
            if ($admin && isset($admin['token'])) {
                //清除token
                (new AdminModel())->delToken($admin['token']);
                //清除缓存的管理员数据
                (new AdminModel())->delInfo($model->id);
            }
            //清除已有权限
            (new AdminModel())->delPermission($model->id);
            return success();
        } else {
            return error();
        }
    }

    /**
     * 修改密码
     * @param Request $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function editPassword(Request $request)
    {
        $admin = $request->get('admin');
        $password = $request->input('password', '');
        $newPassword = $request->input('new_password', '');
        $model = new AdminModel();
        if ($model->verifyPassword($admin, $password) == false) {
            return error('原密码不正确');
        }
        $model->editPassword($admin, $newPassword);
        $this->logout($request);
        return success();
    }

    /**
     * 退出登录
     * @param Request $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function logout(Request $request)
    {
        $token = $request->header('Token');
        if ($token) {
            (new AdminModel())->delToken($token);
        }
        return success();
    }
}
