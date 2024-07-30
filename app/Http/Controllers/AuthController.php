<?php

namespace App\Http\Controllers;

use App\Http\Requests\LoginRequest;
use App\Http\Requests\RegisterRequest;
use Illuminate\Http\Request;
use App\Services\UserService;
use App\Services\RoleService;
use Illuminate\Support\Facades\Auth;
use Laravel\Socialite\Facades\Socialite;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\Log;

class AuthController extends Controller
{
    protected $userService;
    protected $roleService;

    public function __construct(UserService $userService, RoleService $roleService)
    {
        $this->userService = $userService;
        $this->roleService = $roleService;
    }

    public function register(RegisterRequest $request)
    {
        try {
            $user_role = $this->roleService->show('user');

            $user = $this->userService->create([
                'name' => $request->name,
                'email' => $request->email,
                'password' => bcrypt($request->password),
                'role_id' => $user_role->id,
            ]);

            Log::debug('User created', ['user' => $user]);

            $token = $user->createToken('authToken', ['role:user'])->plainTextToken;
            Log::debug($token);

            return response()->json(['token' => $token, 'user' => $user]);
        } catch (\Exception $e) {
            var_dump($e);
            Log::error("[ERROR] AuthController.register - ", ['error' => $e]);
            return response()->json(['error' => "Error: {$e}"], 500);
        }
    }

    public function login(LoginRequest $request)
    {
        try {
            $credentials = $request->only('email', 'password');

            if (!Auth::attempt($credentials)) {
                return response()->json(['message' => 'Unauthorized'], 401);
            }

            $user = $this->userService->find(Auth::id());
            $token = match ($user->role->name) {
                'admin' => $user->createToken('authToken', ['role:admin'])->plainTextToken,
                default => $user->createToken('authToken', ['role:user'])->plainTextToken,
            };

            return response()->json(['token' => $token, 'user' => $user]);
        } catch (\Exception $e) {
            Log::error("[ERROR] AuthController.login - " . $e);
            return response()->json(['error' => "Error: {$e}"], 500);
        }
    }

    public function redirectToGoogle()
    {
        return Socialite::driver('google')->redirect();
    }


    public function handleGoogleCallback()
    {
        try {
            $googleUser = Socialite::driver('google')->user();
            $user = $this->userService->findByGoogleId($googleUser->getId());

            $user_role = $this->roleService->show('user');

            if (!$user) {
                $user = $this->userService->create([
                    'email' => $googleUser->getEmail(),
                    'name' => $googleUser->getName(),
                    'google_id' => $googleUser->getId(),
                    'avatar' => $googleUser->getAvatar(),
                    'role_id' => $user_role->id,
                    'password' => bcrypt(Str::random(24)),
                    'remember_token' => Str::random(10),
                ]);
            }

            $token = $user->createToken('authToken')->plainTextToken;

            return response()->json(['token' => $token, 'user' => $user]);
        } catch (\Exception $e) {
            Log::error("[ERROR] AuthController.handleGoogleCallback - " . $e);
            return response()->json(['error' => "Error: {$e}"], 500);
        }
    }

    public function logout(Request $request)
    {
        $request->user()->currentAccessToken()->delete();

        return response()->json(['message' => 'Logged out successfully']);
    }
}
