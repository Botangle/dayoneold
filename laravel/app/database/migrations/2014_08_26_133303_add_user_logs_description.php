<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AddUserLogsDescription extends Migration {

	/**
	 * Run the migrations.
	 *
	 * @return void
	 */
	public function up()
	{
        Schema::table('user_logs', function(Blueprint $table){
                $table->dropIndex('idx_user_log_user_id');
                $table->text('description')->after('type');
                $table->integer('related_type_id')->unsigned()->index()->default(0)->after('type');

                $table->index('type');
                $table->index('created');
                $table->foreign('user_id')->references('id')->on('users');
            });
	}

	/**
	 * Reverse the migrations.
	 *
	 * @return void
	 */
	public function down()
	{
        Schema::table('user_logs', function(Blueprint $table){
                $table->dropForeign('user_logs_user_id_foreign');
                $table->dropIndex('user_logs_type_index');
                $table->dropIndex('user_logs_created_index');
                $table->dropColumn('description');
                $table->dropIndex('user_logs_related_type_id_index');
                $table->dropColumn('related_type_id');

                $table->index('user_id', 'idx_user_log_user_id');
            });
	}

}
