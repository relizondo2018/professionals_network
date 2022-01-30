<?php

namespace App\Http\Controllers;

use App\Http\Requests\UserDeleteRequest;
use App\Http\Requests\UserImportRequest;
use App\Http\Requests\UserRegisterRequest;
use App\Http\Requests\UserUpdateRequest;
use App\Http\Resources\UserResource;
use App\Models\User;
use Faker\Factory;
use Illuminate\Http\Request;

class UsersController extends Controller
{
    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\UserRegisterRequest  $request
     * @return \Illuminate\Http\Response
     */
    public function store(UserRegisterRequest $request)
    {
		$user = User::create([
			'email' => $request->input('email'),
			'first_name' => $request->input('first_name'),
			'last_name' => $request->input('last_name'),
			'password' => bcrypt($request->input('password')),
			'country' => $request->input('country')
		]);
		
		return json_success(new UserResource($user), "User Successfully created");

    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\UserUpdateRequest  $request
     * @return \Illuminate\Http\Response
     */
    public function update(UserUpdateRequest $request)
    {
		$user = User::where('email', $request->input('email'))->first();
		$user->first_name = $request->input('first_name');
		$user->last_name = $request->input('last_name');
		$user->country = $request->input('country');
		$user->password = bcrypt($request->input('password'));
		$user->save();

		return json_success(new UserResource($user), "User Updated Successfully");
    }

    /**
     * Remove the specified resource from storage.
     *
     * @return \Illuminate\Http\Response
     */
    public function delete(UserDeleteRequest $request)
    {
        $user = User::where('email', $request->input('email'))->first();
		$user->delete();

		return json_success([], 'User Deleted Successfully');
    }

	public function import(UserImportRequest $request) {
		try {
			for ($i=0; $i < $request->input('number_of_users'); $i++) { 
				$user = new User();
				$faker = Factory::create();
				$user->first_name = $faker->firstName();
				$user->last_name = $faker->lastName();
				$user->email = $faker->email();
				$user->password = bcrypt($faker->password(8, 12));
				$user->country = $faker->countryCode();
				$user->save();
			}
		} catch (\Throwable $th) {
			return json_error([], $th->getMessage());
		}

		return json_success([], 'Data created successfully');
	}

}
