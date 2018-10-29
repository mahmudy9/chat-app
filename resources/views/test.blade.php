@extends('app')

@section('content')
    <p id="power">0</p>
@endsection

@section('footer')
    <script src="https://cdnjs.cloudflare.com/ajax/libs/socket.io/2.1.1/socket.io.js"></script>

    <script>
        var socket = io('http://localhost:3000');
        //var socket = io('http://192.168.10.10:3000');
        socket.on("private-test-channel:App\\Events\\MessageEvent", function(message){
            // increase the power everytime we load test route
            console.log(message);
            $('#power').text(parseInt($('#power').text()) + parseInt(message.data.power));
        });

    </script>
@endsection
