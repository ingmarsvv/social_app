<?php

namespace Database\Seeders;

use App\Models\Post;
use App\Models\User;
use App\Models\Category;
use Illuminate\Database\Seeder;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;

class PostSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {

        $categories = Category::all();

        Post::factory(25)->create()->each(function ($post) use ($categories) {
            $post->save();
            $post->categories()->attach($categories->random(rand(1, 3))->pluck('id')->toArray());
        });
    }
}
