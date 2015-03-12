<?php
/**
 * UserTest.php
 *
 * @author: David Baker <dbaker@acorncomputersolutions.com
 * Date: 3/5/15
 * Time: 12:11 PM
 */

class UserTest extends TestCase {

	public function testFirstNameGetter() {

		$user = new User;
		$user->name = "John";

		$this->assertEquals("John", $user->first_name);
	}

	public function testFirstNameSetter() {

		$user = new User;
		$user->first_name = "John";

		$this->assertEquals("John", $user->name);
	}

	public function testLastNameGetter() {

		$user = new User;
		$user->lname = "Doe";

		$this->assertEquals("Doe", $user->last_name);
	}

	public function testLastNameSetter() {

		$user = new User;
		$user->last_name = "Doe";

		$this->assertEquals("Doe", $user->lname);
	}

	public function testFullNameGetter() {

		$user = new User;
		$user->first_name = "John";
		$user->last_name = "Doe";

		$this->assertEquals("John Doe", $user->full_name);
	}
}
