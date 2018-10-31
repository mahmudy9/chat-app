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



<form v-on:submit.prevent="sendmessage()" >
<div class="form-group">
<input type="text" v-model="body"/>
</div>

<div class="form-group">
    <input type="submit" class="btn btn-primary" value="Send" />
</div>
</form>


@endsection

@section('script')
<script>
    var socket = io('http://localhost:3000');
    socket.on(`private-chat-channel.{{$chatid}}.{{auth()->user()->id}}:App\\Events\\ChatEvent` , function(msg){
        //console.log(msg);
        $('#msgs').append(`<p><em><b>${msg.data.fromusername}</b></em> : ${msg.data.body}</p><hr>`)
    });
    var self= 'hi';
    var app = new Vue({
        el:'#app',
        data:{
            body:'',
            chatid:{{$chatid}},
            socket:io('http://localhost:3000'),
            message:'',
            self:self
        },
        methods:{
            sendmessage:function() {
                axios.post('/store-message' , {
                    body:this.body,
                    chatid:this.chatid
                }).then( function(response){
                    this.message=response;
                    this.socket.on(`private-message-channel.${this.message.data.from}.${this.message.data.to}:App\\Events\\MessageEvent` , function(msg) {
                        //console.log(msg);
                        $('#msgs').append(`<p><em><b>${msg.data.fromusername}</b></em> : ${msg.data.body}</p><hr>`);
                        this.body='';
                    });
                }).catch(function(error){
                    this.socket.on(`private-error-channel.{{auth()->user()->id}}:App\\Events\\ErrorMessage` , function(msg){
                        console.log(msg);
                    });
                    console.error(error);
                });
            }
        },
        mounted:function() {
            // this.getmessages();
        },
        updated:function() {

        }
    })

</script>
@endsection
