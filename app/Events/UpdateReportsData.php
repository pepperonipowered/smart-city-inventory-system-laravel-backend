<?php

namespace App\Events;

use Illuminate\Broadcasting\Channel;
use Illuminate\Broadcasting\InteractsWithSockets;
use Illuminate\Broadcasting\PresenceChannel;
use Illuminate\Broadcasting\PrivateChannel;
use Illuminate\Contracts\Broadcasting\ShouldBroadcast;
use Illuminate\Foundation\Events\Dispatchable;
use Illuminate\Queue\SerializesModels;

class UpdateReportsData implements ShouldBroadcast
{
    use Dispatchable, InteractsWithSockets, SerializesModels;


    public $report;
    /**
     * Create a new event instance.
     */
    public function __construct($report = null)
    {
        //
        $this->report = $report;
    }

    /**
     * Get the channels the event should broadcast on.
     *
     * @return array<int, \Illuminate\Broadcasting\Channel>
     */
    public function broadcastOn(): array
    {
        return [
            new Channel('report-updates'),
        ];
    }

    public function broadcastAs()
    {
        return 'report.updated';
    }

    public function broadcastWith()
    {
        return [
            'report' => $this->report,
        ];
    }
}
