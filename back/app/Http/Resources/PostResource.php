<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class PostResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @return array<string, mixed>
     */
    public function toArray(Request $request): array
{
    return [
        'id' => $this->id,
        'title' => $this->title,
        'content' => $this->content,
        'author' => $this->user->name,
        'categories' => $this->categories->map(function ($category) {
            return [
                'id' => $category->id,
                'name' => $category->name,
            ];
        }),
        'comments_count' => $this->whenCounted('comments'), 
        'comments' => $this->whenLoaded('comments', function () {
            return $this->comments->map(function ($comment) {
                return [
                    'id' => $comment->id,
                    'user_id' => $comment->user_id,
                    'content' => $comment->content,
                    'author' => $comment->user->name,
                    'created_at' => $comment->created_at->format('Y-m-d H:i'),
                ];
            });
        }),
        'created_at' => $this->created_at->format('Y-m-d H:i'),
    ];
}
}
