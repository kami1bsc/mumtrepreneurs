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

class NotificationCounter implements ShouldBroadcast
{
    use Dispatchable, InteractsWithSockets, SerializesModels;

    protected $user;
    protected $messanger;

    /**
     * Create a new event instance.
     *
     * @return void
     */
    public function __construct(User $user, $messanger)
    {
        $this->user = $user;
        $this->messanger = $messanger;
    }
    
    public function broadcastWith()
    {
        return array(
            'status' => true,
            'message' => 'Data Found',
            'data' => $this->messanger
        );
    }
    
    public function broadcastAs()
    {
        return 'notification_counter';
    }

    /**
     * Get the channels the event should broadcast on.
     *
     * @return \Illuminate\Broadcasting\Channel|array
     */
    public function broadcastOn()
    {
        return ['notification.'.$this->user->id.'.counter'];
    }
}
