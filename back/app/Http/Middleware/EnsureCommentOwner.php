<?php

namespace App\Http\Middleware;

use Closure;
use App\Models\Comment;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class EnsureCommentOwner
{
    /**
     * Handle an incoming request.
     *
     * @param  \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response)  $next
     */
    public function handle(Request $request, Closure $next): Response
    {   
        $commentId = $request->route('comment'); //
        $comment = $commentId instanceof Comment ? $commentId : Comment::find($commentId); //check if it is an instance or just an ID

        if (!$comment || $comment->user_id !== $request->user()->id) {
            return response()->json(['message' => 'Forbidden'], 403);
        }
        
        return $next($request);
    }
}
