<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Services\MerchantService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use Tymon\JWTAuth\Facades\JWTAuth;

class MerchantController extends Controller
{
    public function __construct(protected MerchantService $merchantService) {
    }

    /**
     * @OA\Post(
     *     path="/api/merchants/fake-login",
     *     summary="Login Comercio",
     *     tags={"Merchants"},
     *     @OA\RequestBody(
     *         required=true,
     *         @OA\JsonContent(
     *              type="object",
     *              @OA\Property(property="email", type="string"),
     *         )
     *     ),
     *     @OA\Response(
     *         response=201,
     *         description="Comercio creado exitosamente",
     *         @OA\JsonContent(
     *              type="object",
     *              @OA\Property(property="token", type="string"),
     *         )
     *     ),
     *     @OA\Response(
     *         response=422,
     *         description="Datos no validos"
     *     )
     * )
     */
    public function fakeLogin(Request $request)
    {
        // Buscar el comercio por email
        $merchant = $this->merchantService->findByNameAndOrEmail('', $request->email);

        if (!$merchant) {
            return response()->json(['message' => 'Usuario no encontrado'], 404);
        }

        // Generar el token JWT
        $token = JWTAuth::fromUser($merchant);

        return response()->json(['token' => $token]);
    }

    /**
     * @OA\Get(
     *     path="/api/merchants",
     *     summary="Obtiene todos los Comercios",
     *     tags={"Merchants"},
     *     security={{ "bearerAuth": {} }},
     *     @OA\Response(
     *         response=200,
     *         description="Lista de Comercios",
     *         @OA\JsonContent(
     *             type="array",
     *             @OA\Items(ref="#/components/schemas/Merchant")
     *         )
     *     )
     * )
     */
    public function index()
    {
        $merchants = $this->merchantService->all();
        return response()->json($merchants);
    }

    /**
     * @OA\Post(
     *     path="/api/merchants",
     *     summary="Crear un nuevo Comercio",
     *     tags={"Merchants"},
     *     security={{ "bearerAuth": {} }},
     *     @OA\RequestBody(
     *         required=true,
     *         @OA\JsonContent(ref="#/components/schemas/Merchant")
     *     ),
     *     @OA\Response(
     *         response=201,
     *         description="Comercio creado exitosamente",
     *         @OA\JsonContent(ref="#/components/schemas/Merchant")
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
            'name' => 'required|string',
            'email' => 'required|email',
        ]);
    
        if ($validator->fails()) {
            return response()->json(['errors' => $validator->errors()], 422);
        }

        $merchantObj = $this->merchantService->findByNameAndOrEmail($request->name, $request->email);

        if (!empty($merchantObj)) {
            return response()->json(['message' => 'Comercio ya existe'], 400);
        }

        $merchant = $this->merchantService->create([
            'name' => $request->name,
            'email' => $request->email,
            'password' => '',
        ]);

        return response()->json($merchant, 201);
    }

    /**
     * @OA\Get(
     *     path="/api/merchants/{id}",
     *     summary="Obtener un Comercio por ID",
     *     tags={"Merchants"},
     *     security={{ "bearerAuth": {} }},
     *     @OA\Parameter(
     *         name="id",
     *         in="path",
     *         description="ID del Comercio",
     *         required=true,
     *         @OA\Schema(type="integer")
     *     ),
     *     @OA\Response(
     *         response=200,
     *         description="Comercio encontrado",
     *         @OA\JsonContent(ref="#/components/schemas/Merchant")
     *     ),
     *     @OA\Response(
     *         response=404,
     *         description="Comercio no encontrado"
     *     )
     * )
     */
    public function show($id)
    {
        $merchant = $this->merchantService->find($id);

        if (empty($merchant)) {
            return response()->json(['message' => 'Comercio no encontrado'], 404);
        }

        return response()->json($merchant);
    }

    /**
     * @OA\Put(
     *     path="/api/merchants/{id}",
     *     summary="Actualizar un Comercio",
     *     tags={"Merchants"},
     *     security={{ "bearerAuth": {} }},
     *     @OA\Parameter(
     *         name="id",
     *         in="path",
     *         description="ID del Comercio",
     *         required=true,
     *         @OA\Schema(type="integer")
     *     ),
     *     @OA\RequestBody(
     *         required=true,
     *         @OA\JsonContent(ref="#/components/schemas/Merchant")   
     *     ),
     *     @OA\Response(
     *         response=200,
     *         description="Comercio actualizado exitosamente",
     *         @OA\JsonContent(ref="#/components/schemas/Merchant")
     *     ),
     *     @OA\Response(
     *         response=422,
     *         description="Datos no validos"
     *     ),
     *     @OA\Response(
     *         response=404,
     *         description="Comercio no encontrado"
     *     )
     * )
     */
    public function update(Request $request, $id)
    {
        $validator = Validator::make($request->all(), [
            'name' => 'required|string',
            'email' => 'required|email',
        ]);
    
        if ($validator->fails()) {
            return response()->json(['errors' => $validator->errors()], 422);
        }

        $merchantObj = $this->merchantService->find($id);

        if (empty($merchantObj)) {
            return response()->json(['message' => 'Comercio no encontrado'], 404);
        }

        $merchant = $this->merchantService->update([
            'name' => $request->name,
            'email' => $request->email,
            'password' => '',
        ], $id);

        return response()->json($merchant);
    }

    /**
     * @OA\Delete(
     *     path="/api/merchants/{id}",
     *     summary="Eliminar un Comercio",
     *     tags={"Merchants"},
     *     security={{ "bearerAuth": {} }},
     *     @OA\Parameter(
     *         name="id",
     *         in="path",
     *         description="ID del Comercio",
     *         required=true,
     *         @OA\Schema(type="integer")
     *     ),
     *     @OA\Response(
     *         response=204,   
     *         description="Comercio eliminado exitosamente"
     *     ),
     *     @OA\Response(
     *         response=404,
     *         description="Comercio no encontrado"
     *     )
     * )
     */
    public function destroy($id)
    {
        $merchantObj = $this->merchantService->find($id);

        if (empty($merchantObj)) {
            return response()->json(['message' => 'Comercio no encontrado'], 404);
        }

        $merchant = $this->merchantService->delete($id);
        return response()->json($merchant, 204);
    }
}
