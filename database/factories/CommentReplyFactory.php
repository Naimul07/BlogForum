<?php

namespace Database\Factories;

use App\Models\Post;
use App\Models\User;
use App\Models\Comment;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\CommentReply>
 */
class CommentReplyFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            //
            'comment_id' => Comment::factory(), 
            'user_id' => User::factory(), // Creates a user for the comment
            'post_id' => Post::factory(), // Creates a comment for the reply
            'reply' => $this->faker->paragraph,
        ];
    }
}
