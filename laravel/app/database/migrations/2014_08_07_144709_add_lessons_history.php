<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AddLessonsHistory extends Migration {

	/**
	 * Run the migrations.
	 *
	 * @return void
	 */
	public function up()
	{
		Schema::table('lessons', function($table){
                $table->text('history');
            });

        // Correct a load of boolean fields that have been created as int(11)
        DB::update("ALTER TABLE lessons CHANGE COLUMN readlesson readlesson tinyint(1) DEFAULT 0;");
        DB::update("ALTER TABLE lessons CHANGE COLUMN readlessontutor readlessontutor tinyint(1) DEFAULT 0;");

        DB::update("ALTER TABLE lessons CHANGE COLUMN laststatus_tutor laststatus_tutor tinyint(1) DEFAULT 0;");
        DB::update("ALTER TABLE lessons CHANGE COLUMN laststatus_student laststatus_student tinyint(1) DEFAULT 0;");

        DB::update("ALTER TABLE lessons CHANGE COLUMN is_confirmed is_confirmed tinyint(1) DEFAULT 0;");

	}

	/**
	 * Reverse the migrations.
	 *
	 * @return void
	 */
	public function down()
	{
		//
	}

}
