<?php

namespace App\Http\Controllers;

use App\Models\Project;
use App\Http\Requests\StoreProjectRequest;
use App\Http\Requests\UpdateProjectRequest;
use Illuminate\Http\Request;
use OpenApi\Annotations as OA;

/**
 * @OA\Tag(
 *     name="Project",
 *     description="API para proyectos"
 * )
 */
class ProjectController extends Controller
{
    /**
     * @OA\Get(
     *     path="/api/project",
     *     tags={"Project"},
     *     summary="Obtener la lista de proyectos",
     *     description="Obtiene una lista de proyectos filtrados",
     *     operationId="index",
     *     @OA\Response(
     *         response=200,
     *         description="Lista obtenida correctamente",
     *         @OA\JsonContent(
     *             @OA\Property(property="success", type="boolean", example=true),
     *             @OA\Property(property="message", type="string", example="Lista de posts obtenida correctamente"),
     *             @OA\Property(property="data", type="array", @OA\Items(type="object"))
     *         )
     *     ),
     *     @OA\Response(
     *         response=500,
     *         description="Error interno del servidor",
     *         @OA\JsonContent(
     *             @OA\Property(property="success", type="boolean", example=false),
     *             @OA\Property(property="message", type="string", example="Error al obtener la lista de posts: Detalles del error")
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
     * @OA\Post(
     *     path="/api/project",
     *     tags={"Project"},
     *     summary="Crear un nuevo proyecto,
     *     description="Crea un nuevo proyecto con los datos proporcionados",
     *     operationId="store",
     *     @OA\RequestBody(
     *         required=true,
     *         @OA\JsonContent(
     *             required={
     *                 'name', 'description', 'status'
     *             },
     *             @OA\Property(property="name", type="string", example="Project 11"),
     *             @OA\Property(property="description", type="string", example=""),
     *             @OA\Property(property="status", type="string", example="active"),
     *         )
     *     ),
     *     @OA\Response(
     *         response=201,
     *         description="Proyecto creado correctamente",
     *         @OA\JsonContent(
     *             @OA\Property(property="success", type="boolean", example=true),
     *             @OA\Property(property="message", type="string", example="Post creado correctamente"),
     *             @OA\Property(property="data", type="object")
     *         )
     *     ),
     *     @OA\Response(
     *         response=422,
     *         description="Datos de entrada inválidos",
     *         @OA\JsonContent(
     *             @OA\Property(property="success", type="boolean", example=false),
     *             @OA\Property(property="message", type="string", example="Los datos proporcionados no son válidos."),
     *             @OA\Property(property="errors", type="object")
     *         )
     *     ),
     *     @OA\Response(
     *         response=500,
     *         description="Error interno del servidor",
     *         @OA\JsonContent(
     *             @OA\Property(property="success", type="boolean", example=false),
     *             @OA\Property(property="message", type="string", example="Error al crear un post: Detalles del error")
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
    
    public function show(Project $project)
    {
        $project->load('tasks');
        return response()->json([
            'message' => 'Proyecto encontrado',
            'data' => $project,
        ], 200);
    }
    
    public function update(UpdateProjectRequest $request, Project $project)
    {
        $project->update($request->validated());

        return response()->json([
            'message' => 'Proyecto actualizado',
            'data' => $project,
        ], 200);
    }
    
    public function destroy(Project $project)
    {
        $project->delete();

        return response()->json([
            'message' => 'Proyecto eliminado',
        ], 200);
    }
}
