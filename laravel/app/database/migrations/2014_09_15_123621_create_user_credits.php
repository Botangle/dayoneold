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
        Schema::create('user_credits', function(Blueprint $table)
            {
                $table->engine = 'InnoDB';
                $table->increments('id');
                $table->integer('user_id');
                $table->decimal('amount', 11, 4);
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
		Schema::drop('user_credits');
	}

}
