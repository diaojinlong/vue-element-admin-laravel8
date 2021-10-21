<?php

namespace App\Http\Middleware\Manage;

use App\Models\AdminModel;
use Closure;

class Auth
{

    /**
     * 验证是否登录，并且将用户信息存储在request中
     * @param $request
     * @param Closure $next
     * @return \Illuminate\Http\JsonResponse|mixed
     */
    public function handle($request, Closure $next)
    {
        $token = $request->header('Token');
        $token = $token ?: $request->input('token');
        if (empty($token)) {
            return error(40000, '令牌不正确，请重新登录', [], 401);
        }
        $admin = (new AdminModel())->getRowByToken($token);
        if (empty($admin)) {
            return error(40000, '令牌不正确，请重新登录', [], 401);
        }
        if ($admin['status'] != 1) {
            return error('用户已禁用，请联系管理员！');
        }
        $request->attributes->add(['admin' => $admin, 'token'=>$token]);
        return $next($request);
    }
}
