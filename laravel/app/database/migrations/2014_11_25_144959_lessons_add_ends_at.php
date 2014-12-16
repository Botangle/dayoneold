<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class LessonsAddEndsAt extends Migration {

	/**
	 * Run the migrations.
	 *
	 * @return void
	 */
	public function up()
	{
        Schema::table('lessons', function(Blueprint $table){
                // When each user was last present for the lesson (includes while waiting for the other to arrive)
                $table->dateTime('ends_at')->after('lesson_at')->index();
            });

        foreach(Lesson::all() as $lesson){
            $lesson->ends_at = $lesson->lesson_at->addMinutes($lesson->duration);
            $lesson->save();
        }
	}

	/**
	 * Reverse the migrations.
	 *
	 * @return void
	 */
	public function down()
	{
		Schema::table('lessons', function(Blueprint $table){
                $table->dropColumn('ends_at');
            });
	}

}
