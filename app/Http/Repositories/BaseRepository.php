<?php

namespace App\Http\Repositories;

use App\interfaces\Repositories\IBaseRepository;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Collection;

abstract class BaseRepository implements IBaseRepository
{
    protected Model $model;

    public function __construct(Model $model)
    {
        $this->model = $model;
    }

    public function all(): Collection
    {
        return $this->model->all();
    }

    public function find(int $id): Model
    {
        return $this->model->find($id);
    }

    public function create(array $data): Model
    {
        return $this->model->create($data);
    }

    public function update($id, array $data): Model
    {
        return $this->model->findOrFail($id)->update($data);
    }

    public function delete($id): bool
    {
        return $this->model->findOrFail($id)->delete();
    }
}
