<?php

namespace App\Repositories\Interfaces;

interface TaskRepositoryInterface
{
    public function all(string $user_id);
    public function show(string $id);
    public function create(array $data);
    public function update(array $data, $id);
    public function destroy(string $id);
}
