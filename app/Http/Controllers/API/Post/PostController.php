<?php

namespace App\Http\Controllers\API\Post;


use App\Http\Controllers\Controller;
use App\Http\Requests\StorePostRequest;
use App\Http\Requests\UpdatePostRequest;
use App\Http\Resources\PostResource;
use App\Models\Post;
use App\Trait\ModelNotFound;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Gate;


class PostController extends Controller
{
    use ModelNotFound;

    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        return PostResource::collection(Post::with(['author', 'category'])
            ->filter(request(['search', 'category', 'author', 'created_at']))
            ->paginate(10))->withQueryString();
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(StorePostRequest $request)
    {
        $data = $request->validated();
        $data['author_id'] = Auth::id();

        $post = Post::create($data);

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

        Gate::authorize('update', $post);

        $data = $request->validated();

        $post->update($data);

        return new PostResource($post);
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Post $post)
    {
        $this->modelNotFound($post);

        Gate::authorize('delete', $post);

        $post->delete();

        return response('', 200);
    }
}
