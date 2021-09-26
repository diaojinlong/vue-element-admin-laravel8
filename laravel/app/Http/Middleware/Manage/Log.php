<?php

namespace App\Http\Middleware\Manage;

use App\Jobs\LogsJob;
use Closure;

class Log
{

    /**
     * 记录操作日志
     * @param $request
     * @param Closure $next
     * @param String $info
     * @return \Illuminate\Http\JsonResponse|mixed
     */
    public function handle($request, Closure $next, $info)
    {
        // 数组键请参考logs表
        $logData = [
            'path' => $request->url(),
            'info' => $info,
            'ip' => $request->ip(),
            'request' => json_encode($request->all()),
            'response' => '',
            'token' => $request->get('token'),
            'admin_id' => $request->get('admin')['id'],
            'request_time' => time()
        ];
        $response = $next($request);
        $contentType = $response->headers->get('content-type');
        if (in_array($contentType, ['application/json'])) {
            $logData['response'] = $response->getContent();
        }
        $logJobNew = true;
        if($logJobNew){
            LogsJob::dispatchNow($logData); //同步队列，写入接口响应速度不如异步队列
        }else{
            LogsJob::dispatch($logData); //异步队列，需要自己使用Supervisor创建监听进程
        }
        return $response;
    }
}
