<?php

namespace App\Repositories;

use App\Models\Task;
use App\Repositories\Interfaces\TaskRepositoryInterface;

class TaskRepository implements TaskRepositoryInterface
{
    public function all(string $user_id)
    {
        return Task::where('user_id', $user_id)
            ->orderby('created_at', 'desc')
            ->paginate();
    }

    public function show($id)
    {
        return Task::find($id);
    }

    public function create(array $data)
    {
        return Task::create($data);
    }

    public function update(array $data, $id)
    {
        return Task::find($id)->update($data);
    }

    public function destroy($id)
    {
        return Task::destroy($id);
    }
}
