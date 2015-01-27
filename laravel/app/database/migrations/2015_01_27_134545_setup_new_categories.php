<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class SetupNewCategories extends Migration {

	/**
	 * Run the migrations.
	 *
	 * @return void
	 */
	public function up()
	{
        // Make a backup of the categories table
        DB::update("CREATE TABLE old_categories LIKE categories;");
        DB::update("INSERT old_categories SELECT * FROM categories;");

        // Make a backup of the users table
        Schema::dropIfExists('old_users');
        DB::update("CREATE TABLE old_users LIKE users;");
        DB::update("INSERT old_users SELECT * FROM users;");

        // Empty the categories table
        DB::update("DELETE FROM categories;");

        // Populate it with the new categories data
        $newCats = [
            'Programming',
            'Node.JS',
            'Ruby On Rails',
            'HTML/CSS',
            'Swift',
            'Java',
            'Python',
            'Marketing',
            'Digital Marketing',
            'Growth Hacking',
            'Product Marketing',
            'SEO',
            'Social Media Marketing',
            'Advertising',
            'Non-Digital Marketing',
            'Design',
            'Mobile Design',
            'Graphic Design',
            'UI',
            'Motion Design',
            'Web Design',
            'iOS Design',
            'Android Design',
        ];
        foreach ($newCats as $cat){
            Category::create([
                'name'      => $cat,
                'status'    => 1,
            ]);
        }

        // Cleanup the users subject data to only include new categories
        $users = User::all();
        foreach($users as $user){
            $selectedCats = explode(", ", $user->subject);
            $validCats = [];
            foreach($selectedCats as $cat){
                if(in_array($cat, $newCats)){
                    $validCats[] = $cat;
                }
            }
            $user->subject = implode(", ", $validCats);
            $user->save();
        }

        // Flush the entire cache to clear all the categories cache
        Cache::flush();
	}

	/**
	 * Reverse the migrations.
	 *
	 * @return void
	 */
	public function down()
	{
        DB::update("DELETE FROM categories;");

        // Move the old categories back to the categories table and drop the backup
        DB::update("INSERT categories SELECT * FROM old_categories;");
        Schema::drop('old_categories');

        // Copy the old user subject back
        DB::update("UPDATE users u INNER JOIN old_users ou ON u.id = ou.id SET u.subject = ou.subject;");

        // Flush the entire cache to clear all the categories cache
        Cache::flush();
	}

}
