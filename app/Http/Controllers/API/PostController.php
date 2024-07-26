<?php

namespace App\Http\Controllers\API;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Validator;
use App\Http\Controllers\API\BaseController as BaseController;
use App\Models\Post;

class PostController extends BaseController 
{
    public function index() {
        $data['posts'] = Post::all();
        return $this->sendResponse($data, 'All posts data');
    }
    public function store(Request $request) {
        $validatePost = Validator::make(
            $request->all(),
            [
                'title' => 'required',
                'description' => 'required',
                'image' => 'required|mimes:png,jpg,jpeg,gif',
            ]
        );
        if ($validatePost->fails()) {
            return $this->sendError('Validation Error', $validatePost->errors()->all());
        }  
        $img = $request->image;
        $ext = $img->getClientOriginalExtension();
        $imageName = time().'.'.$ext;
        $img->move(public_path().'/uploads', $imageName);
        $post = Post::create([
            'title' => $request->title,
            'description' => $request->description,
            'image' => $imageName
        ]);        
        return $this->sendResponse($post, 'Post created successfully');
    }
    public function show(string $id) {
        $data['post'] = Post::select(
            'id',
            'title',
            'description',
            'image'
        )->where('id', $id)->get();
        return $this->sendResponse($data, 'Your single post');
    }
    public function update(Request $request, string $id) {
        $validatePost = Validator::make(
            $request->all(),
            [
                'title' => 'required',
                'description' => 'required',
                'image' => 'nullable|image|mimes:png,jpg,jpeg,gif',
            ]
        );
        if ($validatePost->fails()) {
            return $this->sendError('Validation Error', $validatePost->errors()->all());
        }  
        $postImage = Post::select('id', 'image')->where(['id' => $id])->get();
        if(!empty($request->image)) {
            $path = public_path().'/uploads/';
            if($postImage[0]->image != '' && $postImage[0]->image != null) {
                $old_file = $path.$postImage[0]->image;
                if(file_exists($old_file)) {
                    unlink($old_file);
                }
            }
            $img = $request->image;
            $ext = $img->getClientOriginalExtension();
            $imageName = time().'.'.$ext;
            $img->move(public_path().'/uploads', $imageName);
        } else {
            $imageName = $postImage[0]->image;
        }
       
        $post = Post::where(['id' => $id])->update([
            'title' => $request->title,
            'description' => $request->description,
            'image' => $imageName
        ]);
        return $this->sendResponse($post, 'Post updated successfully');
    }
    public function destroy(string $id) {
        $imagePath = Post::select('image')->where('id', $id)->get();
        $filePath = public_path().'/uploads/'.$imagePath[0]->image;
        unlink($filePath);
        $post = Post::where('id', $id)->delete();
        return $this->sendResponse($post, 'Your post have been removed');
    }
}
