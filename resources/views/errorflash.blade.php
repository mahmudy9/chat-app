@if(Session::has('error'))
    <div class="alert alert-danger">
            {!! session('error') !!}
    </div>
@endif
