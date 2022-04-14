<?php

namespace App\Http\Requests\item;

use Illuminate\Contracts\Validation\Validator;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Http\Exceptions\HttpResponseException;

class StoreItemRequest extends FormRequest
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
            'id' => 'nullable|integer',
            'item_section_id' => 'nullable|integer',
            'name' => 'required|string',
            'item_category_id' => 'nullable|integer',
            'price' => 'nullable|numeric',
            'item_condition_id' => 'nullable|integer',
            'item_warranty_id' => 'nullable|integer',
            'description' => 'nullable|string',
            'is_draft' => 'required|boolean',
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
