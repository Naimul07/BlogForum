<?php

namespace Database\Seeders;

use App\Models\Comment;
use App\Models\CommentReply;
use App\Models\Post;
use App\Models\PostReaction;
// use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use App\Models\User;
use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        User::factory()->count(10)->create();
        Post::factory()->count(10)->create();
        Comment::factory()->count(10)->create();
        CommentReply::factory()->count(10)->create();
        PostReaction::factory()->count(10)->create();

        User::factory()->create([
            'firstName' => 'Test',
            'lastName' => 'User',
            'email' => 'test@example.com',
        ]);
    }
}
