<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Intervention\Image\Facades\Image;
use Illuminate\Support\Facades\Cache;
use App\Models\User;

class ProfilesController extends Controller
{
    public function index(User $user)
    {
        $follows = (auth()->user()) ? auth()->user()->following->contains($user->id) : false;
       
        $postCount = Cache::remember('count.posts.'.$user->id,now()->addSeconds(60),function() use($user){
            return $user->posts->count();
        }); 

        $followersCount = Cache::remember('count.followers.'.$user->id,now()->addSeconds(60),function() use($user){
            return $user->profile->followers->count();
        }); 

        $followingCount = Cache::remember('count.following.'.$user->id,now()->addSeconds(60),function() use($user){
            return $user->following->count();
        }); 

        return view('profiles.index',compact('user','follows','postCount','followersCount','followingCount'));
    }

    public function edit(User $user)
    {
        $this->authorize('update',$user->profile);
        return view('profiles.edit',compact('user'));
    }

    public function update(Request $request,User $user)
    {
        $this->authorize('update',$user->profile);
        
        $data = $request->validate([
            'title' => 'required',
            'description' => 'required',
            'url' => 'url',
            'image' => 'image'
        ]);

        if($request->image){
            $image_path = request('image')->store('profile','public');
            $data['image'] = $image_path;
            $image = Image::make(public_path("/storage/".$image_path))->fit(1000,1000);
            $image->save();
        }

        auth()->user()->profile->update($data);

        return redirect('/profile/'.auth()->user()->id);
    }


}
