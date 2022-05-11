<?php

use Illuminate\Support\Facades\Broadcast;

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

Broadcast::channel('App.Models.User.{id}', function ($user, $id) {
    return (int) $user->id === (int) $id;
});


Broadcast::channel('chat.{id}.messanger', function ($user, $messanger) {
    return (int) $user->user_id === (int) $id;
});

Broadcast::channel('notification.{id}.counter', function ($user, $messanger) {
    return (int) $user->user_id === (int) $id;
});

Broadcast::channel('chat.{id}.messages', function ($user, $messages) {
    return (int) $user->user_id === (int) $id;
});

Broadcast::channel('counter', function () {
    return true;
});
