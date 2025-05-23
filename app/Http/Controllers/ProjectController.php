<?php

namespace App\Http\Controllers;

use App\Models\Project;
use App\Http\Requests\StoreProjectRequest;
use App\Http\Requests\UpdateProjectRequest;

/**
 * @OA\Tag(
 *     name="Project",
 *     description="API Endpoints para gestión de Proyectos"
 * )
 */
class ProjectController extends Controller
{
    /**
     * @OA\Get(
     *     path="/api/project",
     *     tags={"Project"},
     *     summary="Obtener la lista de Project",
     *     description="Obtiene una lista de todos los proyectos disponibles",
     *     operationId="getProject",
     *     @OA\Response(
     *         response=200,
     *         description="Lista obtenida correctamente",
     *         @OA\JsonContent(
     *             @OA\Property(property="success", type="boolean", example=true),
     *             @OA\Property(property="message", type="string", example="Lista obtenida correctamente"),
     *             @OA\Property(property="data", type="array", @OA\Items(type="object"))
     *         )
     *     ),
     *     @OA\Response(
     *         response=500,
     *         description="Error interno del servidor",
     *         @OA\JsonContent(
     *             @OA\Property(property="success", type="boolean", example=false),
     *             @OA\Property(property="message", type="string", example="Error al obtener la lista: Detalles del error")
     *         )
     *     )
     * )
     */
    public function index()
    {
        $data = Project::with('tasks')->get();
        return response()->json([
            'message' => 'Lista de proyectos',
            'data' => $data,
        ], 200);
    }
    
    /**
     * @OA\Post(
     *     path="/api/project",
     *     tags={"Project"},
     *     summary="Crear un nuevo Project",
     *     description="Crea un nuevo proyecto con los datos proporcionados",
     *     operationId="createProject",
     *     @OA\RequestBody(
     *         required=true,
     *         @OA\JsonContent(
     *             required={
     *                 "name",
     *                 "status"
     *             },
     *             @OA\Property(property="name", type="string", example="Proyecto 1"),
     *             @OA\Property(property="description", type="string", nullable=true, example="Esto es opcional"),
     *             @OA\Property(property="status", type="string", example="active")
     *         )
     *     ),
     *     @OA\Response(
     *         response=201,
     *         description="Dato creada correctamente",
     *         @OA\JsonContent(
     *             @OA\Property(property="success", type="boolean", example=true),
     *             @OA\Property(property="message", type="string", example="Dato creado correctamente"),
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
     *             @OA\Property(property="message", type="string", example="Error al crear: Detalles del error")
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
     * @OA\Get(
     *     path="/api/project/{project}",
     *     tags={"Project"},
     *     summary="Buscar un Project por id",
     *     description="Devuelve un proyecto en especifico.",
     *     operationId="showProject",
     *     @OA\Parameter(
     *         name="project",
     *         in="path",
     *         required=true,
     *         description="ID del Project a buscar",
     *         @OA\Schema(type="integer", example=1)
     *     ),
     *     @OA\Response(
     *         response=200,
     *         description="Dato obtenido correctamente",
     *         @OA\JsonContent(
     *             @OA\Property(property="success", type="boolean", example=true),
     *             @OA\Property(property="message", type="string", example="Dato obtenido correctamente"),
     *             @OA\Property(property="data", type="array", @OA\Items(type="object"))
     *         )
     *     ),
     *    @OA\Response(
     *         response=404,
     *         description="Dato no encontrado",
     *         @OA\JsonContent(
     *             @OA\Property(property="success", type="boolean", example=false),
     *             @OA\Property(property="message", type="string", example="El dato no existe.")
     *         )
     *     ),
     *     @OA\Response(
     *         response=500,
     *         description="Error interno del servidor",
     *         @OA\JsonContent(
     *             @OA\Property(property="success", type="boolean", example=false),
     *             @OA\Property(property="message", type="string", example="Error al obtener el dato: Detalles del error")
     *         )
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
     * @OA\Put(
     *     path="/api/project/{project}",
     *     tags={"Project"},
     *     summary="Actualizar un Project existente",
     *     description="Actualiza los datos de un proyecto existente con la información proporcionada",
     *     operationId="updateProject",
     *     @OA\Parameter(
     *         name="project",
     *         in="path",
     *         required=true,
     *         description="ID del Project a actualizar",
     *         @OA\Schema(type="integer", example=1)
     *     ),
     *     @OA\RequestBody(
     *         required=true,
     *         @OA\JsonContent(
     *             required={
     *                 "name",
     *                 "status"
     *             },
     *             @OA\Property(property="name", type="string", example="Proyecto 1"),
     *             @OA\Property(property="description", type="string", nullable=true, example="Esto es opcional"),
     *             @OA\Property(property="status", type="string", example="active")
     *         )
     *     ),
     *     @OA\Response(
     *         response=200,
     *         description="Dato actualizado correctamente",
     *         @OA\JsonContent(
     *             @OA\Property(property="success", type="boolean", example=true),
     *             @OA\Property(property="message", type="string", example="Dato actualizado correctamente"),
     *             @OA\Property(property="data", type="object")
     *         )
     *     ),
     *     @OA\Response(
     *         response=404,
     *         description="Dato no encontrado",
     *         @OA\JsonContent(
     *             @OA\Property(property="success", type="boolean", example=false),
     *             @OA\Property(property="message", type="string", example="El dato no existe.")
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
     *             @OA\Property(property="message", type="string", example="Error al actualizar el dato: Detalles del error")
     *         )
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
     * @OA\Delete(
     *     path="/api/project/{project}",
     *     tags={"Project"},
     *     summary="Eliminar un Project existente",
     *     description="Elimina un proyecto existente con el ID proporcionado",
     *     operationId="deleteProject",
     *     @OA\Parameter(
     *         name="project",
     *         in="path",
     *         required=true,
     *         description="ID del Project a eliminar",
     *         @OA\Schema(type="integer", example=1)
     *     ),
     *     @OA\Response(
     *         response=200,
     *         description="Dato eliminado correctamente",
     *         @OA\JsonContent(
     *             @OA\Property(property="success", type="boolean", example=true),
     *             @OA\Property(property="message", type="string", example="Dato eliminado correctamente"),
     *             @OA\Property(property="data", type="object")
     *         )
     *     ),
     *     @OA\Response(
     *         response=404,
     *         description="dato no encontrado",
     *         @OA\JsonContent(
     *             @OA\Property(property="success", type="boolean", example=false),
     *             @OA\Property(property="message", type="string", example="El dato no existe.")
     *         )
     *     ),
     *     @OA\Response(
     *         response=500,
     *         description="Error interno del servidor",
     *         @OA\JsonContent(
     *             @OA\Property(property="success", type="boolean", example=false),
     *             @OA\Property(property="message", type="string", example="Error al eliminar el dato: Detalles del error")
     *         )
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
