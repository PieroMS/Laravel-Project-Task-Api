<?php

namespace App\Http\Controllers;

use App\Models\Task;
use App\Http\Requests\StoreTaskRequest;
use App\Http\Requests\UpdateTaskRequest;

/**
 * @OA\Tag(
 *     name="Task",
 *     description="API Endpoints para gestión de Tareas"
 * )
 */
class TaskController extends Controller
{
    /**
     * @OA\Get(
     *     path="/api/task",
     *     tags={"Task"},
     *     summary="Obtener la lista de Task",
     *     description="Obtiene una lista de todos las tareas disponibles",
     *     operationId="getTask",
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
        $data = Task::all();
        return response()->json([
            'message' => 'Lista de tareas',
            'data' => $data,
        ], 200);
    }
    
    /**
     * @OA\Post(
     *     path="/api/task",
     *     tags={"Task"},
     *     summary="Crear un nuevo Task",
     *     description="Crea una nueva tarea con los datos proporcionados",
     *     operationId="createTask",
     *     @OA\RequestBody(
     *         required=true,
     *         @OA\JsonContent(
     *             required={
     *                 "project_id",
     *                 "title",
     *                 "status",
     *                 "priority",
     *                 "due_date"
     *             },
     *             @OA\Property(property="project_id", type="string", example="1"),
     *             @OA\Property(property="title", type="string", example="Tarea de ejemplo"),
     *             @OA\Property(property="description", type="string", nullable=true, example="Esto es opcional"),
     *             @OA\Property(property="status", type="string", example="pending"),
     *             @OA\Property(property="priority", type="string", example="medium"),
     *             @OA\Property(property="due_date", type="string", example="2025-06-01")
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
    public function store(StoreTaskRequest $request)
    {
        $task = Task::create($request->all());

        return response()->json([
            'message' => 'Tarea creada',
            'data' => $task,
        ], 201);
    }
    
    /**
     * @OA\Get(
     *     path="/api/task/{task}",
     *     tags={"Task"},
     *     summary="Buscar un Task por id",
     *     description="Devuelve una tarea en especifico.",
     *     operationId="showReward",
     *     @OA\Parameter(
     *         name="task",
     *         in="path",
     *         required=true,
     *         description="ID del Task a buscar",
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
    public function show(Task $task)
    {
        $task = Task::find($task->id);
        return response()->json([
            'message' => 'Tarea encontrada',
            'data' => $task,
        ], 200);
    }
    
    /**
     * @OA\Put(
     *     path="/api/task/{task}",
     *     tags={"Task"},
     *     summary="Actualizar un task existente",
     *     description="Actualiza los datos de una tarea existente con la información proporcionada",
     *     operationId="updateTask",
     *     @OA\Parameter(
     *         name="task",
     *         in="path",
     *         required=true,
     *         description="ID del Task a actualizar",
     *         @OA\Schema(type="integer", example=1)
     *     ),
     *     @OA\RequestBody(
     *         required=true,
     *         @OA\JsonContent(
     *             required={
     *                 "title",
     *                 "status",
     *                 "priority",
     *                 "due_date"
     *             },
     *             @OA\Property(property="title", type="string", example="Tarea de ejemplo"),
     *             @OA\Property(property="description", type="string", nullable=true, example="Esto es opcional"),
     *             @OA\Property(property="status", type="string", example="pending"),
     *             @OA\Property(property="priority", type="string", example="medium"),
     *             @OA\Property(property="due_date", type="string", example="2025-06-01")
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
    public function update(UpdateTaskRequest $request, Task $task)
    {
        $task->update($request->validated());

        return response()->json([
            'message' => 'Tarea actualizada',
            'data' => $task,
        ], 200);
    }
    
    /**
     * @OA\Delete(
     *     path="/api/task/{task}",
     *     tags={"Task"},
     *     summary="Eliminar un Task existente",
     *     description="Elimina una tarea existente con el ID proporcionado",
     *     operationId="deleteTask",
     *     @OA\Parameter(
     *         name="task",
     *         in="path",
     *         required=true,
     *         description="ID del Task a eliminar",
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
    public function destroy(Task $task)
    {
        $task->delete();

        return response()->json([
            'message' => 'Tarea eliminada',
        ], 200);
    }
}
