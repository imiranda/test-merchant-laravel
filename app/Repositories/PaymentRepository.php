<?php

namespace App\Repositories;

use App\Models\Payment;

class PaymentRepository implements IPaymentRepository
{
    public function all(int $merchant_id)
    {
        return Payment::where('merchant_id', $merchant_id)->get();
    }

    public function create(array $data)
    {
        return Payment::create($data);
    }

    public function update(array $data, $id)
    {
        $payment = Payment::find($id);
        $payment->update($data);
        return $payment;
    }

    public function delete($id)
    {
        $payment = Payment::find($id);
        return $payment->delete();
    }

    public function find($id)
    {
        return Payment::find($id);
    }
}
