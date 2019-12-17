<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Validator;
use GuzzleHttp;

class AuthController extends Controller
{
    public function login(Request $request)
    {

        try{

            /* $client = new \GuzzleHttp\Client();
            $response = $client->request('POST', 'http://localhost:8001/api/store', [
                'form_params' => [
                    'name' => 'krunal',
                ]
            ]);
            $response = $response->getBody()->getContents(); */

            $client = new \GuzzleHttp\Client();

            $formData = [
                'form_params' => [
                        'grant_type' => 'password',
                        'client_id' => 2,
                        'client_secret' => 'UCOvQtVZWBpQ1qCdcEEF3FLhTpxUU8KAb7bU7non',
                        'username' => $request->username,
                        'password' => $request->password,
                    ]
            ];

            $headers = [
                'headers' => [
                    'Accept' => 'application/json',
                    'Content-type' => 'application/json'
                ]
            ];

            $response = $client->request('POST', 'http://01a5c73e.ngrok.io/oauth/token', $headers, $formData);
            $response = $response->getBody()->getContents();
			$response = json_decode($response, true);
            \Log::info($response);
            return $response;

        }catch(\Exception $e) {
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
