<?php

namespace App\Services;

use App\Repositories\IMerchantRepository;

class MerchantService
{
    public function __construct(protected IMerchantRepository $merchantRepository) {
    }

    public function create(array $data)
    {
        return $this->merchantRepository->create($data);
    }

    public function update(array $data, $id)
    {
        return $this->merchantRepository->update($data, $id);
    }

    public function delete($id)
    {
        return $this->merchantRepository->delete($id);
    }

    public function all()
    {
        return $this->merchantRepository->all();
    }
    
    public function find($id)
    {
        return $this->merchantRepository->find($id);
    }

    public function findByNameAndOrEmail(string $name, string $email)
    {
        return $this->merchantRepository->findByNameAndOrEmail($name, $email);
    }
}