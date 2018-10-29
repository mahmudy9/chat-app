@extends('layouts.app')

@section('content')

@if(!$messages)

    <h3>No Messages in your chat with {{$to->name}} yet</h3>
@else
    <div id="msgs">
    @foreach($messages as $message)
        <p><em><b>{{App\User::find($message->from)->name}}</b></em> : {{$message->body}}</p>
        <hr>
    @endforeach
    </div>
@endif



<form  id="chatstore" v-on:submit.prevent="sendmessage" >
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
    var self= 'hi';
    var app = new Vue({
        el:'#app',
        data:{
            body:'',
            chatid:{{$chatid}},
            socket:socket,
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
                        console.log(msg);
                        $('#msgs').append(`<p><em><b>{{$from->name}}</b></em> : ${msg.data.body}</p><hr>`);
                        this.body='';
                    });
                }).catch(function(error){
                    this.socket.on(`private-error-channel.{{auth()->user()->id}}:App\\Events\\ErrorMessage` , function(msg){
                        console.log(msg);
                    });
                    console.error(error);
                })
            },
            send_message:function(){
                this.socket.on(`private-error-channel.{{auth()->user()->id}}` , function(msg){
                    console.log(msg);
                })
            },
            getmessages:function() {
                axios.get('/chatroom/'+this.chatid).then(function(response){
                    console.log(response);
                }).catch(function(err) {
                    console.log(err);
                })
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
