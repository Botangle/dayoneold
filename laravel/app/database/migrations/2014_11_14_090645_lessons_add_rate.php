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

        /**
         * Some lessons reference tutors and students who have been deleted, so we need to set those to the
         * unknown user so that we can add the foreign key
         */
        // Adding an unknown user so that user_logs that have referential integrity problems can be
        //   attached to this unknown user.
        $user = User::where('email', 'deleted@botangle.com')->first();
        if (!$user){
            $user = User::create(array(
                    'email'     => 'deleted@botangle.com',
                    'username'  => 'deleteduser',
                    'name'      => 'Deleted',
                    'lname'     => 'User',
                ));
        }
        $userId = $user->id;

        /**
         * Some lessons reference users who have been deleted, so we need to set those to the
         * unknown user so that we can add the foreign key
         */
        DB::update("UPDATE lessons l
            SET l.student = $userId
            WHERE
                l.id IN (
                    SELECT * FROM( SELECT l2.id
                        FROM lessons l2
                        LEFT JOIN users u ON l2.student = u.id
                        WHERE u.id is null ) tmp
                )
        ");
        DB::update("UPDATE lessons l
            SET l.tutor = $userId
            WHERE
                l.id IN (
                    SELECT * FROM( SELECT l2.id
                        FROM lessons l2
                        LEFT JOIN users u ON l2.tutor = u.id
                        WHERE u.id is null ) tmp
                )
        ");

        Schema::table('lessons', function(Blueprint $table){
                $table->foreign('student')->references('id')->on('users');
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
