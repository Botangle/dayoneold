<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CleanupUsermessages extends Migration {

	/**
	 * Run the migrations.
	 *
	 * @return void
	 */
	public function up()
	{
        // Adding an unknown user so that usermessages that have referential integrity problems can be
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

        DB::update("ALTER TABLE usermessages CHANGE COLUMN readmessage readmessage tinyint(1) DEFAULT 0;");

        /**
         * Some usermessages reference users who have been deleted (or just don't reference a user), so we need
         * to set those to the deleted user so that we can add the foreign key
         */
        DB::update("UPDATE usermessages um
            SET um.send_to = $userId
            WHERE
                um.id IN (
                    SELECT * FROM( SELECT um2.id
                        FROM usermessages um2
                        LEFT JOIN users u ON um2.send_to = u.id
                        WHERE u.id is null ) tmp
                )
        ");
        DB::update("UPDATE usermessages um
            SET um.sent_from = $userId
            WHERE
                um.id IN (
                    SELECT * FROM( SELECT um2.id
                        FROM usermessages um2
                        LEFT JOIN users u ON um2.sent_from = u.id
                        WHERE u.id is null ) tmp
                )
        ");

        Schema::table('usermessages', function(Blueprint $table){
                $table->foreign('sent_from')->references('id')->on('users')->onDelete('cascade');
                $table->foreign('send_to')->references('id')->on('users')->onDelete('cascade');
                $table->index('date');
            });
	}

	/**
	 * Reverse the migrations.
	 *
	 * @return void
	 */
	public function down()
	{
        DB::update("ALTER TABLE usermessages CHANGE COLUMN readmessage readmessage int(11) DEFAULT 0;");

        Schema::table('usermessages', function(Blueprint $table){
                $table->dropForeign('usermessages_send_to_foreign');
                $table->dropForeign('usermessages_sent_from_foreign');
            });
	}

}
