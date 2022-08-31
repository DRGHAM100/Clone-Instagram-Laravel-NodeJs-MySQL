<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\User;

class FindFreindsController extends Controller
{
    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct()
    {
        $this->middleware('auth');
    }

    public function index()
    {
        $following = auth()->user()->following()->pluck('profiles.user_id');
        $new_freinds = User::whereNotIn('id',$following)->where('id','!=',auth()->user()->id)->inRandomOrder()->limit(25)->get();
        return view('find_freinds.index',compact('new_freinds'));
    }
}
