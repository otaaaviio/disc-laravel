<?php

namespace App\Http\Repositories;

use App\interfaces\Repositories\IBaseRepository;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Database\Eloquent\Model;
use Throwable;

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
        return $this->model->findOrFail($id);
    }

    public function create(array $data): Model
    {
        return $this->model->create($data);
    }

    /**
     * @throws Throwable
     */
    public function update(Model $model, array $data): Model
    {
        $model->update($data);

        return $model;
    }

    public function delete($id): bool
    {
        return $this->model->findOrFail($id)->delete();
    }
}
