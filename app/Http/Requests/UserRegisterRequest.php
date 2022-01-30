<?php

namespace App\Http\Requests;

use App\Http\Controllers\Controller;
use Illuminate\Contracts\Validation\Validator;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\ValidationException;

class UserRegisterRequest extends FormRequest
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
            'email' => 'email|required|unique:users,email',
			'first_name' => 'required|alpha',
			'last_name' => 'required|alpha',
			'password' => 'required|min:8',
			'country' => 'required|max:2|alpha'
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
