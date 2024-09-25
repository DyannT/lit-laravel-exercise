<?php

namespace App\Repository;

interface CommonRepositoryInterface
{
    public function all();
    public function find($id);
    public function first();
    public function create(array $attributes);
    public function update($id, array $attributes);
    public function delete($id);
    public function search(array $search);
    public function paginate($page, $perPage);
}
