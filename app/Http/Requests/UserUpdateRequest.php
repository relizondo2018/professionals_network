<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class UserUpdateRequest extends FormRequest
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
            'email' => 'email|required|exists:users,email',
			'first_name' => 'required|alpha',
			'last_name' => 'required|alpha',
			'password' => 'required|min:8',
			'country' => 'required|max:2|alpha'
        ];
    }
}
