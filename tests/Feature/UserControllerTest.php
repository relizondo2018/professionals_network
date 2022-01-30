<?php

namespace Tests\Feature;

use Illuminate\Foundation\Testing\DatabaseMigrations;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class UserControllerTest extends TestCase
{

	use DatabaseMigrations;

	CONST BAD_REQUEST = [
		'email' => 'emailfalso',
		'first_name' => 'validacionnumeros123',
		'last_name' => 'validacionessimbolos',
		'password' => 'seisXX',
		'country' =>'validacionNumeros222'
	];

	CONST GOOD_REQUEST = [
		'email' => 'test@crimson.com',
		'first_name' => 'Rene',
		'last_name' => 'Crimson',
		'password' => '12345678',
		'country' => 'MX'
	];

    /**
     * Test validations
     *
     * @return void
     */
    public function test_create_user()
    {
        $response = $this->post('/api/users', self::BAD_REQUEST);
		$response->assertStatus(422);

		$second_response = $this->create_request();
		$second_response->assertSuccessful();	
    }

	public function test_validations_one_by_one()
	{
		$response_email = $this->post('/api/users', [
			'email' => 'fallaenvalidacion',
			'first_name' => 'Rene',
			'last_name' => 'Crimson',
			'password' => '12345678',
			'country' => 'MX']);
		$response_email->assertStatus(422);

		$response_first = $this->post('/api/users', [
			'email' => 'rene@crimson.com',
			'first_name' => '12345',
			'last_name' => 'Crimson',
			'password' => '12345678',
			'country' => 'MX']);
		$response_first->assertStatus(422);

		$response_last = $this->post('/api/users', [
			'email' => 'rene@crimson.com',
			'first_name' => 'Rene',
			'last_name' => '12345',
			'password' => '12345678',
			'country' => 'MX']);
		$response_last->assertStatus(422);

		$response_password = $this->post('/api/users', [
			'email' => 'rene@crimson.com',
			'first_name' => '12345',
			'last_name' => 'Crimson',
			'password' => 'aaaa',
			'country' => 'MX']);
		$response_first->assertStatus(422);
	}

	public function test_update_user()
	{
		$response = $this->create_request();
		$response->assertSuccessful();

		$update = $this->patch('/api/users', [
			'email' => 'test@crimson.com',
			'first_name' => 'Rene',
			'last_name' => 'Edicion',
			'password' => '12345678',
			'country' => 'MX'
		]);
		$update->assertSuccessful();
	}

	public function test_unauthenticated()
	{
		$response = $this->patch('/api/users', [
			'email' => 'testinexistente@crimson.com',
			'first_name' => 'Rene',
			'last_name' => 'Edicion',
			'password' => '12345678',
			'country' => 'MX'
		]);
		$response->assertUnauthorized();
	}

	public function test_delete()
	{
		$response = $this->create_request();
		$response->assertSuccessful();

		$delete = $this->delete('/api/users', [
			'email' => 'test@crimson.com',
			'password' => '12345678',
		]);
		$delete->assertSuccessful();

		$validation = $this->delete('/api/users', [
			'email' => 'test@crimson.com',
			'password' => '12345678',
		]);
		$validation->assertStatus(401);
	}

	protected function create_request() {
		return $this->post('/api/users', self::GOOD_REQUEST);
	}


}
