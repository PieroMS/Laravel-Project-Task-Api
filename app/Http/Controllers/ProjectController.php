<?php

namespace App\Http\Controllers;

use App\Models\Project;
use App\Http\Requests\StoreProjectRequest;
use App\Http\Requests\UpdateProjectRequest;
use Illuminate\Http\Request;

/**
* @OA\Info(
*             title="API Project", 
*             version="1.0",
*             description="Listado de URIs de la API Project"
* )
*
* @OA\Server(url="http://127.0.0.1:8000")
*/
class ProjectController extends Controller
{
    /**
     * Listado filtrado de proyectos
     *
     * @OA\Get(
     *     path="/api/project",
     *     tags={"Project"},
     *     summary="Obtener lista de proyectos filtrados",
     *     @OA\Parameter(
     *         name="Project 1",
     *         in="query",
     *         description="Nombre del proyecto",
     *         required=false,
     *         @OA\Schema(type="string")
     *     ),
     *     @OA\Parameter(
     *         name="status",
     *         in="query",
     *         description="Estado del proyecto",
     *         required=false,
     *         @OA\Schema(type="string")
     *     ),
     *     @OA\Parameter(
     *         name="from",
     *         in="query",
     *         description="Fecha de inicio del filtro (YYYY-MM-DD)",
     *         required=false,
     *         @OA\Schema(type="string", format="date")
     *     ),
     *     @OA\Parameter(
     *         name="to",
     *         in="query",
     *         description="Fecha de fin del filtro (YYYY-MM-DD)",
     *         required=false,
     *         @OA\Schema(type="string", format="date")
     *     ),
     *     @OA\Response(
     *         response=200,
     *         description="Lista de proyectos",
     *         @OA\JsonContent(
     *             type="array",
     *             @OA\Items(
     *                 @OA\Property(property="id", type="integer", example=1),
     *                 @OA\Property(property="name", type="string", example="Proyecto 1"),
     *                 @OA\Property(property="status", type="string", example="active"),
     *                 @OA\Property(property="created_at", type="string", format="date-time", example="2023-02-23T00:09:16.000000Z"),
     *                 @OA\Property(property="updated_at", type="string", format="date-time", example="2023-02-23T12:33:45.000000Z")
     *             )
     *         )
     *     )
     * )
     */
    public function index(Request $request)
    {
        $query = Project::query();

        if($request->filled('name')) {
            $query->where('name', 'like', '%' . $request->name . '%');
        }

        if($request->filled('status')) {
            $query->where('status', 'like', '%' . $request->status . '%');
        }

        if($request->filled('from') && $request->filled('to')) {
            $query->whereBetween('created_at', [$request->from, $request->to]);
        }

        return $query->get();
    }
    
    /**
     * Crear un nuevo proyecto
     *
     * @OA\Post(
     *     path="/api/project",
     *     tags={"Project"},
     *     summary="Crea un nuevo proyecto",
     *     @OA\RequestBody(
     *         required=true,
     *         @OA\JsonContent(
     *             required={"name", "status"},
     *             @OA\Property(property="name", type="string", example="Nuevo Proyecto"),
     *             @OA\Property(property="status", type="string", example="active")
     *         )
     *     ),
     *     @OA\Response(
     *         response=201,
     *         description="Proyecto creado exitosamente",
     *         @OA\JsonContent(
     *             @OA\Property(property="message", type="string", example="Proyecto creado"),
     *             @OA\Property(
     *                 property="data",
     *                 type="object",
     *                 @OA\Property(property="id", type="integer", example=1),
     *                 @OA\Property(property="name", type="string", example="Nuevo Proyecto"),
     *                 @OA\Property(property="status", type="string", example="activo"),
     *                 @OA\Property(property="created_at", type="string", format="date-time", example="2025-05-09T10:00:00.000000Z"),
     *                 @OA\Property(property="updated_at", type="string", format="date-time", example="2025-05-09T10:00:00.000000Z")
     *             )
     *         )
     *     ),
     *     @OA\Response(
     *         response=422,
     *         description="Error de validación",
     *         @OA\JsonContent(
     *             @OA\Property(property="message", type="string", example="Los datos proporcionados no son válidos."),
     *             @OA\Property(property="errors", type="object")
     *         )
     *     )
     * )
     */
    public function store(StoreProjectRequest $request)
    {
        $project = Project::create($request->validated());

        return response()->json([
            'message' => 'Proyecto creado',
            'data' => $project,
        ], 201);
    }
    
    /**
     * Obtener un proyecto por ID
     *
     * @OA\Get(
     *     path="/api/project/{id}",
     *     tags={"Project"},
     *     summary="Mostrar información de un proyecto",
     *     @OA\Parameter(
     *         name="id",
     *         in="path",
     *         required=true,
     *         description="ID del proyecto",
     *         @OA\Schema(type="integer")
     *     ),
     *     @OA\Response(
     *         response=200,
     *         description="Proyecto encontrado",
     *         @OA\JsonContent(
     *             @OA\Property(property="message", type="string", example="Proyecto encontrado"),
     *             @OA\Property(
     *                 property="data",
     *                 type="object",
     *                 @OA\Property(property="id", type="integer", example=1),
     *                 @OA\Property(property="name", type="string", example="Proyecto X"),
     *                 @OA\Property(property="status", type="string", example="active"),
     *                 @OA\Property(property="created_at", type="string", format="date-time", example="2025-05-09T10:00:00.000000Z"),
     *                 @OA\Property(property="updated_at", type="string", format="date-time", example="2025-05-09T10:00:00.000000Z"),
     *                 @OA\Property(
     *                     property="tasks",
     *                     type="array",
     *                     @OA\Items(
     *                         type="object",
     *                         @OA\Property(property="id", type="integer", example=10),
     *                         @OA\Property(property="title", type="string", example="Tarea 1")
     *                     )
     *                 )
     *             )
     *         )
     *     ),
     *     @OA\Response(
     *         response=404,
     *         description="Proyecto no encontrado"
     *     )
     * )
     */
    public function show(Project $project)
    {
        $project->load('tasks');
        return response()->json([
            'message' => 'Proyecto encontrado',
            'data' => $project,
        ], 200);
    }
    
    /**
     * Actualizar un proyecto
     *
     * @OA\Put(
     *     path="/api/project/{id}",
     *     tags={"Project"},
     *     summary="Actualizar los datos de un proyecto",
     *     @OA\Parameter(
     *         name="id",
     *         in="path",
     *         required=true,
     *         description="ID del proyecto",
     *         @OA\Schema(type="integer")
     *     ),
     *     @OA\RequestBody(
     *         required=true,
     *         @OA\JsonContent(
     *             required={"name", "status"},
     *             @OA\Property(property="name", type="string", example="Proyecto Actualizado"),
     *             @OA\Property(property="status", type="string", example="inactive")
     *         )
     *     ),
     *     @OA\Response(
     *         response=200,
     *         description="Proyecto actualizado",
     *         @OA\JsonContent(
     *             @OA\Property(property="message", type="string", example="Proyecto actualizado"),
     *             @OA\Property(
     *                 property="data",
     *                 type="object",
     *                 @OA\Property(property="id", type="integer", example=1),
     *                 @OA\Property(property="name", type="string", example="Proyecto Actualizado"),
     *                 @OA\Property(property="status", type="string", example="inactive"),
     *                 @OA\Property(property="created_at", type="string", format="date-time", example="2025-05-09T10:00:00.000000Z"),
     *                 @OA\Property(property="updated_at", type="string", format="date-time", example="2025-05-09T12:00:00.000000Z")
     *             )
     *         )
     *     ),
     *     @OA\Response(
     *         response=422,
     *         description="Error de validación"
     *     ),
     *     @OA\Response(
     *         response=404,
     *         description="Proyecto no encontrado"
     *     )
     * )
     */
    public function update(UpdateProjectRequest $request, Project $project)
    {
        $project->update($request->validated());

        return response()->json([
            'message' => 'Proyecto actualizado',
            'data' => $project,
        ], 200);
    }
    
    /**
     * Eliminar un proyecto
     *
     * @OA\Delete(
     *     path="/api/project/{id}",
     *     tags={"Project"},
     *     summary="Eliminar un proyecto existente",
     *     @OA\Parameter(
     *         name="id",
     *         in="path",
     *         required=true,
     *         description="ID del proyecto",
     *         @OA\Schema(type="integer")
     *     ),
     *     @OA\Response(
     *         response=200,
     *         description="Proyecto eliminado",
     *         @OA\JsonContent(
     *             @OA\Property(property="message", type="string", example="Proyecto eliminado")
     *         )
     *     ),
     *     @OA\Response(
     *         response=404,
     *         description="Proyecto no encontrado"
     *     )
     * )
     */
    public function destroy(Project $project)
    {
        $project->delete();

        return response()->json([
            'message' => 'Proyecto eliminado',
        ], 200);
    }
}
