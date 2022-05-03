<?php

namespace App\Http\Requests\authentication;

use Illuminate\Contracts\Validation\Validator;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Http\Exceptions\HttpResponseException;
use Illuminate\Validation\ValidationException;

class RegisterRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     *
     * @return bool
     */
    public function authorize()
    {
        return true;
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array
     */
    public function rules()
    {
        return [
            'name' => 'required|string',
            'username' => 'required|unique:users',
            'email' => 'required|unique:users',
            'location' => 'required|string',
            'password' => 'required|string',
            'contact_number' => 'required|string',
        ];
    }

    protected function failedValidation(Validator $validator)
    {
        throw new HttpResponseException(
            customResponse()
                ->data(null)
                ->message($validator->errors()->all()[0])
                ->failed(422)
                ->generate()
        );
    }

    protected $stopOnFirstFailure = true;
}
