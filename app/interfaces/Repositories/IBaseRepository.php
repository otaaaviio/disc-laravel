<?php

namespace App\interfaces\Repositories;

use Illuminate\Database\Eloquent\Collection;
use Illuminate\Database\Eloquent\Model;

interface IBaseRepository
{
    public function all(): Collection;

    public function find(int $id): Model;

    public function create(array $data): Model;

    public function update($id, array $data): Model;

    public function delete($id): bool;

}