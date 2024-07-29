<?php

namespace App\Http\Controllers;

use App\Http\Requests\StoreUserRequest;
use App\Http\Requests\UpdateUserRequest;
use App\Services\UserService;
use Illuminate\Http\Request;

class UserController extends Controller
{
    protected $userService;

    public function __construct(UserService $userService)
    {
        $this->userService = $userService;
    }

    public function index()
    {
        $csrfToken = csrf_token();
        $users = $this->userService->all();
        return response()->json([
            'csrf_token' => $csrfToken,
            'data' => $users,
        ]);
    }

    public function show(Request $request, $id)
    {
        $request->validate([
            'id' => 'required|uuid|exists:users,id',
        ]);

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
