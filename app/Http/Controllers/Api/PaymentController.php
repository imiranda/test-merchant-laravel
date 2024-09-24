<?php

namespace App\Http\Controllers\api;

use App\Http\Controllers\Controller;
use App\Services\PaymentService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

use Tymon\JWTAuth\Facades\JWTAuth;

class PaymentController extends Controller
{
    public $merchant = null;

    public function __construct(protected PaymentService $paymentService) {
    }

    /**
     * @OA\Get(
     *     path="/api/payments",
     *     summary="Obtiene todos los Pagos",
     *     tags={"Payments"},
     *     security={{ "bearerAuth": {} }},
     *     @OA\Response(
     *         response=200,
     *         description="Lista de Pagos",
     *         @OA\JsonContent(
     *             type="array",
     *             @OA\Items(ref="#/components/schemas/Payment")
     *         )
     *     )
     * )
     */
    public function index()
    {
        $merchant = JWTAuth::user();
        
        $payments = $this->paymentService->all($merchant->id);
        return response()->json($payments);
    }

    /**
     * @OA\Post(
     *     path="/api/payments",
     *     summary="Crear un nuevo Pago",
     *     tags={"Payments"},
     *     security={{ "bearerAuth": {} }},
     *     @OA\RequestBody(
     *         required=true,
     *         @OA\JsonContent(ref="#/components/schemas/Payment")
     *     ),
     *     @OA\Response(
     *         response=201,
     *         description="Pago creado exitosamente",
     *         @OA\JsonContent(ref="#/components/schemas/Payment")
     *     ),
     *     @OA\Response(
     *         response=422,
     *         description="Datos no validos"
     *     )
     * )
     */
    public function store(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'merchant_id' => 'required|integer',
            'amount' => 'required|integer',
            'method' => 'required|string',
            'fee' => 'required|integer',
            'status' => 'required|string',
        ]);
    
        if ($validator->fails()) {
            return response()->json(['errors' => $validator->errors()], 422);
        }

        $payment = $this->paymentService->create([
            'merchant_id' => $request->merchant_id,
            'amount' => $request->amount,
            'method' => $request->method,
            'fee' => $request->fee,
            'status' => $request->status,
        ]);
        return response()->json($payment, 201);
    }

    /**
     * @OA\Get(
     *     path="/api/payments/{id}",
     *     summary="Obtener un Pago por ID",
     *     tags={"Payments"},
     *     security={{ "bearerAuth": {} }},
     *     @OA\Parameter(
     *         name="id",
     *         in="path",
     *         description="ID del Pago",
     *         required=true,
     *         @OA\Schema(type="integer")
     *     ),
     *     @OA\Response(
     *         response=200,
     *         description="Pago encontrado",
     *         @OA\JsonContent(ref="#/components/schemas/Payment")
     *     ),
     *     @OA\Response(
     *         response=404,
     *         description="Pago no encontrado"
     *     )
     * )
     */
    public function show($id)
    {
        $merchant = JWTAuth::user();

        $payment = $this->paymentService->find($id);

        if (empty($payment)) {
            return response()->json(['message' => 'Pago no encontrado'], 404);
        }

        if ($payment->merchant_id != $merchant->id) {
            return response()->json(['message' => 'Pago no corresponde a Comercio'], 404);
        }

        return response()->json($payment);
    }

    /**
     * @OA\Put(
     *     path="/api/payments/{id}",
     *     summary="Actualizar un Pago",
     *     tags={"Payments"},
     *     security={{ "bearerAuth": {} }},
     *     @OA\Parameter(
     *         name="id",
     *         in="path",
     *         description="ID del Pago",
     *         required=true,
     *         @OA\Schema(type="integer")
     *     ),
     *     @OA\RequestBody(
     *         required=true,
     *         @OA\JsonContent(ref="#/components/schemas/Payment")   
     *     ),
     *     @OA\Response(
     *         response=200,
     *         description="Pago actualizado exitosamente",
     *         @OA\JsonContent(ref="#/components/schemas/Payment")
     *     )
     * )
     */
    public function update(Request $request, $id)
    {
        $validator = Validator::make($request->all(), [
            'merchant_id' => 'required|integer',
            'amount' => 'required|number',
            'method' => 'required|string',
            'fee' => 'required|number',
            'status' => 'required|string',
        ]);
    
        if ($validator->fails()) {
            return response()->json(['errors' => $validator->errors()], 422);
        }

        $paymentObj = $this->paymentService->find($id);

        if (empty($paymentObj)) {
            return response()->json(['message' => 'Pago no encontrado'], 404);
        }

        $payment = $this->paymentService->update([
            'merchant_id' => $request->merchant_id,
            'amount' => $request->amount,
            'method' => $request->method,
            'fee' => $request->fee,
            'status' => $request->status,
        ], $id);
        return response()->json($payment);
    }

    /**
     * @OA\Delete(
     *     path="/api/payments/{id}",
     *     summary="Eliminar un Pago",
     *     tags={"Payments"},
     *     security={{ "bearerAuth": {} }},
     *     @OA\Parameter(
     *         name="id",
     *         in="path",
     *         description="ID del Pago",
     *         required=true,
     *         @OA\Schema(type="integer")
     *     ),
     *     @OA\Response(
     *         response=204,   
     *         description="Pago eliminado exitosamente"
     *     )
     * )
     */
    public function destroy($id)
    {
        $paymentObj = $this->paymentService->find($id);

        if (empty($paymentObj)) {
            return response()->json(['message' => 'Pago no encontrado'], 404);
        }

        $payment = $this->paymentService->delete($id);
        return response()->json($payment, 204);
    }
}
