<?php

namespace App\Http\Controllers\Api;

use Illuminate\Support\Facades\Auth;
use App\Http\Controllers\Controller;
use App\Models\Cart;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Config;
use Tymon\JWTAuth\Facades\JWTAuth;
use Illuminate\Contracts\Auth\UserProvider;

class AuthController extends Controller
{
    /**
     * Create a new AuthController instance.
     *
     * @return void
     */
    public function __construct()
    {
        $this->middleware('auth:api', ['except' => ['login', 'registration']]);
    }

    /**
     * Get a JWT via given credentials.
     *
     * @return \Illuminate\Http\JsonResponse
     */
    public function login(Request $request)
    {
        try {
            $credentials = $request->only('email', 'password');
            $token = auth('api')->attempt($credentials);
            if (!$token) {
                return response()->json(['success' => 0, 'message' => 'Email & Password does not match with our records'], 404);
            }
        } catch (\Tymon\JWTAuth\Exceptions\TokenExpiredException $e) {

            return response()->json(['success' => 0, 'message' => 'token expired'], 500);
        } catch (\Tymon\JWTAuth\Exceptions\TokenInvalidException $e) {

            return response()->json(['success' => 0, 'message' => 'token invalid'], 500);
        } catch (\Tymon\JWTAuth\Exceptions\JWTException $e) {

            return response()->json(['success' => 0, 'message' => 'unknown error'], 500);
        }

        $user = auth('api')->user();
        return response()->json([
            'success' => 1,
            'access_token' => $token,
            'user' => $user
        ]);
    }

    function checkLogin()
    {
        if (Auth::user()) {
            return response()->json(['success' => 1]);
        }

        return response()->json(['success' => 0]);
    }

    /**
     * Get the authenticated User.
     *
     * @return \Illuminate\Http\JsonResponse
     */
    public function me()
    {
        return response()->json(auth()->user());
    }

    /**
     * Log the user out (Invalidate the token).
     *
     * @return \Illuminate\Http\JsonResponse
     */
    public function logout()
    {
        auth()->logout();

        return response()->json(['message' => 'Successfully logged out']);
    }

    /**
     * Refresh a token.
     *
     * @return \Illuminate\Http\JsonResponse
     */
    public function refresh()
    {
        return $this->respondWithToken(auth()->refresh());
    }

    /**
     * Get the token array structure.
     *
     * @param  string $token
     *
     * @return \Illuminate\Http\JsonResponse
     */
    protected function respondWithToken($token)
    {
        return response()->json([
            'access_token' => $token,
            'token_type' => 'bearer',
            'expires_in' => auth('api')->factory()->getTTL() * 60
        ]);
    }

    public function registration(Request $request)
    {
        try {
            $this->validate($request, [
                'name' => 'required|max: 255',
                'email' => 'required|max: 255',
                'password' => 'required|max: 255',
            ]);
            User::create([
                'user_type' => 1,
                'name' => $request->name,
                'email' => $request->email,
                'password' => bcrypt($request->password),
            ]);
            return response()->json(["success" => 1, "message" => "Success"], 200);
        } catch (\Exception $e) {
            return response()->json(["success" => 0, "message" => "Something Error!"], 401);
        }
    }

    public function users()
    {
        $users = User::orderBy('name', 'asc')->get();
        return response()->json($users);
    }
}
