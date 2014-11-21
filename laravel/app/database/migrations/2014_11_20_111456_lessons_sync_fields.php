<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class LessonsSyncFields extends Migration {

	/**
	 * Run the migrations.
	 *
	 * @return void
	 */
	public function up()
	{
        Schema::table('lessons', function(Blueprint $table){
                // Records the lesson time used up (i.e. chargeable) in seconds
                $table->integer('seconds_used');

                // When each user was last present for the lesson (includes while waiting for the other to arrive)
                $table->dateTime('student_last_present_at');
                $table->dateTime('tutor_last_present_at');

                // These fields (combined with the two above) are used to negotiate the synced start time
                // (or restart time if the lesson is interrupted for any reason)
                $table->string('sync_status', 30)->default('waiting');
                $table->dateTime('synced_start_at');

                // These fields are just collecting interest info, which may be useful later e.g. for handling no-shows
                // How many seconds each person has had to wait for the other since the official lesson start time
                $table->integer('student_seconds_wait');
                $table->integer('tutor_seconds_wait');
            });
	}

	/**
	 * Reverse the migrations.
	 *
	 * @return void
	 */
	public function down()
	{
        Schema::table('lessons', function(Blueprint $table){
                $table->dropColumn('seconds_used');
                $table->dropColumn('student_last_present_at');
                $table->dropColumn('tutor_last_present_at');
                $table->dropColumn('sync_status');
                $table->dropColumn('synced_start_at');
                $table->dropColumn('student_seconds_wait');
                $table->dropColumn('tutor_seconds_wait');
            });
	}

}
