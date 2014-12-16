<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AddStatusTimestamps extends Migration {

	/**
	 * Run the migrations.
	 *
	 * @return void
	 */
	public function up()
	{
		Schema::table('my_statuses', function($table){
                $table->timestamps();
            });

        DB::update("UPDATE my_statuses SET `created_at`=`created`");

        Schema::table('my_statuses', function($table){
                $table->dropColumn('created');
            });

	}

	/**
	 * Reverse the migrations.
	 *
	 * @return void
	 */
	public function down()
	{
        Schema::table('my_statuses', function($table){
                $table->dateTime('created');
            });

        DB::update("UPDATE my_statuses SET `created`=`created_at`");

        Schema::table('my_statuses', function($table){
                $table->dropTimestamps();
            });
	}

}
