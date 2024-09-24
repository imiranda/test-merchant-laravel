<?php

namespace App\Repositories;

use App\Models\Merchant;

class MerchantRepository implements IMerchantRepository
{
    public function all()
    {
        return Merchant::all();
    }

    public function create(array $data)
    {
        return Merchant::create($data);
    }

    public function update(array $data, $id)
    {
        $merchant = Merchant::find($id);
        $merchant->update($data);
        return $merchant;
    }

    public function delete($id)
    {
        $merchant = Merchant::find($id);
        return $merchant->delete();
    }

    public function find($id)
    {
        return Merchant::find($id);
    }

    public function findByNameAndOrEmail(string $name, string $email)
    {
        return Merchant::where('name', 'like', "%{$name}%")
                       ->orWhere('email', 'like', "%{$email}%")
                       ->first();
    }
}
