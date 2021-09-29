<?php
namespace App\Http\Requests;

use Illuminate\Contracts\Validation\Validator;
use Illuminate\Http\Exceptions\HttpResponseException;

trait CommonRequest {

    protected function failedValidation(Validator $validator)
    {
        $errors = $validator->errors();
        throw (new HttpResponseException(error(40001, $errors->first())));
    }

}
