<?php

namespace App\Http\Controllers\Api\V2;

use App\Http\Controllers\Controller;
use App\Http\Requests\StorePostRequest;
use App\Http\Resources\Api\V2\PostResource;
use App\Models\Post;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class PostController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
//        $posts = Post::with('author')->get(); // all posts
//        $posts = Auth::user()->posts()->with('author')->latest()->paginate(); // posts only from the auth user
        $posts = Auth::user()->posts()->paginate();

        return PostResource::collection($posts);
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(StorePostRequest $request)
    {
        $data = $request->validated();
        $data['author_id'] = $request->user()->id;

        $post = Post::query()->create($data);

        return new PostResource($post);
    }

    /**
     * Display the specified resource.
     */
    public function show(Post $post)
    {
//        $post = Post::query()->findOrFail($id);
        abort_if(Auth::id() !== $post->author_id, 403, 'Access denied.');

        return new PostResource($post);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, Post $post)
    {
        abort_if(Auth::id() !== $post->author_id, 403, 'Access denied.');

        $data = $request->validate([
            'title' => ['required', 'string', 'max:255'],
            'body'  => ['required', 'string'],
        ]);

        $post->update($data);

        return new PostResource($post);
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Post $post)
    {
        abort_if(Auth::id() !== $post->author_id, 403, 'Access denied.');

        $post->delete();

        return response()->noContent();
    }
}
