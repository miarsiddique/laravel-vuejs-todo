<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Validator;
use App\Core\ClientGuzzul;
use DB;


class AuthController extends Controller
{
    protected $client;

    public function __construct(ClientGuzzul $client)
    {
        $this->client = $client;
    }

    public function login(Request $request)
    {
        try{

            $id = DB::table('oauth_clients')->where('id' , 7)->first()->id;
            $secret = DB::table('oauth_clients')->where('id' , 7)->first()->secret;

            $response = $this->client->post('oauth/token', [
                'grant_type' => 'password',
                'client_id' => $id,
                'client_secret' => $secret,
                'username' => $request->username,
                'password' => $request->password,
                'scope' => '*'
            ]);

            return $response;

        }catch(\Exception $e) {
            \Log::info($e->getMessage());
            if($e->getCode() === 400) {
                return response()->json('Invalid Request', $e->getCode());
            } else if($e->getCode() === 401) {
                return response()->json('Your enter data is incorrect', $e->getCode());
            }

            return response()->json('somthing went wrong', $e->getCode());
        }
    }

    public function register(Request $request)
    {
        $request->validate([
            'name' => ['required', 'string', 'max:255'],
            'email' => ['required', 'string', 'email', 'max:255', 'unique:users'],
            'password' => ['required', 'min:6'],
        ]);

        return \App\Models\User::create([
            'name' => $request->name,
            'email' => $request->email,
            'password' => Hash::make($request->password),
        ]);
    }

    public function logout()
    {
        auth()->user()->tokens->each(function($token, $key) {
            $token->delete();
        });

        return response()->json('logout successfully. ');
    }
}
