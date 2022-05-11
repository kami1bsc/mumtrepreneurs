<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Post;
class PostSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        Post::create([
            'user_id' => 2,
            'post_description' => 'Duis aute irure dolor in reprehenderit in voluptate velit esse cillum dolore eu fugiat nulla pariatur.',
            'post_time' => time(),
            'post_media' => 'post_media/default.jpg',
            'media_type' => 'image',
        ], 200);

        Post::create([
            'user_id' => 3,
            'post_description' => 'To keep the body in good health is a duty… otherwise we shall not be able to keep our mind strong and clear.',
            'post_time' => time(),
            'post_media' => 'post_media/gym.mp4',
            'media_type' => 'video',
        ], 200);

        Post::create([
            'user_id' => 4,
            'post_description' => 'New York may be the city that never sleeps, but it sure does sleeps around',
            'post_time' => time(),
            'post_media' => 'post_media/default1.jpg',
            'media_type' => 'image',
        ], 200);

        Post::create([
            'user_id' => 2,
            'post_description' => 'qui dolorem ipsum quia dolor sit amet, consectetur, adipisci velit, sed quia non numquam eius modi tempora incidunt ut labore',
            'post_time' => time(),
            'post_media' => 'post_media/default2.jpg',
            'media_type' => 'image',
        ], 200);

        Post::create([
            'user_id' => 3,
            'post_description' => 'Nemo enim ipsam voluptatem quia voluptas sit aspernatur aut odit aut fugit, sed quia consequuntur magni dolores eos qui ratione voluptatem sequi nesciunt.',
            'post_time' => time(),
            'post_media' => 'post_media/default3.jpg',
            'media_type' => 'image',
        ], 200);

        Post::create([
            'user_id' => 4,
            'post_description' => 'Ut enim ad minima veniam, quis nostrum exercitationem ullam corporis suscipit laboriosam, nisi ut aliquid ex ea commodi consequatur',
            'post_time' => time(),
            'post_media' => 'post_media/default4.jpg',
            'media_type' => 'image',
        ], 200);

        Post::create([
            'user_id' => 2,
            'post_description' => 'Quis autem vel eum iure reprehenderit qui in ea voluptate velit esse quam nihil molestiae consequatur.',
            'post_time' => time(),
            'post_media' => 'post_media/default5.jpg',
            'media_type' => 'image',
        ], 200);
    }
}
