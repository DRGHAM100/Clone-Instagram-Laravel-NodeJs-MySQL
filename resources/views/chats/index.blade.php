@extends('layouts.app')

@section('content')
<div class="container">
<div id="snippetContent"> 
            <main class="content">
                <div class="container p-0"> 
                    <div class="card">
                        <div class="row g-0">
                            <div class="col-12 col-lg-5 col-xl-3 border-right"> 
                                @include('chats.chats')
                            </div> 
                            <div class="col-12 col-lg-7 col-xl-9">
                                @include('chats.chat_box')
                            </div>
                        </div>
                    </div>
                </div>
            </main> 
        </div>
</div>
@endsection

@section('scripts')
<script>
    var tout;
    $(function(){
        var user_id = '{{ Auth::user()->id }}';
        var other_user_id = '{{ ($otherUser) ? $otherUser->id : '' }}';
        var other_user_name = '{{ ($otherUser) ? $otherUser->name : '' }}';
        var socket = io("http://localhost:3000",{query:{user_id:user_id}});
        $('#chat-form').on('submit',function(e){
            e.preventDefault();
            var message = $('#message-input').val();
            if(message.trim().length == 0){
                $('#message-input').focus()
            }else{
                var data = {
                    user_id: user_id,
                    other_user_id: other_user_id,
                    other_user_name: other_user_name,
                    message: message
                }

                socket.emit('send_message',data);
                $('#message-input').val('');
            }
        });


        socket.on('receive_message',function(data){
            console.log(data);
            if(data.user_id == user_id && data.other_user_id == other_user_id || data.other_user_id == user_id && data.user_id == other_user_id){
                @if($otherUser)
                if(data.user_id == user_id){  
                    var html = `
                                <div class="chat-message-right pb-4">
                                    <div>
                                        <img src="{{ auth()->user()->profile->profileImage() }}" class="rounded-circle mr-1" alt="Chris Wood" width="40" height="40" />
                                        <div class="text-muted small text-nowrap mt-2">
                                            ${data.time }
                                        </div>
                                    </div>
                                    <div class="flex-shrink-1 bg-light rounded py-2 px-3 mr-3">
                                        <div class="font-weight-bold mb-1">You</div>
                                        ${data.message }
                                    </div>
                                </div>`;
                }else{
                    socket.emit('read_message',data.id);
                    var html = `
                                <div class="chat-message-left pb-4">
                                    <div>
                                        <img src="{{ $otherUser->profile->profileImage() }}" class="rounded-circle mr-1" alt="Sharon Lessman" width="40" height="40" />
                                        <div class="text-muted small text-nowrap mt-2">
                                            ${data.time }
                                        </div>
                                    </div>
                                    <div class="flex-shrink-1 bg-light rounded py-2 px-3 ml-3">
                                        <div class="font-weight-bold mb-1">{{ $otherUser->username }}</div>
                                        ${data.message }
                                    </div>
                                </div>`;
                }  

                $('.chat-messages').append(html);
                $('.chat-messages').animate({ scrollTop: $('.chat-messages').prop("scrollHeight") }, 1000);
                @endif  
            }else{    
                $('#unread-count-'+data.user_id).html(' <div class="badge bg-success float-right">'+data.unread_messages+'</div>')
            }
        });

        socket.on('user_typing',function(data){
            if(data.user_id == other_user_id){
                $('#typing-'+data.user_id).html('<em>Typing...</em>');
            }
            clearTimet();
            clearTyping();
            
        });

        $('#message-input').on('keyup',function(){
            socket.emit('user_typing',{user_id: user_id,other_user_id: other_user_id});
        })
    });

    function clearTyping(){
        tout = setTimeout(() => {
            $('#typing-{{ ($otherUser) ? $otherUser->id : '' }}').html('<em></em>');
        }, 2000);
    }

    function clearTimet(){
        clearTimeout(tout);
    }

</script>
@endsection
