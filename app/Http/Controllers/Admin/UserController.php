<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\User;

class UserController extends Controller
{
    public function users()
    {
        $title = 'Daftar Customer';
        $users = User::get()->toArray();

        return view('admin.users.users')->with(compact('title', 'users'));

    }
}
