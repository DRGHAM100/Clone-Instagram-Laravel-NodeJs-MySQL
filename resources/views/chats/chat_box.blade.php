@if($otherUser != null)
    <div class="py-2 px-4 border-bottom d-none d-lg-block">
        <div class="d-flex align-items-center py-1">
            <div class="position-relative"><img src="{{ $otherUser->profile->profileImage() }}" class="rounded-circle mr-1" alt="Sharon Lessman" width="40" height="40" /></div>
            <div class="flex-grow-1 pl-3">
                <strong><a href="/profile/{{ $otherUser->id }}" style="text-decoration:none;color:#000">{{ $otherUser->name }}</a></strong>
                <div class="text-muted small" id="typing-{{$otherUser->id}}"></div>
            </div> 
        </div>
    </div>
    <div class="position-relative">
        <div class="chat-messages p-4">
            @foreach($messages as $message)
                @if($message->user_id == Auth::user()->id)
                    <div class="chat-message-right pb-4">
                        <div>
                            <img src="{{ auth()->user()->profile->profileImage() }}" class="rounded-circle mr-1" alt="Chris Wood" width="40" height="40" />
                            <div class="text-muted small text-nowrap mt-2">
                                {{ date("h:i A",strtotime($message->created_at)) }}
                            </div>
                        </div>
                        <div class="flex-shrink-1 bg-light rounded py-2 px-3 mr-3">
                            <div class="font-weight-bold mb-1">You</div>
                            {{ $message->message }}
                        </div>
                    </div>
                @else
                    <div class="chat-message-left pb-4">
                        <div>
                            <img src="{{ $otherUser->profile->profileImage() }}" class="rounded-circle mr-1" alt="Sharon Lessman" width="40" height="40" />
                            <div class="text-muted small text-nowrap mt-2">
                                {{ date("h:i A",strtotime($message->created_at)) }}
                            </div>
                        </div>
                        <div class="flex-shrink-1 bg-light rounded py-2 px-3 ml-3">
                            <div class="font-weight-bold mb-1">{{ $otherUser->username }}</div>
                            {{ $message->message }}
                        </div>
                    </div>
                @endif
            @endforeach
        </div>
    </div>
    <div class="flex-grow-0 py-3 px-4 border-top">
        <form id="chat-form">
            <div class="input-group">
                <input type="text" id="message-input" class="form-control" placeholder="Type your message" /> 
                <button type="submit" class="btn btn-primary">Send</button>
            </div>
        </form>
    </div>
@else
<div style="text-align:center"><img src="{{ asset('img/logo.png') }}" alt="Logo" style="width:50%;padding:2rem;" class="px-3"></div>
@endif