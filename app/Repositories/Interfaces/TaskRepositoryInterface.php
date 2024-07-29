<?php

namespace App\Repositories\Interfaces;

interface TaskRepositoryInterface
{
    public function all();
    public function show($id);
    public function create(array $data);
    public function update(array $data, $id);
    public function destroy($id);
}
