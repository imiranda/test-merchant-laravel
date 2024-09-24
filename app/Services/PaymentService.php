<?php

namespace App\Services;

use App\Repositories\IPaymentRepository;

class PaymentService
{
    public function __construct(protected IPaymentRepository $paymentRepository) {
    }

    public function create(array $data)
    {
        return $this->paymentRepository->create($data);
    }

    public function update(array $data, $id)
    {
        return $this->paymentRepository->update($data, $id);
    }

    public function delete($id)
    {
        return $this->paymentRepository->delete($id);
    }

    public function all($id)
    {
        return $this->paymentRepository->all($id);
    }
    
    public function find($id)
    {
        return $this->paymentRepository->find($id);
    }
}