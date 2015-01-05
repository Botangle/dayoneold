<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AlterUsersSubjectToVarchar extends Migration {

	/**
	 * Run the migrations.
	 *
	 * @return void
	 */
	public function up()
	{
		DB::update("ALTER TABLE users MODIFY subject VARCHAR(1000)");

        Schema::table('users', function(Blueprint $table){
                $table->index('subject');
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
                $table->dropIndex('users_subject_index');
            });

        DB::update("ALTER TABLE users MODIFY subject TEXT");
	}

}
