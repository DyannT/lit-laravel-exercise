<?php

namespace App\Repository;
use Illuminate\Database\Eloquent\Model;

class CommonRepository implements CommonRepositoryInterface
{
    protected Model $model;

    public function __construct($model)
    {
        $this->model = $model;
    }

    public function all(): \Illuminate\Database\Eloquent\Collection
    {
        return $this->model::orderByDesc('id')->get();
    }

    public function find($id){
        return $this->model::find($id);
    }

    public function first(){
        return $this->model::first();
    }

    public function create(array $attributes){
        return $this->model::create($attributes);
    }

    public function update($id, array $attributes){
        return $this->find($id)->update($attributes);
    }

    public function delete($id){
        return $this->find($id)->delete();
    }

    public function search(array $search){
        return $this->model::where($search)->orderByDesc('id')->get();
    }

    public function paginate($page, $perPage)
    {
        return $this->model::orderByDesc('id')->paginate($perPage, ['*'], 'page', $page);
    }
}
