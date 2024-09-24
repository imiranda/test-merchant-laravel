<?php

namespace App\Repositories;

interface IPaymentRepository
{
    public function all(int $merchant_id);

    public function create(array $data);

    public function update(array $data, $id);

    public function delete($id);

    public function find($id);
}