<?php

namespace App\Events;

use Illuminate\Broadcasting\Channel;
use Illuminate\Broadcasting\InteractsWithSockets;
use Illuminate\Broadcasting\PresenceChannel;
use Illuminate\Broadcasting\PrivateChannel;
use Illuminate\Contracts\Broadcasting\ShouldBroadcast;
use Illuminate\Foundation\Events\Dispatchable;
use Illuminate\Queue\SerializesModels;

class UpdateTrafficStatus implements ShouldBroadcast
{
    use Dispatchable, InteractsWithSockets, SerializesModels;

    /**
     * Create a new event instance.
     */

     public $road_id;
     public $status_id;
     public $direction;

    
    public function __construct($road_id, $status_id, $direction)
    {
        $this->road_id = $road_id;
        $this->status_id = $status_id;
        $this->direction = $direction;
    }

    /**
     * Get the channels the event should broadcast on.
     *
     * @return array<int, \Illuminate\Broadcasting\Channel>
     */
    public function broadcastOn(): array
    {
        return [
            new Channel('traffic-update'),
        ];
    }

     public function broadcastAs()
    {
        return 'direction.status.updated';
    }

    public function broadcastWith()
    {
        return [
            'road_id' => $this->road_id,
            'status_id' => $this->status_id,
            'direction' => $this->direction,
            'timestamp' => now()->timestamp
        ];
    }
}
