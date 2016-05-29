<?php

namespace Tricolore\Events;

use Tricolore\Events\Event;
use Tricolore\Http\Controllers\HomeController;
use Illuminate\Queue\SerializesModels;
use Illuminate\Contracts\Broadcasting\ShouldBroadcast;

class HomepageWillBeRendered extends Event
{
    use SerializesModels;

    public $controller;

    /**
     * Create a new event instance.
     *
     * @return void
     */
    public function __construct(HomeController $controller)
    {
        $this->controller = $controller;
    }

    /**
     * Get the channels the event should be broadcast on.
     *
     * @return array
     */
    public function broadcastOn()
    {
        return [];
    }
}
