<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\User;
use App\Models\Chat;
use DB;

class ChatController extends Controller
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

    public function index(Request $request,$id=null)
    {
        $messages = [];
        $otherUser = null;
        if($id){
            $otherUser = User::findOrFail($id);
            $group_id = (auth()->user()->id > $id) ? auth()->user()->id.$id : $id.auth()->user()->id;
            $messages = Chat::where('group_id',$group_id)->get(); 
            Chat::where('other_user_id',auth()->user()->id)
            ->where('user_id',$id)
            ->where('is_read',0)
            ->update([ 'is_read' => 1 ]);
        } 

        $freinds = auth()->user()->following()->get();
        
        return view('chats.index',compact('freinds','messages','otherUser'));

    }
}
