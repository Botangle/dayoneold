<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class UserAddTimezone extends Migration {

	/**
	 * Run the migrations.
	 *
	 * @return void
	 */
	public function up()
	{
        Schema::table('users', function(Blueprint $table){
                $table->dropColumn('timezone');
            });
		Schema::table('users', function(Blueprint $table){
                $table->string('timezone')->default('UTC');
                $table->string('timezone_update', 10)->default('auto'); // ask, never, auto
            });
	}

	/**
	 * Reverse the migrations.
	 *
	 * @return void
	 */
	public function down()
	{
        Schema::table('users', function(Blueprint $table){
                $table->dropColumn('timezone_update');
            });
	}

}
