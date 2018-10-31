<?php
use App\Chat;
/*
|--------------------------------------------------------------------------
| Broadcast Channels
|--------------------------------------------------------------------------
|
| Here you may register all of the event broadcasting channels that your
| application supports. The given channel authorization callbacks are
| used to check if an authenticated user can listen to the channel.
|
*/

Broadcast::channel('App.User.{id}', function ($user, $id) {
    return (int) $user->id === (int) $id;
});

Broadcast::channel('message-channel.{from}.{to}' , function($user , $from , $to) {
    if($user->id == $from || $user->id == $to)
    {
        return true;
    }
    return false;
});

Broadcast::channel('error-channel.{userid}' , function($user , $userid){
    if($user->id == $userid)
    {
        return true;
    }
    return false;
});

Broadcast::channel('chat-channel.{chatid}.{to}' , function($user , $chatid , $to){
    //$chat = Chat::find($chatid);
    if($to == $user->id)
    {
        return true;
    }
    return false;
});
