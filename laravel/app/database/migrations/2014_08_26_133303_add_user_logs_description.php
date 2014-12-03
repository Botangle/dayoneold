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
            });

        /** Need to fix the data for adding the foreign key */
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
         * Some user_logs reference users who have been deleted, so we need to set those to the
         * unknown user so that we can add the foreign key
         */
        DB::update("UPDATE user_logs ul
            SET ul.user_id = $userId
            WHERE
                ul.id IN (
                    SELECT * FROM( SELECT ul2.id
                        FROM user_logs ul2
                        LEFT JOIN users u ON ul2.user_id = u.id
                        WHERE u.id is null ) tmp
                )
        ");

        Schema::table('user_logs', function(Blueprint $table){
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
