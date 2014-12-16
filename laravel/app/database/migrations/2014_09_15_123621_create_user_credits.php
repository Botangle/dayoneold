<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateUserCredits extends Migration {

	/**
	 * Run the migrations.
	 *
	 * @return void
	 */
	public function up()
	{
        Schema::table('user_credits', function(Blueprint $table)
            {
                $table->foreign('user_id')->references('id')->on('users')->onDelete('cascade');
            });
	}

	/**
	 * Reverse the migrations.
	 *
	 * @return void
	 */
	public function down()
	{
		Schema::table('user_credits', function(Blueprint $table){
                $table->dropForeign('user_credits_user_id_foreign');
            });
	}

}
