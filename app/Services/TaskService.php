<?php

namespace App\Services;

use App\Repositories\TaskRepository;

class TaskService
{
    protected $taskRepository;

    public function __construct(TaskRepository $taskRepository)
    {
        $this->taskRepository = $taskRepository;
    }

    public function all(string $user_id)
    {
        return $this->taskRepository->all($user_id);
    }

    public function show($id)
    {
        return $this->taskRepository->show($id);
    }

    public function create(array $data)
    {
        return $this->taskRepository->create($data);
    }

    public function update(array $data, $id)
    {
        return $this->taskRepository->update($data, $id);
    }

    public function destroy($id)
    {
        return $this->taskRepository->destroy($id);
    }
}
