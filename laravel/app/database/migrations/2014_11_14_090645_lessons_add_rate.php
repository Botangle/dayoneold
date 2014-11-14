<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class LessonsAddRate extends Migration {

	/**
	 * Run the migrations.
	 *
	 * @return void
	 */
	public function up()
	{
        Schema::table('lessons', function(Blueprint $table){
                $table->float('rate')->after('subject');
                $table->string('rate_type', 10)->after('rate');
                $table->dropColumn('lesson_date');
                $table->dropColumn('lesson_time');
                $table->dropColumn('ampm');
            });
        $lessons = Lesson::all();
        foreach($lessons as $lesson){
            $lesson->setRateFromTutor();
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
                $table->dropColumn('rate');
                $table->dropColumn('rate_type');
                $table->date('lesson_date');
                $table->time('lesson_time');
                $table->string('ampm', 2);
            });
	}

}
