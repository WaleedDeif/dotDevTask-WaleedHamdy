<?php

use Illuminate\Database\Seeder;

class PostsTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        //
        for ($i=0; $i <20 ; $i++) { 
        	$faker = Faker\Factory::create();
		    $post = new App\Models\Post;
		    $post->title = $faker->state;
		    $post->content = $faker->text($maxNbChars = 200);
		    $post->save();
        }

    }
}
