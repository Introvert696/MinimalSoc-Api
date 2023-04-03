<?php

namespace App\Http\Controllers;

use App\Models\Document;
use App\Models\User;
use Validator;
use Illuminate\Http\Request;

class AuthController extends Controller
{
    /**
     * Create a new AuthController instance.
     *
     * @return void
     */
    public function __construct()
    {
        $this->middleware('auth:api', ['except' => ['login', 'register']]);
    }
    /**
     * Get a JWT via given credentials.
     *
     * @return \Illuminate\Http\JsonResponse
     */
    public function login(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'email' => 'required|email',
            'password' => 'required|string|min:6',
        ]);
        if ($validator->fails()) {
            return response()->json($validator->errors(), 422);
        }
        if (!$token = auth()->attempt($validator->validated())) {
            return response()->json(['error' => 'Unauthorized'], 401);
        }
        return $this->createNewToken($token);
    }
    /**
     * Register a User.
     *
     * @return \Illuminate\Http\JsonResponse
     */
    public function register(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'name' => 'required|string|between:2,100',
            'lastname' => 'required',
            'birthday' => 'required',
            'user_photo' => '',
            'bg_image' => '',
            'login' => 'required',
            'email' => 'required|string|email|max:100|unique:users',
            'password' => 'required|string|confirmed|min:6',
        ]);
        if ($validator->fails()) {
            return response()->json($validator->errors()->toJson(), 400);
        }
        //Загрузка аватарки
        $user_photo = $request->file('photo_user');
        $user_photoName = $user_photo->getClientOriginalName();
        //Загрузка беэшки
        $bg = $request->file('bg_image');
        $bgName = $bg->getClientOriginalName();

        $user = User::create(array_merge(
            $validator->validated(),
            [
                'password' => bcrypt($request->password),
                'user_photo' => $user_photoName,
                'bg_image' => $bgName
            ]
        ));


        //dd($user_photoName);
        $user_photo->storeAs('/uploads', $user_photoName);
        $data['title']  = $user_photo->getClientOriginalName();
        $data['path'] = "/image/" . $user_photo->getClientOriginalName();
        $data['creater'] = $user->id;
        $document = Document::create($data);


        //dd($bgName);
        $bg->storeAs('/uploads', $bgName);
        $data['title']  = $bg->getClientOriginalName();
        $data['path'] = "/image/" . $bg->getClientOriginalName();
        $data['creater'] = $user->id;
        $document = Document::create($data);

        return response()->json([
            'message' => 'User successfully registered',
            'user' => $user
        ], 201);
    }

    /**
     * Log the user out (Invalidate the token).
     *
     * @return \Illuminate\Http\JsonResponse
     */
    public function logout()
    {
        auth()->logout();
        return response()->json(['message' => 'User successfully signed out']);
    }
    /**
     * Refresh a token.
     *
     * @return \Illuminate\Http\JsonResponse
     */
    public function refresh()
    {
        return $this->createNewToken(auth()->refresh());
    }
    /**
     * Get the authenticated User.
     *
     * @return \Illuminate\Http\JsonResponse
     */
    public function userProfile()
    {
        return response()->json(auth()->user());
    }
    /**
     * Get the token array structure.
     *
     * @param  string $token
     *
     * @return \Illuminate\Http\JsonResponse
     */
    protected function createNewToken($token)
    {
        return response()->json([
            'access_token' => $token,
            'token_type' => 'bearer',
            'expires_in' => auth()->factory()->getTTL() * 60,
            'user' => auth()->user()
        ]);
    }
}
