<?php

namespace App\Http\Middleware;

use Closure;
use App\Models\Post;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class EnsurePostOwner
{
    /**
     * Handle an incoming request.
     *
     * @param  \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response)  $next
     */
    public function handle(Request $request, Closure $next): Response
    {   
        $postId = $request->route('post'); //
        $post = $postId instanceof Post ? $postId : Post::find($postId); //check if it is an instance or just an ID

        if (!$post || $post->user_id !== $request->user()->id) {
            return response()->json(['message' => 'Forbidden'], 403);
        }
        
        return $next($request);
    }
}
