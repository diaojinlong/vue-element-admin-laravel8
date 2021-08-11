<?php

namespace App\Http\Middleware\Manage;

use App\Models\AdminModel;
use Closure;

class Permission
{

    /**
     * 验证管理员是否有权限操作，该中间件需用在Auth中间件后面
     * @param $request
     * @param Closure $next
     * @param string $path
     * @return \Illuminate\Http\JsonResponse|mixed
     */
    public function handle($request, Closure $next, $path = '')
    {
        if (empty($path)) {
            $path = str_replace('manage/', '', $request->path());
        }
        $pathArr = explode('|', $path);
        $admin = $request->get('admin');
        $permission = (new AdminModel())->getPermission($admin['id']);
        if (in_array('*', $permission)) {
            return $next($request);
        }
        $isPermission = false;
        foreach ($pathArr as $path) {
            if (in_array($path, $permission)) {
                $isPermission = true;
                break;
            }
        }
        if ($isPermission) {
            return $next($request);
        } else {
            return error('无权限访问');
        }

    }
}
