<?php

namespace App\Http\Controllers;

use App\Models\Post;
use Illuminate\Http\Request;

class PostController extends Controller
{
    public function index()
    {
        $posts = Post::all();
        return response()->json($posts);
    }
    public function createPost(Request $request)
    {
        $request->validate([
            'file' => 'required|mimes:jpg,jpeg,png,csv,txt,xlx,xls,pdf|max:2048'
        ]);
        $post = new Post();
        $post->title = $request->title;

        if ($request->file()) {
            $file_name = time() . '_' . $request->file->getClientOriginalName();
            $file_path = $request->file('file')->storeAs('uploads', $file_name, 'public');
            $post->image_path = '/storage/' . $file_path;
            $post->save();
            return response()->json([
                'message' => 'New post created'
            ]);
        }
    }

    public  function update(Request $request, $id)
    {
        $post = Post::find($id);
        if ($post) {
            $post->update([
                'title' => $request->title,
            ]);

            return response()->json([
                'success' => true,
                'message' => 'Post updated',
                'data' => $post
            ], 200);
        }
    }

    public function deletePost($id)
    {
        $post = Post::find($id);
        $post->delete();
        return response()->json(['message' => 'post id deleted']);
    }
}
