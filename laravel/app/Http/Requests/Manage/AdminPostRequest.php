<?php

namespace App\Http\Requests\Manage;

use Illuminate\Foundation\Http\FormRequest;
use App\Http\Requests\CommonRequest;
use Illuminate\Http\Request;
use Illuminate\Validation\Rule;
use App\Rules\MobilePhone;

class AdminPostRequest extends FormRequest
{
    use CommonRequest;

    /**
     * Determine if the user is authorized to make this request.
     *
     * @return bool
     */
    public function authorize()
    {
        return true;
    }

    public function rules(Request $request)
    {
        $id = $request->input('id', 0);
        $rule = [
            'username' => ['required', 'string', Rule::unique('admin')->ignore($id)],
            'name' => ['required', 'string'],
            'password' => ['required', 'string']
        ];
        $phone = $request->input('phone', '');
        if ($phone != '') {
            $rule['phone'] = [new MobilePhone];
        }
        return $rule;
    }

    public function attributes()
    {
        return [
            'username' => '用户名',
            'name' => '姓名',
            'phone' => '手机号'
        ];
    }

}
