<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Intervention\Image\Facades\Image;
use App\Models\Post;

class PostController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth');
    }

    public function index()
    {
        $users = auth()->user()->following()->pluck('profiles.user_id');
        $posts = Post::whereIn('user_id',$users)->with('user')->latest()->paginate(4);
        return view('posts.index',compact('posts'));

    }

    public function create()
    {
         return view('posts.create');
    }

    public function store(Request $request)
    {
        $data = $request->validate([
            'caption' => 'required',
            'image' => 'required|image'
        ]);

        $image_path = request('image')->store('uploads','public');
        $data['image'] = $image_path;
        $image = Image::make(public_path("/storage/".$image_path))->fit(1200,1200);
        $image->save();
        auth()->user()->posts()->create($data);

        return redirect('/profile/'.auth()->user()->id);
    }

    public function show(Post $post)
    {
        return view('posts.show',compact('post'));
    }
}
