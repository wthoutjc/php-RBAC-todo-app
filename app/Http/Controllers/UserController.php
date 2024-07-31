<?php

namespace App\Http\Controllers;

use App\Http\Requests\StoreUserRequest;
use App\Http\Requests\UpdateUserRequest;
use App\Services\UserService;
use Illuminate\Support\Facades\Log;

class UserController extends Controller
{
    protected $userService;

    public function __construct(UserService $userService)
    {
        $this->userService = $userService;
    }

    public function me()
    {
        try {
            $user = auth()->guard('sanctum')->user();
            Log::debug('User me', ['user' => $user]);

            return response()->json($user);
        } catch (\Exception $e) {
            dd($e);
            return response()->json(['error' => $e->getMessage()], 500);
        }
    }

    public function index()
    {
        // $csrfToken = csrf_token();
        $users = $this->userService->all();
        return response()->json($users);
    }

    public function show($id)
    {
        $user = $this->userService->find($id);
        return response()->json($user);
    }

    public function store(StoreUserRequest $request)
    {
        $user = $this->userService->create($request->all());
        return response()->json($user);
    }

    public function update(UpdateUserRequest $request, $id)
    {
        $user = $this->userService->update($request->all(), $id);
        return response()->json($user);
    }

    public function destroy($id)
    {
        $this->userService->destroy($id);
    }
}
