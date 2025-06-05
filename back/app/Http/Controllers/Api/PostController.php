<?php

namespace App\Http\Controllers\Api;

use App\Models\Post;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Http\Resources\PostResource;
use Illuminate\Support\Facades\Auth;
use Purifier;

class PostController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {   
        $request->validate([
            'search' => 'string || nullable'
        ]);
        
        $searchPhrase = $request->query('search', '');

        $query = Post::with(['categories', 'user'])
            ->withCount('comments');

        //if input 'search' then add scope query
        if ($request->input('search')) {
            $query->search($searchPhrase);
        }

        return PostResource::collection($query->paginate(6));
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $validated = $request->validate([
            'title' => 'required|string|max:255',
            'content' => 'required|string',
            'category_ids' => 'required|array',
            'category_ids.*' => 'exists:categories,id', //check if categories id is present in db
        ]);
    
        $post = Auth::user()->posts()->create([
            'title' => $validated['title'],
            'content' => $validated['content'],
        ]);
    
        $post->categories()->attach($validated['category_ids']);

        return response()->json(['message' => 'Post created successfully'], 201);
    }

    /**
     * Display the specified resource.
     */
    public function show(Post $post)
    {
        $post->load(['user', 'categories', 'comments.user']);
        $post->loadCount('comments');

        return new PostResource($post);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Post $post, Request $request)
    {
        $validated = $request->validate([
            'title' => 'required|string|max:255',
            'content' => 'required|string',
            'categories' => 'required|array',
            'categories.*' => 'exists:categories,id', //check if categories id is present in db
        ]);
    
        $post->title = $validated["title"];
        $post->content = $validated["content"];
        $post->save();
        $post->categories()->sync($validated['categories']); //sync with intermediate table

        return response()->json(['message' => 'Post updated successfully'], 200);
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Post $post)
    {
        $post->delete(); // cascade delete on category happens automatically

        return response()->json(['message' => 'Post deleted successfully']);
    }

    public function myPosts() {

        $user = auth()->user();

        return PostResource::collection($user->posts()->with(['categories', 'user'])
            ->withCount('comments')->latest()->paginate(6));
    }
}
