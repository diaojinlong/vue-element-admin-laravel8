<?php

if (!function_exists('response_json')) {
    /**
     * 返回json数据
     * @param int $status
     * @param string $msg
     * @param array $data
     * @param int $code
     * @return \Illuminate\Http\JsonResponse
     */
    function response_json($status = 0, $msg = '', $data = [], $code = 200)
    {
        $response = [
            'status' => $status,
            'msg' => $msg,
            'data' => $data
        ];
        return response()->json($response, $code);
    }
}

if (!function_exists('success')) {
    /**
     * 成功
     * @param int $status
     * @param string $msg
     * @param array $data
     * @param int $code
     * @return \Illuminate\Http\JsonResponse
     */
    function success($status = 0, $msg = 'ok', $data = [], $code = 200)
    {
        if (is_array($status)) {
            $data = $status;
            $status = 0;
        }
        return response_json($status, $msg, $data, $code);
    }
}

if (!function_exists('error')) {
    /**
     * 失败
     * @param int $status
     * @param string $msg
     * @param array $data
     * @param int $code
     * @return \Illuminate\Http\JsonResponse
     */
    function error($status = 40000, $msg = 'error', $data = [], $code = 200)
    {
        if (!is_integer($status)) {
            if (is_array($status)) {
                $data = $status;
                $status = 40000;
            }
            if (is_string($status)) {
                $msg = $status;
                $status = 40000;
            }
        }
        return response_json($status, $msg, $data, $code);
    }
}

if (!function_exists('format_time')) {
    /**
     * 转换时间戳
     * @param int $time
     * @return string
     */
    function format_time($time)
    {
        if ($time) {
            return date('Y-m-d H:i:s', $time);
        }
        return '';
    }
}

if (!function_exists('format_day')) {
    /**
     * 转换时间戳
     * @param int $time
     * @return string
     */
    function format_day($time)
    {
        if ($time) {
            return date('Y-m-d', $time);
        }
        return '';
    }
}
