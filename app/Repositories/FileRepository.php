<?php

namespace App\Repositories;

use App\Repositories\Interfaces\FileRepositoryInterface;
use App\Models\File;

class FileRepository implements FileRepositoryInterface
{
    public function create(array $data): File
    {
        return File::create($data);
    }

    public function delete(string $id): void
    {
        File::destroy($id);
    }

    public function find(string $id): File
    {
        return File::findOrFail($id);
    }

    public function findByTaskId(string $task_id): File
    {
        return File::where('task_id', $task_id)->get();
    }

    public function update(string $id, array $data): File
    {
        $file = File::findOrFail($id);
        $file->update($data);
        return $file;
    }

    public function deleteByTaskId(string $task_id): void
    {
        File::where('task_id', $task_id)->delete();
    }
}
