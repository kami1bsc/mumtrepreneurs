<?php

namespace App\Events;

use Illuminate\Broadcasting\Channel;
use Illuminate\Broadcasting\InteractsWithSockets;
use Illuminate\Broadcasting\PresenceChannel;
use Illuminate\Broadcasting\PrivateChannel;
use Illuminate\Contracts\Broadcasting\ShouldBroadcast;
use Illuminate\Foundation\Events\Dispatchable;
use Illuminate\Queue\SerializesModels;
use App\Models\User;

class GetMessages implements ShouldBroadcast
{
    use Dispatchable, InteractsWithSockets, SerializesModels;

    protected $user;
    protected $messages;

    /**
     * Create a new event instance.
     *
     * @return void
     */
    public function __construct(User $user, $messages)
    {
        $this->user = $user;
        $this->messages = $messages;
    }
    
    public function broadcastWith()
    {
        return array($this->messages);
    }
    
    public function broadcastAs()
    {
        return 'messages';
    }

    /**
     * Get the channels the event should broadcast on.
     *
     * @return \Illuminate\Broadcasting\Channel|array
     */
    public function broadcastOn()
    {
        return ['chat.'.$this->user->id.'.messages'];
    }
}
