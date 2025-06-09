<?php

namespace App\Events;

use Illuminate\Broadcasting\Channel;
use Illuminate\Broadcasting\InteractsWithSockets;
use Illuminate\Broadcasting\PresenceChannel;
use Illuminate\Broadcasting\PrivateChannel;
use Illuminate\Contracts\Broadcasting\ShouldBroadcast;
use Illuminate\Foundation\Events\Dispatchable;
use Illuminate\Queue\SerializesModels;

class UpdateBarangays implements ShouldBroadcast
{
    use Dispatchable, InteractsWithSockets, SerializesModels;

    public $barangay;
    /**
     * Create a new event instance.
     */
    public function __construct($barangay = null)
    {
        $this->barangay = $barangay;
    }

    /**
     * Get the channels the event should broadcast on.
     *
     * @return array<int, \Illuminate\Broadcasting\Channel>
     */
    public function broadcastOn(): array
    {
        return [
            new Channel('barangay-updated'),
        ];
    }

    public function broadcastAs(): string
    {
        return 'barangay.updated';
    }

    public function broadcastWith(): array
    {
        return [
            'barangay' => $this->barangay,
        ];
    }
}
