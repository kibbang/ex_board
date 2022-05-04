<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\User;
use Illuminate\Support\Facades\Auth;
use Laravel\Passport\Client;

class AuthController extends Controller
{
    /**
     * Register
     *
     * @param Illuminate\Http\Request $request
     *
     * @bodyParam email string
     * @bodyParam name string
     * @bodyParam password string
     *
     * @return \Illuminate\Http\JsonResponse
     */
    public function register(Request $request) {
        $this->validate($request, [
            'name' => 'required|min:3',
            'email' => 'required|email',
            'password' => 'required|min:8',
        ]);

        User::create([
            'name' => $request->name,
            'email' => $request->email,
            'password' => bcrypt($request->password),
        ]);

        return response()->json(['message'=>'Register was success'], 201);
    }

    /**
     * @param string $userId
     * @param string $password
     * @return string|\Symfony\Component\HttpFoundation\Response
     * @throws \Exception
     */
    public function createAcessToken($userId, $password) {
        $credentials = array(
            'email'=>$userId,
            'password'=>$password
        );

        if (!Auth::attempt($credentials)) {
            return 'Login attempt was failed';
        }

        $client = Client::where('password_client', 1)->first();

        $data = [
            'grant_type'=>'password',
            'client_id'=>$client->id,
            'client_secret'=>$client->secret,
            'username'=>$userId,
            'password'=>$password,
            'scope'=>''
        ];

        $request = Request::create('/oauth/token', 'POST', $data);
        $response = app()->handle($request);

        return $response;
    }
    /**
     * Login
     *
     * @param  Illuminate\Http\Request $request
     *
     * @bodyParam email string
     * @bodyParam password string
     *
     * @return \Illuminate\Http\JsonResponse
     */
    public function login(Request $request) {
        $accessData = [
            'email' => $request->email,
            'password' => $request->password
        ];

        if (auth()->attempt($accessData)) {
            $token = $this->createAcessToken($request->email, $request->password);
            return $token;
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
        return response()->json(['user'=> Auth::user()], 201);
    }

    /**
     * Logout
     *
     * @param Request $request
     *
     * @authenticated
     *
     * @return \Illuminate\Http\JsonResponse
     */
    public function logout(Request $request) {
        $accessToken = $request->user()->token();
        $accessToken->revoke();
        return response()->json(['message'=> 'Successfully logout'], 201);
    }

}
