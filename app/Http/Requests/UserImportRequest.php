<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Contracts\Validation\Validator;
use Illuminate\Validation\ValidationException;

class UserImportRequest extends FormRequest
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
        	'number_of_users' => 'required|numeric|max:50',
        ];
    }

	/**
     * Change Json Response values to add result and reason.
     *
     * @return Response
     */
	protected function failedValidation(Validator $validator)
	{
		$response = json_error(['errors' => $validator->errors()], "The given data is invalid", 422);
		
		throw new ValidationException($validator, $response);
	}
}
