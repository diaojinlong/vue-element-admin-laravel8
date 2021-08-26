<?php

namespace App\Models;

class AdminRoleModel extends BaseModel
{
    //表名
    protected $table = 'admin_role';


    /**
     * 获取用户角色名称
     * @param $adminId
     * @return array
     */
    public function getRoleName($adminId)
    {
        $names = $this->join('role', 'admin_role.role_id', '=', 'role.id')
            ->where('admin_role.admin_id', $adminId)
            ->pluck('role.name');
        return $names ? $names->toArray() : [];
    }

    /**
     * 获取用户角色ID
     * @param $adminId
     * @return array
     */
    public function getRoleId($adminId)
    {
        $ids = $this->join('role', 'admin_role.role_id', '=', 'role.id')
            ->where('admin_role.admin_id', $adminId)
            ->pluck('role.id');
        return $ids ? $ids->toArray() : [];
    }

    /**
     * 更新用户角色
     * @param $adminId
     * @param $roleIds
     */
    public function updateAdminRole($adminId, $roleIds)
    {
        $this->where('admin_id', $adminId)->delete();
        foreach ($roleIds as $roleId) {
            $model = new self();
            $model->admin_id = $adminId;
            $model->role_id = $roleId;
            $model->save();
        }
    }
}
