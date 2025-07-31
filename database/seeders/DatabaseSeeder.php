<?php

namespace Database\Seeders;

use App\Models\Comment;
use App\Models\Post;
use App\Models\Reply;
use App\Models\Tag;
use App\Models\User;
// use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        $users = User::factory(30)->create();
        $tags = Tag::factory(30)->create();
        $posts = Post::factory(30)->recycle($users)->recycle($tags)->create();
        $comments = Comment::factory(30)->recycle($users)->recycle($posts)->create();
        Reply::factory(30)->recycle($users)->recycle($comments)->create();
        

        // User::factory()->create([
        //     'name' => 'Test User',
        //     'email' => 'test@example.com',
        // ]);
    }
}
