<?php

namespace App\Repositories;

use Illuminate\Database\Eloquent\Model;

class BaseRepository
{
    public function __construct(protected Model $model)
    {}

    public function all(array $columns = ['*'], array $with = [])
    {
        return !empty($with) ? $this->model->with($with)->get($columns) : $this->model->all($columns);
    }

    public function findWhere(array $conditions = [], array $with = [], array $columns = ['*']) {
        $query = $this->model->with($with);
        
        foreach ($conditions as $field => $value) {
            if (is_array($value)) {
                // ['view' => ['>', 100]].
                $query->where($field, $value[0], $value[1]);
            } else {
                // ['view', 100].
                $query->where($field, $value);
            }
        }
        return $query->first($columns);
    }


    public function find(int $id)
    {
        return $this->model->findOrFail($id);
    }
    public function findBy(string $column, mixed $value)
    {
        return $this->model->where($column, $value)->first();
    }

    public function create(array $data)
    {
       return $this->model->create($data);
    }
    public function update(int $id, array $data)
    {
       return $this->model->find($id)->update($data);
    }

    public function delete(int $id)
    {
       return $this->model->find($id)->delete();
    }
}