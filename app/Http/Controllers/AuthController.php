<?php

namespace App\Http\Controllers;

use App\Http\Requests\LoginUserRequest;
use App\Http\Requests\StoreUserRequest;
use App\Models\User;
use App\Traits\HttpResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Validator;


class AuthController extends Controller
{
    use HttpResponse;

    public function login(LoginUserRequest $request)
    {

        $request->validated($request->all());

        if(!Auth::attempt($request->only(['email', 'password']))) {
            return $this->onError("","There is no a such user", 401);
        }

        $user= User::where('email', $request->email)->first();


        return $this->onSucces([
            'user' => $user,
            'token'=> $user->createToken('Api Token of '. $user->name)->plainTextToken
            ]);

    }

    public function register(StoreUserRequest $request)
    {


        $request->validated($request->all());


        $user = User::create([
        'name'=> $request->name,
        'email'=> $request->email,
        'password'=> Hash::make($request->password),

        ]);

        return $this->onSucces([
            'user'=>$user,
            'token'=>$user->createToken('API Token of ' . $user->name)->plainTextToken,
            ]);
    }

    public function logout()
    {
        return 'This is my logout';
    }


}
