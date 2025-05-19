<?php

namespace App\Http\Controllers;

use App\Models\Post;
use Illuminate\Http\Request;
use App\Http\Resources\PostResource;
use Illuminate\Support\Facades\Validator;

class PostController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $posts = Post::all();

        return response()->json(
            [
                'message' => 'Posts retrieved successfully',
                'data' => PostResource::collection($posts),
                'status' => true,
            ],
            200
        );
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        //
        $validator = Validator::make($request->all(), [
            'title' => 'required|string|max:255',
            'body' => 'required|string|max:255',
        ]);
        if ($validator->fails()) {
            return response()->json($validator->errors(), 422);
        }

        $post = Post::create($request->all());

        return response()->json(
            [
                'message' => 'Post created successfully',
                'data' => new PostResource($post),
                'status' => true,
            ],
            201
        );
    }

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        $post = Post::find($id);
        if (!$post) {
            return response()->json(
                [
                    'message' => 'Post not found',
                    'status' => false,
                ],
                404
            );
        }

        return response()->json(
            [
                'message' => 'Post retrieved successfully',
                'data' => new PostResource($post),
                'status' => true,
            ],
            200
        );
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
    {
        $post = Post::find($id);
        if (!$post) {
            return response()->json(
                [
                    'message' => 'Post not found',
                    'status' => false,
                ],
                404
            );
        }

        $validator = Validator::make($request->all(), [
            'title' => 'required|string|max:255',
            'body' => 'required|string|max:255',
        ]);
        if ($validator->fails()) {
            return response()->json($validator->errors(), 422);
        }
        $post = Post::find($id);
        $post->update($request->all());

        return response()->json(
            [
                'message' => 'Post updated successfully',
                'data' => new PostResource($post),
                'status' => true,
            ],
            200
        );
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        $post = Post::find($id);
        if (!$post) {
            return response()->json(
                [
                    'message' => 'Post not found',
                    'status' => false,
                ],
                404
            );
        }

        $post->delete();

        return response()->json(
            [
                'message' => 'Post deleted successfully',
                'status' => true,
            ],
            200
        );
    }
}
