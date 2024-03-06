<?php

namespace TyrantG\LaravelScaffold\Http;

use Illuminate\Contracts\Validation\Validator;
use Illuminate\Foundation\Http\FormRequest as BaseFormRequest;
use Illuminate\Validation\ValidationException;

class FormRequest extends BaseFormRequest
{
    protected $stopOnFirstFailure = true;

    protected function failedValidation(Validator $validator)
    {
        throw (new ValidationException($validator))
            ->status(200)
            ->errorBag($validator->errors()->first());
    }
}
