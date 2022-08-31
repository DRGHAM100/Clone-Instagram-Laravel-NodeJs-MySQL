@extends('layouts.app')

@section('content')
<div class="container">
    <form action="{{ route('post.store') }}" method="post" enctype="multipart/form-data">
        @csrf 
        <div class="row">
            <div class="col-8 offset-2">
                <div class="row">
                    <h1>Add New Post</h1>
                </div>
                <div class="row mb-3">
                    <label for="name" class="col-md-4 col-form-label">{{ __('Post Caption') }}</label>

                    <div class="col-md-12">
                        <input id="caption" type="text" class="form-control @error('caption') is-invalid @enderror" name="caption" value="{{ old('caption') }}" required autocomplete="caption" autofocus>

                        @error('caption')
                            <span class="invalid-feedback" role="alert">
                                <strong>{{ $message }}</strong>
                            </span>
                        @enderror
                    </div>
                </div>
                <div class="row mb-3">
                    <label for="image" class="col-md-4 col-form-label">{{ __('Post Image') }}</label>
                    <div class="col-md-12">
                        <input type="file" name="image" id="image" class="form-control @error('image') is-invalid @enderror" required>
                        @error('image')
                            <span class="invalid-feedback" role="alert">
                                <strong>{{ $message }}</strong>
                            </span>
                        @enderror
                    </div>
                </div>
                <div class="row mb-0">
                    <div class="col-md-12">
                        <button type="submit" class="btn btn-primary btn-block">
                            {{ __('Add') }}
                        </button>
                    </div>
                </div>
            </div>
      </div>
    </form>
</div>
@endsection
