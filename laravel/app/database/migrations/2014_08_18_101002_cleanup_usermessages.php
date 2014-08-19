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
        $user = User::where('email', 'unknown@botangle.com')->first();
        if (!$user){
            $user = User::create(array(
                    'email'     => 'unknown@botangle.com',
                    'username'  => 'unknown',
                    'name'      => 'Unknown',
                    'lname'     => 'User',
                ));
        }

        DB::update("ALTER TABLE usermessages CHANGE COLUMN readmessage readmessage tinyint(1) DEFAULT 0;");
        DB::update("UPDATE usermessages SET sent_from=". $user->id ." WHERE sent_from=0;");
        DB::update("UPDATE usermessages SET send_to=". $user->id ." WHERE send_to=0;");

        Schema::table('usermessages', function(Blueprint $table){
                $table->foreign('send_to')->references('id')->on('users')->onDelete('cascade');
                $table->foreign('sent_from')->references('id')->on('users')->onDelete('cascade');
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
