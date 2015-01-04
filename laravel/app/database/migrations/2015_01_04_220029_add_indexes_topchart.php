<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AddIndexesTopchart extends Migration {

	/**
	 * Run the migrations.
	 *
	 * @return void
	 */
	public function up()
	{
        Schema::table('users', function(Blueprint $table){
                $table->index('status');
                $table->index('role_id');
            });

        Schema::table('reviews', function(Blueprint $table){
                $table->index('rate_to');
            });

        Schema::table('categories', function(Blueprint $table){
                $table->index('status');
                $table->index('parent_id');
            });
	}

	/**
	 * Reverse the migrations.
	 *
	 * @return void
	 */
	public function down()
	{
        Schema::table('users', function(Blueprint $table){
                $table->dropIndex('users_status_index');
                $table->dropIndex('users_role_id_index');
            });

        Schema::table('reviews', function(Blueprint $table){
                $table->dropIndex('reviews_rate_to_index');
            });

        Schema::table('categories', function(Blueprint $table){
                $table->dropIndex('categories_status_index');
                $table->dropIndex('categories_parent_id_index');
            });
	}

}
