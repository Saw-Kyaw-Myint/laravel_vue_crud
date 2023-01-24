<?php

namespace App\Http\Controllers;

use App\Models\Post;
use Illuminate\Http\Request;
use Illuminate\Contracts\Validation\Validator;
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
            'title'=>'required',
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

    public  function update(Request $request,$id)
    {
        info($request);

        $request->validate([
            'title'=>'required',
            'file' => 'required|mimes:jpg,jpeg,png,csv,txt,xlx,xls,pdf|max:2048'
        ]);
        
       $post = Post::find($id);
        $post->title = $request->title;
        if ($request->file()) {
            $file_name = time() . '_' . $request->file->getClientOriginalName();
            $file_path = $request->file('file')->storeAs('uploads', $file_name, 'public');
            $post->image_path = '/storage/' . $file_path;
            $post->update();

            return response()->json([
                'message' => 'New post updated'
            ]);
        }
    }

    public function deletePost($id)
    {
        $post = Post::find($id);
        $post->delete();
        
        return response()->json(['message' => 'post id deleted']);
    }
}
