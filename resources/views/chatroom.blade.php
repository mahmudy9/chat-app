@extends('layouts.app')

@section('content')

@if(!$messages)

    <h3>No Messages in your chat with {{$to->name}} yet</h3>
@else
    <div id="msgs">
    @foreach($messages as $message)
        <p><em><b>{{$message->fromusername}}</b></em> : {{$message->body}}</p>
        <hr>
    @endforeach
    </div>
@endif

<form id="chatform" >
<div class="form-group">
<input type="text" id="body"/>
</div>

<div class="form-group">
    <input type="submit" class="btn btn-primary" value="Send" />
</div>
</form>


@endsection

@section('script')
<script>
    var socket = io('http://localhost:3000');


    socket.removeAllListeners(`private-chat-channel.{{$chatid}}.{{auth()->user()->id}}:App\\Events\\ChatEvent`);
    socket.on(`private-chat-channel.{{$chatid}}.{{auth()->user()->id}}:App\\Events\\ChatEvent` , function(msg){
        //console.log(msg);
        $('#msgs').append(`<p><em><b>${msg.data.fromusername}</b></em> : ${msg.data.body}</p><hr>`)
    });

    $.ajaxSetup({
        headers : {
            'X-CSRF-TOKEN' : $('meta[name="csrf-token"]').attr('content')
        }
    })

    $('#chatform').submit(function(e) {
        e.preventDefault();
        var data = {
            body : $('#body').val(),
            chatid : {{$chatid}}
        }

        $.ajax({
            type: 'POST',
            url: "{{url('/store-message')}}",
            data: data,
            success: function(response) {
                console.log(response);
                var message=response;
                socket.removeAllListeners(`private-message-channel.${message.from}.${message.to}:App\\Events\\MessageEvent`);
                socket.on(`private-message-channel.${message.from}.${message.to}:App\\Events\\MessageEvent` , function(msg) {
                    console.log(msg);
                    $('#msgs').append(`<p><em><b>${msg.data.fromusername}</b></em> : ${msg.data.body}</p><hr>`);
                    $('#body').val('');
                });
            },
            error: function(jqXhr , statusText , errorThrown) {
                socket.removeAllListeners(`private-error-channel.{{auth()->user()->id}}:App\\Events\\ErrorMessage`);
                socket.on(`private-error-channel.{{auth()->user()->id}}:App\\Events\\ErrorMessage` , function(msg){
                    console.log(msg);
                });
                console.error(errorThrown , statusText);
            },
        });

    });


/*    $('#chatform').submit(function(e) {
        e.preventDefault();
        var body = $('#body').val();
        var chatid ={{$chatid}} ;
        axios.post('/store-message' , {
            body:body,
            chatid:chatid
        }).then( function(response){
            console.log(response);
            var message=response;
            socket.removeAllListeners(`private-message-channel.${message.data.from}.${message.data.to}:App\\Events\\MessageEvent`);
            socket.on(`private-message-channel.${message.data.from}.${message.data.to}:App\\Events\\MessageEvent` , function(msg) {
                console.log(msg);
                $('#msgs').append(`<p><em><b>${msg.data.fromusername}</b></em> : ${msg.data.body}</p><hr>`);
                $('#body').val('');
            });
        }).catch(function(error){
            socket.removeAllListeners(`private-error-channel.{{auth()->user()->id}}:App\\Events\\ErrorMessage`);
            socket.on(`private-error-channel.{{auth()->user()->id}}:App\\Events\\ErrorMessage` , function(msg){
                console.log(msg);
            });
            console.error(error);
        });
    })
*/
</script>
@endsection
