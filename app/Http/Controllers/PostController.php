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
    public function create(Request $request)
    {
        $request->validate([
            'title'=>'required',
            'file' => 'required|mimes:jpg,jpeg,png,csv,txt,xlx,xls,pdf|max:2048'
        ]);
        
        $posts= new Post();
        if ($request->file()) {
            $file_name = time() . '_' . $request->file->getClientOriginalName();
            $file_path = $request->file('file')->storeAs('uploads', $file_name, 'public');
            $image_path = '/storage/' . $file_path;
            $data=[
                'title'=>$request->title,
                'image_path'=>$image_path
            ];       
            $posts->create($data);

            return response()->json([
                'message' => 'New post created'
            ]);
        }
    }

    public  function update(Request $request,$id)
    {
        $request->validate([
            'title'=>'required',
            'file' => 'required|mimes:jpg,jpeg,png,csv,txt,xlx,xls,pdf|max:2048'
        ]);
        
       $post = Post::find($id);
      $post->title = $request->title;  if ($request->file()) {
            $file_name = time() . '_' . $request->file->getClientOriginalName();
            $file_path = $request->file('file')->storeAs('uploads', $file_name, 'public');
            $image_path = '/storage/' . $file_path;
           $data=[
         'title'=>$request->title,
         'image_path'=>$image_path
           ]; 
           $post->update($data);

            return response()->json([
                'message' => 'New post updated'
            ]);
        }
    }

    public function destroy($id)
    {
        info($id);
        $post = Post::find($id);
        $post->delete();

        return response()->json(['message' => 'post id deleted']);
    }
}
