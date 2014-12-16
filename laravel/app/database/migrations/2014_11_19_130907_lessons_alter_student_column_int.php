<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class LessonsAlterStudentColumnInt extends Migration {

	/**
	 * Run the migrations.
	 *
	 * @return void
	 */
	public function up()
	{
        // In spite of the name of this class, this effectively alters tutor from varchar to int(10)
        Schema::table('lessons', function(Blueprint $table){
                $table->renameColumn('tutor', 'tutor_old');
        });
        Schema::table('lessons', function(Blueprint $table){
                $table->integer('tutor')->after('created');
            });

        DB::update("UPDATE lessons SET tutor=tutor_old");

        Schema::table('lessons', function(Blueprint $table){
                $table->foreign('tutor')->references('id')->on('users');
                $table->dropColumn('tutor_old');
            });
	}

	/**
	 * Reverse the migrations.
	 *
	 * @return void
	 */
	public function down()
	{
		// Only really reverting the migration, so that it can be run again
        Schema::table('lessons', function(Blueprint $table){
                $table->dropIndex('lessons_tutor_index');
            });
	}

}
