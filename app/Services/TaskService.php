<?php

namespace App\Services;

use App\Repositories\Interfaces\TaskRepositoryInterface;

class TaskService
{
    protected $taskRepository;

    public function __construct(TaskRepositoryInterface $taskRepository)
    {
        $this->taskRepository = $taskRepository;
    }

    public function all()
    {
        return $this->taskRepository->all();
    }

    public function find($id)
    {
        return $this->taskRepository->find($id);
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
