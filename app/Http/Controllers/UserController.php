<?php

namespace App\Http\Controllers;

use App\User;
use Illuminate\Http\Request;

class UserController extends Controller
{
    public function get(){
        return ['users' => User::all(), 'fields' => []];
    }

    public function set(Request $request){

        $user = User::findOrFail($request->get('id'));
        $user->email = $request->get('email');
        $user->name = $request->get('name');
        $user->save();

        return ['status' => 'success'];
    }
}
