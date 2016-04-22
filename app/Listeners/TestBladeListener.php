<?php

namespace Tricolore\Listeners;

use Auth;

class TestBladeListener
{
    /**
     * Subscribe listeners
     *
     * @param Illuminate\Events\Dispatcher $events
     * @return void
     */
    public function subscribe($events)
    {
        $events->listen('blade_test_hello_user', 'Tricolore\Listeners\TestBladeListener@showName');
    }

    /**
     * Show name
     *
     * @return string
     */
    public function showName()
    {
        return Auth::user()->name;
    }
}
