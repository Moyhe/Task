<?php

namespace App\Http\Controllers\API\Post;


use App\Http\Controllers\Controller;
use App\Http\Resources\CommentResource;
use App\Models\Post;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class CommentController extends Controller
{

    /**
     * Store a newly created resource in storage.
     */
    public function store(string $id)
    {
        $post = Post::query()->findOrFail($id);

        request()->validate([
            'comment' => ['required']
        ]);

        $comment = $post->comments()->create([
            'author_id' => Auth::id(),
            'comment' => request('comment')
        ]);

        return new CommentResource($comment->load(['post']));
    }
}
