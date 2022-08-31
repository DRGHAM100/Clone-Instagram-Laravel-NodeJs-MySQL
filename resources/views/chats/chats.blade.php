@foreach($freinds as $freind)
    <div>
        <a href="{{ route('chats',['id' => $freind->id]) }}" class="list-group-item list-group-item-action border-0">
           <div id="unread-count-{{$freind->user_id}}">
            @if( $freind->user->unread_messages($freind->user->id) != 0)
                <div class="badge bg-success float-right">{{ $freind->user->unread_messages($freind->user->id) }}</div>
            @endif
           </div>
            <div class="d-flex align-items-start">
                <img src="{{ $freind->user->profile->profileImage() }}" class="rounded-circle mr-1" alt="Vanessa Tucker" width="40" height="40" />
                <div class="flex-grow-1 ml-3">
                    {{ $freind->user->username }}
                    <div class="small" id="status_{{$freind->user_id}}">
                        @if($freind->user->is_online == 0)
                            <span class="fa fa-circle chat-offline"></span> Offline
                        @else
                            <span class="fa fa-circle chat-online"></span> Online
                        @endif
                    </div>
                </div>
            </div>
        </a>
        <hr class="d-block d-lg-none mt-1 mb-0" />
    </div>
@endforeach