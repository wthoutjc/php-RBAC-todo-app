<?php

namespace App\Repositories\Interfaces;

interface FileRepositoryInterface
{
    public function create(array $data);

    public function delete(string $id);

    public function find(string $id);

    public function findByTaskId(string $task_id);

    public function update(string $id, array $data);

    public function deleteByTaskId(string $task_id);
}
