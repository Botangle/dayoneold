<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AddUsersAverageRating extends Migration {

	/**
	 * Run the migrations.
	 *
	 * @return void
	 */
	public function up()
	{
        Schema::table('users', function(Blueprint $table){
                $table->float('average_rating')->default(0)->index();
                $table->integer('review_count')->default(0);
            });

        DB::update("UPDATE  users u
                            INNER JOIN
                            (
                                SELECT  rate_to, AVG(rating) avg_rating, COUNT(id) rating_count
                                FROM    reviews
                                GROUP BY rate_to
                            ) r ON u.id = r.rate_to
                    SET     u.average_rating = r.avg_rating, u.review_count = r.rating_count
        ");
    }

	/**
	 * Reverse the migrations.
	 *
	 * @return void
	 */
	public function down()
	{
        Schema::table('users', function(Blueprint $table){
                $table->dropColumn('average_rating');
                $table->dropColumn('review_count');
            });
	}

}
