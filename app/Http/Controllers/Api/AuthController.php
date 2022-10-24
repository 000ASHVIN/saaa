<?php

namespace App\Http\Controllers\Api;

use App\Http\Requests;
use Tymon\JWTAuth\JWTAuth;
use Illuminate\Http\Request;
use Illuminate\Mail\Message;
use App\Traits\ResetsPasswords;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Password;
use Tymon\JWTAuth\Exceptions\{JWTException};

class AuthController extends Controller
{
    use ResetsPasswords;

    private $auth;    

    public function __construct(JWTAuth $auth)
    {
        $this->auth = $auth;
    }

    public function login(Request $request)
    {
        try {
            $token = $this->auth->attempt($request->only('email', 'password'));

            if(! $token) {
                return response()->json([
                    'errors' => [
                        'root' => 'Could not sign you in with the provided details'
                    ]
                ], 401);
            }
        } catch (JWTException $e) {
            return response()->json([
                'errors' => [
                    'root' => 'Failed'
                ]
            ], $e->getStatusCode());
        }

        return response()->json([
            'data' => auth()->user(),
            'meta' => [
                'token' => $token
            ]
        ], 200);
   
    }


    /**
     * Logged in User
     *
     * @return \Illuminate\Http\JsonResponse
     */
    public function user()
    {
        return response()->json([
            'data' => request()->user()
        ]);
    }

    /**
     * Logout a User
     *
     * @return \Illuminate\Contracts\Routing\ResponseFactory|\Symfony\Component\HttpFoundation\Response
     */
    public function logout()
    {
        $this->auth->invalidate($this->auth->getToken());

        return response(null, 200);
    }

    /**
     * Send a reset link to the given user.
     *
     * @param  \Illuminate\Http\Request $request
     * @return \Illuminate\Http\Response
     */
    public function postEmail(Request $request)
    {
        $this->validate($request, ['email' => 'required|email']);

        $response = Password::sendResetLink($request->only('email'), function (Message $message) {
            $message->subject("Your Password Reset Link");
        });

        switch ($response) {
            case Password::RESET_LINK_SENT:                
                return response()->json(['message' => 'Password reset email sent.'], 200);

            case Password::INVALID_USER:
                alert()->error('Invalid email.', 'Error');
                return response()->json(['message' => 'Invalid email'], 422);
        }
    }

}
