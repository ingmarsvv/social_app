<?php

namespace Database\Seeders;

use App\Models\Post;
use App\Models\User;
use App\Models\Comment;
use Illuminate\Database\Seeder;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;

class CommentSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        Post::all()->each(function ($post) {
            for ($i = 0; $i < rand(2, 6); $i ++) {
                Comment::create([
                    'post_id' => $post->id,
                    'user_id' => User::inRandomOrder()->first()->id,
                    'content' => fake()->paragraph(3)
                ]);
            }
        });
    }
}
