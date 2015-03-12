<?php

class UserTableSeeder extends Seeder {

	/**
	 * Run the database seeds.
	 *
	 * @return void
	 */
	public function run()
	{
		DB::table('users')->delete();

		User::create( [
			'id'                => 1,
			'role_id'           => 1,
			'username'          => 'dbaker',
			'password'          => 'PleaseChangeMe',
			'email'             => 'david@acorndevs.com',
			'name'              => 'David',
			'lname'             => 'Baker',
			'activation_key'    => '977619abaf61d3a01a07e9c8aa3ccce0',
			'status'            => 1,
			'is_online'         => 0,
			'terms'             => 0,
		]);
	}

}
