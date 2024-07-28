<?php

namespace App\Repositories\Interfaces;

use App\Models\Task;

interface TaskRepositoryInterface
{
    public function all(): array;
    public function find($id): Task;
    public function create(array $data): Task;
    public function update(array $data, $id): Task;
    public function destroy($id): void;
}
