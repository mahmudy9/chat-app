<!doctype html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="csrf-token" content="{{csrf_token()}}">
    <title>FiveOne Socket.io</title>

</head>
<body>
    <div id="app" >

@yield('content')
</div>
<script src="https://ajax.googleapis.com/ajax/libs/jquery/2.1.4/jquery.min.js"></script>

@yield('footer')
</body>
</html>
