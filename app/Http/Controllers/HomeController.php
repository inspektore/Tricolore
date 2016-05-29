<?php

namespace Tricolore\Http\Controllers;

use Tricolore\Http\Requests;
use Illuminate\Http\Request;
use Event;

class HomeController extends Controller
{
    public $default = 'home';

    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct()
    {
        $this->middleware('auth');
    }

    /**
     * Show the application dashboard.
     *
     * @return \Illuminate\Http\Response
     */
    public function home()
    {
        Event::fire(new \Tricolore\Events\HomepageWillBeRendered($this));

        return view($this->default);
    }
}
