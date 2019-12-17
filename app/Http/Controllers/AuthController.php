<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class AuthController extends Controller
{
    public function login(Request $request)
    {
        $formData = [
            'form_params' => [
                'grant_type' => 'password',
                'client_id' => 2,
                'client_secret' => 'euF4MGHGXTLW4ak1GdI7GDR8fwDZaq26tUciWyG4',
                'username' => $request->username,
                'password' => $request->password,
            ],
        ];

        $http = new \GuzzleHttp\Client;

        try{
            $response = $http->post('localhost:8002/oauth/token', $formData);
            return $response->getBody();

        }catch(\GuzzleHttp\Exception\BadResponseException $e) {
            if($e->getCode() == 400) {
                return response()->json('Invalid Request', $e->getCode());
            } else if($e->getCode() === 401) {
                return response()->json('Your enter data is incorrect', $e->getCode());
            }

            return response()->json('somthing went wrong', $e->getCode());
        }
    }
}
