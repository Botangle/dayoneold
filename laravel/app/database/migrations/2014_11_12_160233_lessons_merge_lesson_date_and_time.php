<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class LessonsMergeLessonDateAndTime extends Migration {

	/**
	 * Run the migrations.
	 *
	 * @return void
	 */
	public function up()
	{
        Schema::table('lessons', function(Blueprint $table){
                $table->dateTime('lesson_at')->after('tutor');
            });
        DB::update("UPDATE lessons SET lesson_at=CONCAT(lesson_date, ' ', lesson_time)");
	}

	/**
	 * Reverse the migrations.
	 *
	 * @return void
	 */
	public function down()
	{
        Schema::table('lessons', function(Blueprint $table){
                $table->dropColumn('lesson_at');
            });
	}

}
