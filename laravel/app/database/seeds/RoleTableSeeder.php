<?php

class RoleTableSeeder extends Seeder {

	/**
	 * Run the database seeds.
	 *
	 * @return void
	 */
	public function run()
	{
		DB::table('roles')->delete();

		Role::create( [
			'id'                => 1,
			'title'             => 'Admin',
			'alias'             => 'admin',
		]);

		Role::create( [
			'id'                => 2,
			'title'             => 'Expert',
			'alias'             => 'expert',
		]);

		Role::create( [
			'id'                => 4,
			'title'             => 'Student',
			'alias'             => 'student',
		]);
	}

}
