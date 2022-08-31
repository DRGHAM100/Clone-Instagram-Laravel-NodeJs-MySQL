@extends('layouts.app')

@section('content')
<div class="container">
    <div class="row">
        @foreach($new_freinds as $new_freind)
        <div class="col-4 p-5">
            <div class="card" style="width: 18rem;">
                <img class="card-img-top" src="{{ $new_freind->profile->profileImage() }}" alt="Card image cap">
                <div class="card-body">
                    <h5 class="card-title">{{$new_freind->name}} | <span style="color:gray">{{$new_freind->username}}</span> </h5>
                    <a href="/profile/{{$new_freind->id}}" class="btn btn-primary btn-block">Profile</a>
                </div>
            </div>
        </div>
        @endforeach
    </div>
</div>
@endsection
