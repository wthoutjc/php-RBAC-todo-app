<?php

namespace App\Repositories\Interfaces;

interface UserRepositoryInterface
{
    public function all();
    public function find($id);
    public function create(array $data);
    public function update(array $data, $id);
    public function destroy($id);
    public function findByGoogleId(string $googleId);
}
