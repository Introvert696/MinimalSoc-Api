<?php

namespace Database\Seeders;

// use Illuminate\Database\Console\Seeds\WithoutModelEvents;

use App\Models\Document;
use App\Models\Friend;
use App\Models\Group;
use App\Models\Group_post;
use App\Models\Message;
use App\Models\Messages_group;
use Illuminate\Database\Seeder;
use App\Models\User;
use App\Models\Post;
use App\Models\Subscribe_to_group;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     *
     * @return void
     */
    public function run()
    {

        $users = User::factory(10)->create();
        $posts = Post::factory(50)->create();
        $documents = Document::factory(20)->create();
        $friends = Friend::factory(10)->create();
        $groups = Group::factory(10)->create();
        $group_posts = Group_post::factory(30)->create();
        $message_groups = Messages_group::factory(20)->create();
        $message = Message::factory(40)->create();
        $subscribe_to_groups = Subscribe_to_group::factory(20)->create();
    }
}
