<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AddTransactionsForeignKey extends Migration {

	/**
	 * Run the migrations.
	 *
	 * @return void
	 */
	public function up()
	{
        Schema::table('transactions', function(Blueprint $table)
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
        Schema::table('transactions', function(Blueprint $table){
                $table->dropForeign('transactions_user_id_foreign');
            });
	}

}
