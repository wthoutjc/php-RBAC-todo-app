<?php

namespace App\Repositories;

use App\Models\Task;
use App\Repositories\Interfaces\TaskRepositoryInterface;

class TaskRepository implements TaskRepositoryInterface
{
    public function all()
    {
        return Task::orderby('created_at', 'desc')->paginate();
    }

    public function show($id)
    {
        return Task::show($id);
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
