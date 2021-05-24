<?php

namespace App\Http\Controllers;

use App\Http\Requests\UserCreateRequest;
use App\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;

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

    public function createUser() {
        return view('user.create');
    }

    public function addUser(UserCreateRequest $request) {

        $user = $request->except(['role']);
        $user['password'] = Hash::make($request->get('password'));

        $user = User::create($user);
//        $user->password = Hash::make($request->get('password'));

        $user->assignRole($request->get('role'));

        dd($request->all());
    }
}
