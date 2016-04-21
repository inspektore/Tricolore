<?php

namespace Tricolore\Http\Controllers\User;

use Illuminate\Http\Request;

use Tricolore\User;
use Tricolore\Http\Requests;
use Tricolore\Http\Controllers\Controller;

class UserController extends Controller
{
    /**
     * Show the user profile
     *
     * @param bool 
     * @throws \Exception
     * @return void
     */
    public function show(User $user, $name, $id)
    {
        $user = $user->findOrFail($id);

        return view('user.profile', compact('user'));
    }
}
