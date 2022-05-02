<?php

namespace App\Http\Controllers;

use http\Env\Response;
use Illuminate\Http\Request;
use App\Models\User;
use Illuminate\Support\Facades\Auth;

class AuthController extends Controller
{
    /**
     * Register
     *
     * @param Illuminate\Http\Request $request
     *
     * @return \Illuminate\Http\JsonResponse
     */
    public function register(Request $request) {
        $this->validate($request, [
            'name' => 'required|min:3',
            'email' => 'required|email',
            'password' => 'required|min:8',
        ]);

        $user = User::create([
            'name' => $request->name,
            'email' => $request->email,
            'password' => bcrypt($request->password),
        ]);

        $token = $user->createToken($request->name)->accessToken;

        return response()->json(['token'=>$token], 200);
    }

    /**
     * Login
     *
     * @param  Illuminate\Http\Request $request
     *
     * @return \Illuminate\Http\JsonResponse
     */
    public function login(Request $request) {
        $accessData = [
            'email' => $request->email,
            'password' => $request->password
        ];

        if (auth()->attempt($accessData)) {
            $token = auth()->user()->createToken($request->name)->accessToken;
            return response()->json(['token' => $token, 'message' => 'login Success', 'user' => auth()->user()], 200);
        } else {
            return response()->json(['error' => 'Unauthorised'], 401);
        }
    }

    /**
     * Current Logined User Info
     *
     * @param Illuminate\Http\Request $request
     *
     * @return \Illuminate\Http\JsonResponse
     */
    public function details() {
        return response()->json(['user'=> Auth::user()], 200);
    }

    /**
     * Logout
     *
     * @param Request $request
     *
     * @return \Illuminate\Http\JsonResponse
     */
    public function logout(Request $request) {
        $accessToken = $request->user()->token();
        $accessToken->revoke();
        return response()->json(['message'=> 'Successfully logout'], 200);
    }

}
