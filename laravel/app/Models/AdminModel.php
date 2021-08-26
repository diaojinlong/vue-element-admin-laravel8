<?php

namespace App\Models;

use Illuminate\Support\Facades\Redis;

class AdminModel extends BaseModel
{
    //表名
    protected $table = 'admin';

    //存储token的redis键
    private $adminTokenRedisKey = 'admin:token:';

    //存储管理员信息的redis键
    private $adminInfoRedisKey = 'admin:info:';

    //存储管理员权限的redis键
    private $adminPermissionRedisKey = 'admin:permission:';


    /**
     * 依据用户名查询用户
     * @param $username
     * @return mixed
     */
    public function getRowByUsername($username)
    {
        $admin = $this->where('username', $username)->first();
        return $admin ? $admin->toArray() : [];
    }

    /**
     * 依据用户token获取用户信息
     * @param $token
     * @return array|mixed
     */
    public function getRowByToken($token)
    {
        $id = Redis::get($this->adminTokenRedisKey . $token);
        if ($id) {
            return $this->getInfo($id);
        } else {
            return [];
        }
    }

    /**
     * 验证密码
     * @param $admin
     * @param $password
     * @return bool true=正确,false=不正确
     */
    public function verifyPassword($admin, $password)
    {
        return $admin['password'] === md5(md5($password) . $admin['created_at']);
    }

    /**
     * 修改密码
     * @param $admin
     * @param $password
     * @return mixed
     */
    public function editPassword($admin, $password)
    {
        return $this->where('id', $admin['id'])->update([
            'password' => md5(md5($password) . $admin['created_at'])
        ]);
    }

    /**
     * 设置token
     * @param $id
     * @param $token
     */
    public function setToken($id, $token)
    {
        Redis::set($this->adminTokenRedisKey . $token, $id, 7200);
    }

    /**
     * 删除token
     * @param $token
     */
    public function delToken($token)
    {
        Redis::del($this->adminTokenRedisKey . $token);
    }

    /**
     * 设置管理员信息
     * @param $admin
     */
    public function setInfo($admin)
    {
        Redis::hMSet($this->adminInfoRedisKey . $admin['id'], $admin);
    }

    /**
     * 获取管理员信息
     * @param $id
     * @return mixed
     */
    public function getInfo($id)
    {
        return Redis::hGetAll($this->adminInfoRedisKey . $id);
    }

    /**
     * 删除管理员信息
     * @param $id
     * @return mixed
     */
    public function delInfo($id)
    {
        Redis::del($this->adminInfoRedisKey . $id);
    }

    /**
     * 更新最后登录时间
     * @param $id
     * @param int $time
     */
    public function updateLoginTime($id, $time = 0)
    {
        $time = $time ?: time();
        $this->where('id', $id)->update(['login_time' => $time]);
    }

    /**
     * 获取管理员的权限
     * @param $id
     * @return mixed
     */
    public function getPermission($id)
    {
        $permission = Redis::hGetAll($this->adminPermissionRedisKey . $id);
        if (empty($permission)) {
            $menus = AdminRoleModel::join('role', 'admin_role.role_id', '=', 'role.id')
                ->where('admin_role.admin_id', $id)
                ->pluck('role.menus')->toArray();
            $menusStr = join(',', $menus);
            $menusArr = explode(',', $menusStr);
            $menusArr = array_unique($menusArr);
            $permission = MenuModel::whereIn('id', $menusArr)->where('api', '<>', '')->pluck('api')->toArray();
            Redis::hMSet($this->adminPermissionRedisKey . $id, $permission);
        }
        return $permission;
    }

    /**
     * 清除缓存的管理员权限
     * @param $id
     */
    public function delPermission($id)
    {
        Redis::del($this->adminPermissionRedisKey . $id);
    }
}
