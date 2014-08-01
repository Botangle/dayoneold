<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class LessonsAddActiveFlag extends Migration {

	/**
	 * Run the migrations.
	 *
	 * @return void
	 */
	public function up()
	{
        Schema::table('lessons', function($table){
                $table->boolean('active')->default(1)->index()->after('notes');
            });

        // Reset active to 0 for all rows
        DB::update("UPDATE lessons SET `active`=0");

        // Only set active to 1 for the latest row for any given parent_id (the latest revision)
        DB::update("UPDATE lessons SET active = 1
WHERE id IN (SELECT * FROM( SELECT MAX(l2.id) FROM lessons l2 GROUP BY l2.parent_id) l3)");

	}

	/**
	 * Reverse the migrations.
	 *
	 * @return void
	 */
	public function down()
	{
        Schema::table('lessons', function($table){
                $table->dropColumn('active');
            });
	}

}
