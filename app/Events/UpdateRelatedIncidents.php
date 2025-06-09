<?php

namespace App\Events;

use Illuminate\Broadcasting\Channel;
use Illuminate\Broadcasting\InteractsWithSockets;
use Illuminate\Broadcasting\PresenceChannel;
use Illuminate\Broadcasting\PrivateChannel;
use Illuminate\Contracts\Broadcasting\ShouldBroadcast;
use Illuminate\Foundation\Events\Dispatchable;
use Illuminate\Queue\SerializesModels;

class UpdateRelatedIncidents implements ShouldBroadcast
{
    use Dispatchable, InteractsWithSockets, SerializesModels;

    public $barangay_id;
    /**
     * Create a new event instance.
     */
    public function __construct($barangay_id)
    {
        $this->barangay_id = $barangay_id;
    }

    /**
     * Get the channels the event should broadcast on.
     *
     * @return array<int, \Illuminate\Broadcasting\Channel>
     */
    public function broadcastOn(): array
    {
        return [
            new Channel('related-incidents'),
        ];
    }

    public function broadcastAs()
    {
        return 'related-incidents.updated';
    }

    public function broadcastWith()
    {
        return [
            'barangay_id' => $this->barangay_id,
        ];
    }
}
