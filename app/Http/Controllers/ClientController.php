<?php

namespace App\Http\Controllers;

use App\Models\Client;
use App\Http\Requests\StoreClientRequest;
use App\Http\Requests\UpdateClientRequest;
use Illuminate\Http\Request;

/**
 * @OA\Tag(
 *     name="Client",
 *     description="API Endpoints para gestión de Clientes"
 * )
 */
class ClientController extends Controller
{
    /**
     * @OA\Get(
     *     path="/api/client",
     *     tags={"Client"},
     *     summary="Obtener la lista de Clientes",
     *     security={{ "sanctum": {} }},
     *     description="Obtiene una lista de todos los clientes disponibles",
     *     operationId="getClientList",
     *     @OA\Response(
     *         response=200,
     *         description="Lista obtenida correctamente",
     *         @OA\JsonContent(
     *             @OA\Property(property="clients", type="array", @OA\Items(type="object"))
     *         )
     *     ),
     *     @OA\Response(
     *         response=500,
     *         description="Error interno del servidor"
     *     )
     * )
     */
    public function index()
    {
        return response()->json([
            'clients' => Client::all(),
        ]);
    }

    /**
     * @OA\Post(
     *     path="/api/client",
     *     tags={"Client"},
     *     summary="Crear un nuevo Cliente",
     *     security={{ "sanctum": {} }},
     *     description="Crea un nuevo cliente con los datos proporcionados",
     *     operationId="createClient",
     *     @OA\RequestBody(
     *         required=true,
     *         @OA\JsonContent(
     *             required={"code", "name", "lastname", "phone"},
     *             @OA\Property(property="code", type="string", example="CLI-001"),
     *             @OA\Property(property="name", type="string", example="Juan"),
     *             @OA\Property(property="lastname", type="string", example="Pérez"),
     *             @OA\Property(property="phone", type="string", example="987654321")
     *         )
     *     ),
     *     @OA\Response(
     *         response=200,
     *         description="Cliente creado correctamente",
     *         @OA\JsonContent(
     *             @OA\Property(property="client", type="object")
     *         )
     *     ),
     *     @OA\Response(
     *         response=422,
     *         description="Datos de entrada inválidos"
     *     ),
     *     @OA\Response(
     *         response=500,
     *         description="Error interno del servidor"
     *     )
     * )
     */
    public function store(Request $request)
    {
        $request->validate([
            'code' => 'required|unique:clients,code',
            'name' => 'required|string|max:255',
            'lastname' => 'required|string|max:255',
            'phone' => 'required|string|max:20',
        ]);

        $client = Client::create($request->all());

        return response()->json([
            'client' => $client,
        ], 200);
    }

    /**
     * @OA\Get(
     *     path="/api/client/{id}",
     *     tags={"Client"},
     *     security={{ "sanctum": {} }},
     *     summary="Obtener un Cliente por ID",
     *     description="Devuelve un cliente específico según su ID",
     *     operationId="getClient",
     *     @OA\Parameter(
     *         name="id",
     *         in="path",
     *         required=true,
     *         description="ID del Cliente",
     *         @OA\Schema(type="integer", example=1)
     *     ),
     *     @OA\Response(
     *         response=200,
     *         description="Cliente encontrado",
     *         @OA\JsonContent(
     *             @OA\Property(property="client", type="object")
     *         )
     *     ),
     *     @OA\Response(
     *         response=404,
     *         description="Cliente no encontrado"
     *     ),
     *     @OA\Response(
     *         response=500,
     *         description="Error interno del servidor"
     *     )
     * )
     */
    public function show($id)
    {
        $client = Client::findOrFail($id);
        return response()->json([
            'client' => $client,
        ], 200);
    }

    /**
     * @OA\Put(
     *     path="/api/client/{id}",
     *     tags={"Client"},
     *     summary="Actualizar un Cliente existente",
     *     security={{ "sanctum": {} }},
     *     description="Actualiza los datos de un cliente existente con la información proporcionada",
     *     operationId="updateClient",
     *     @OA\Parameter(
     *         name="id",
     *         in="path",
     *         required=true,
     *         description="ID del Cliente a actualizar",
     *         @OA\Schema(type="integer", example=1)
     *     ),
     *     @OA\RequestBody(
     *         required=true,
     *         @OA\JsonContent(
     *             required={"code", "name", "lastname", "phone"},
     *             @OA\Property(property="code", type="string", example="CLI-001"),
     *             @OA\Property(property="name", type="string", example="Juan"),
     *             @OA\Property(property="lastname", type="string", example="Pérez"),
     *             @OA\Property(property="phone", type="string", example="987654321")
     *         )
     *     ),
     *     @OA\Response(
     *         response=200,
     *         description="Cliente actualizado correctamente",
     *         @OA\JsonContent(
     *             @OA\Property(property="client", type="object")
     *         )
     *     ),
     *     @OA\Response(
     *         response=422,
     *         description="Datos de entrada inválidos"
     *     ),
     *     @OA\Response(
     *         response=404,
     *         description="Cliente no encontrado"
     *     ),
     *     @OA\Response(
     *         response=500,
     *         description="Error interno del servidor"
     *     )
     * )
     */
    public function update(Request $request, $id)
    {
        $request->validate([
            'code' => 'required|unique:clients,code,' . $id,
            'name' => 'required|string|max:255',
            'lastname' => 'required|string|max:255',
            'phone' => 'required|string|max:20',
        ]);

        $client = Client::findOrFail($id);
        $client->update($request->all());

        return response()->json([
            'client' => $client,
        ], 200);
    }

    /**
     * @OA\Delete(
     *     path="/api/client/{id}",
     *     tags={"Client"},
     *     summary="Eliminar un Cliente",
     *     security={{ "sanctum": {} }},
     *     description="Elimina un cliente según su ID",
     *     operationId="deleteClient",
     *     @OA\Parameter(
     *         name="id",
     *         in="path",
     *         required=true,
     *         description="ID del Cliente",
     *         @OA\Schema(type="integer", example=1)
     *     ),
     *     @OA\Response(
     *         response=200,
     *         description="Cliente eliminado correctamente",
     *         @OA\JsonContent(
     *             @OA\Property(property="message", type="string", example="Cliente eliminado")
     *         )
     *     ),
     *     @OA\Response(
     *         response=404,
     *         description="Cliente no encontrado"
     *     ),
     *     @OA\Response(
     *         response=500,
     *         description="Error interno del servidor"
     *     )
     * )
     */
    public function destroy($id)
    {
        $client = Client::findOrFail($id);
        $client->delete();

        return response()->json([
            'message' => 'Cliente eliminado',
        ], 200);
    }
}
