<?php

namespace App\Http\Controllers;

use App\Http\Requests\StoreTaskRequest;
use App\Http\Requests\UpdateTaskRequest;
use Illuminate\Http\Request;
use App\Services\TaskService;
use Illuminate\Support\Facades\Log;

class TaskController extends Controller
{
    protected $taskService;
    protected $fileService;

    public function __construct(TaskService $taskService)
    {
        $this->taskService = $taskService;
    }

    public function index(Request $request)
    {
        $user = auth()->guard('sanctum')->user();
        $user_id = $user->id;

        $request->validate([
            'page' => 'integer|min:1',
            'per_page' => 'integer|min:1|max:100',
        ]);

        $users = $this->taskService->all($user_id);
        return response()->json($users);
    }

    public function show($id)
    {
        $user = auth()->guard('sanctum')->user();
        $user_id = $user->id;

        $task = $this->taskService->show($id);

        if ($task->user_id !== $user_id) {
            return response()->json(['message' => 'Unauthorized'], 401);
        }

        return response()->json($task);
    }

    public function store(StoreTaskRequest $request)
    {
        $task = $this->taskService->create($request);
        return response()->json($task, 201);
    }

    public function update(UpdateTaskRequest $request, $id)
    {
        $task = $this->taskService->update($request, $id);
        return response()->json($task);
    }

    public function destroy($id)
    {
        $user = auth()->guard('sanctum')->user();
        $task = $this->taskService->show($id);

        if ($task->user_id !== $user->id) {
            return response()->json(['message' => 'Unauthorized'], 401);
        }

        $this->taskService->destroy($id);
    }
}
