<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

/**
 * @OA\Schema(
 *      schema="Payment",
 *      @OA\Property(property="id", type="integer", format="int64", example=1),
 *      @OA\Property(property="merchant_id", type="integer", format="int64", example=1),
 *      @OA\Property(property="amount", type="number", format="decimal", example=19.00),
 *      @OA\Property(property="method", type="string", enum={"tarjeta_credito", "pix", "transferencia"}, example="tarjeta_credito"),
 *      @OA\Property(property="fee", type="number", format="decimal", example=2.00),
 *      @OA\Property(property="status", type="string", enum={"pendiente", "procesado", "fallido"}, example="pendiente"),
 *  )
 */
class Payment extends Model
{
    use HasFactory;

    protected $fillable = ['merchant_id', 'amount', 'method', 'fee', 'status'];

    public function merchant()
    {
        return $this->belongsTo(Merchant::class);
    }
}