<?php

namespace App\Http\Requests\topic;

use Illuminate\Contracts\Validation\Validator;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Http\Exceptions\HttpResponseException;

class StoreTopicRequest extends FormRequest
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
            'topic_section_id' => 'required|integer',
            'name' => 'required|string',
            'description' => 'required|string',
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
