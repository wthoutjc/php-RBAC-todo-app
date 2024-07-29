<?php

namespace App\Http\Controllers;

use App\Http\Requests\StoreTaskRequest;
use App\Http\Requests\UpdateTaskRequest;
use Illuminate\Http\Request;
use App\Services\TaskService;

class TaskController extends Controller
{
    protected $taskService;

    public function __construct(TaskService $taskService)
    {
        $this->taskService = $taskService;
    }

    public function index(Request $request)
    {
        $request->validate([
            'page' => 'integer|min:1',
            'per_page' => 'integer|min:1|max:100',
        ]);

        $users = $this->taskService->all();
        return response()->json($users);
    }

    public function show(Request $request, $id)
    {
        $request->validate([
            'id' => 'required|uuid|exists:tasks,id',
        ]);

        $user = $this->taskService->show($id);
        return response()->json($user);
    }

    public function store(StoreTaskRequest $request)
    {
        $user = $this->taskService->create($request->validated());
        return response()->json($user, 201);
    }

    public function update(UpdateTaskRequest $request, $id)
    {
        $user = $this->taskService->update($request->validated(), $id);
        return response()->json($user);
    }

    public function destroy($id)
    {
        $this->taskService->destroy($id);
    }
}
