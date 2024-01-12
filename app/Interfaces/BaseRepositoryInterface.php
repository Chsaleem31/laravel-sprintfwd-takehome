<?php

namespace App\Interfaces;

interface BaseRepositoryInterface
{
    public function all();

    public function store(array $data);

    public function find($id);

    public function update(array $data, $id);

    public function delete($id);
}
