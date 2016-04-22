<?php

namespace Tricolore\Listeners;

use Tricolore\Events\HomepageWillBeRendered;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Contracts\Queue\ShouldQueue;

class ModifyHomepage
{
    public $default;

    /**
     * Create the event listener.
     *
     * @return void
     */
    public function __construct()
    {
        //
    }

    /**
     * Handle the event.
     *
     * @param  HomepageWillBeRendered  $event
     * @return void
     */
    public function handle(HomepageWillBeRendered $event)
    {
        return $event->controller->default = 'home';
    }
}
