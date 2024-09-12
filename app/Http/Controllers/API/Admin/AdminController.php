<?php

namespace App\Http\Controllers\API\Admin;

use App\Http\Controllers\Controller;
use App\Http\Requests\StorePostRequest;
use App\Http\Requests\UpdatePostRequest;
use App\Http\Resources\PostResource;
use App\Models\Post;
use App\Trait\ModelNotFound;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Cache;

class AdminController extends Controller
{
    use ModelNotFound;

    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        return Cache::remember('posts', now()->addMinutes(10), function () {
            return PostResource::collection(Post::with(['author', 'category'])
                ->filter(request(['title', 'category', 'author', 'created_at']))
                ->paginate(10))->withQueryString();
        });
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(StorePostRequest $request)
    {
        $data = $request->validated();
        $data['author_id'] = Auth::id();

        $post = Post::create($data);

        Cache::forget('posts');

        return new PostResource($post);
    }

    /**
     * Display the specified resource.
     */
    public function show(Post $post)
    {
        $this->modelNotFound($post);

        return new PostResource($post->load('author'));
    }


    /**
     * Update the specified resource in storage.
     */
    public function update(UpdatePostRequest $request, Post $post)
    {
        $this->modelNotFound($post);

        $data = $request->validated();

        $post->update($data);

        Cache::forget('posts');

        return new PostResource($post);
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Post $post)
    {
        $this->modelNotFound($post);

        $post->delete();

        Cache::forget('posts');

        return response('', 200);
    }
}
