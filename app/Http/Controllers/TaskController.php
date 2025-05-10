<?php

namespace App\Http\Controllers;

use App\Models\Task;
use App\Http\Requests\StoreTaskRequest;
use App\Http\Requests\UpdateTaskRequest;
use Illuminate\Http\Request;

/**
 * @OA\Schema(
 *     schema="Task",
 *     type="object",
 *     title="Task",
 *     required={"project_id", "title", "status", "priority", "due_date"},
 *     @OA\Property(property="id", type="integer", example=1),
 *     @OA\Property(property="project_id", type="integer", example=2, description="ID del proyecto al que pertenece la tarea"),
 *     @OA\Property(property="title", type="string", example="Tarea ejemplo", description="Título de la tarea"),
 *     @OA\Property(property="description", type="string", example="Descripción corta", description="Descripción de la tarea"),
 *     @OA\Property(property="status", type="string", example="pending", description="Estado de la tarea (pending, progress, done)"),
 *     @OA\Property(property="priority", type="string", example="high", description="Prioridad de la tarea (low, medium, high)"),
 *     @OA\Property(property="due_date", type="string", format="date", example="2025-05-15", description="Fecha límite de la tarea"),
 * )
 */
class TaskController extends Controller
{
    /**
     * @OA\Get(
     *     path="/api/task",
     *     operationId="getTasks",
     *     tags={"Task"},
     *     summary="Listar tareas",
     *     description="Devuelve todas las tareas según los filtros proporcionados",
     *     @OA\Parameter(
     *         name="status",
     *         in="query",
     *         description="Filtrar tareas por estado",
     *         required=false,
     *         @OA\Schema(type="string", enum={"pending", "progress", "done"})
     *     ),
     *     @OA\Parameter(
     *         name="priority",
     *         in="query",
     *         description="Filtrar tareas por prioridad",
     *         required=false,
     *         @OA\Schema(type="string", enum={"low", "medium", "high"})
     *     ),
     *     @OA\Parameter(
     *         name="from",
     *         in="query",
     *         description="Filtrar tareas por fecha de inicio (formato YYYY-MM-DD)",
     *         required=false,
     *         @OA\Schema(type="string", format="date")
     *     ),
     *     @OA\Parameter(
     *         name="to",
     *         in="query",
     *         description="Filtrar tareas por fecha de fin (formato YYYY-MM-DD)",
     *         required=false,
     *         @OA\Schema(type="string", format="date")
     *     ),
     *     @OA\Parameter(
     *         name="project_id",
     *         in="query",
     *         description="Filtrar tareas por ID del proyecto",
     *         required=false,
     *         @OA\Schema(type="integer")
     *     ),
     *     @OA\Response(
     *         response=200,
     *         description="Éxito",
     *         @OA\JsonContent(
     *             type="array",
     *             @OA\Items(ref="#/components/schemas/Task")
     *         )
     *     )
     * )
     */
    public function index(Request $request)
    {
        $query = Task::query();

        if($request->filled('status')) {
            $query->where('status', 'like', '%' . $request->status . '%');
        }

        if($request->filled('priority')) {
            $query->where('priority', 'like', '%' . $request->priority . '%');
        }

        if($request->filled('from') && $request->filled('to')) {
            $query->whereBetween('due_date', [$request->from, $request->to]);
        }

        if($request->filled('project_id')) {
            $query->where('project_id', 'like', '%' . $request->project_id . '%');
        }

        return $query->get();
    }

    /**
     * @OA\Post(
     *     path="/api/task",
     *     operationId="createTask",
     *     tags={"Task"},
     *     summary="Crear tarea",
     *     description="Crea una nueva tarea",
     *     @OA\RequestBody(
     *         required=true,
     *         @OA\JsonContent(
     *             type="object",
     *             required={"project_id", "title", "status", "priority", "due_date"},
     *             @OA\Property(property="project_id", type="integer", example=2, description="ID del proyecto al que pertenece la tarea"),
     *             @OA\Property(property="title", type="string", example="Tarea ejemplo", description="Título de la tarea"),
     *             @OA\Property(property="description", type="string", example="Descripción corta", description="Descripción de la tarea"),
     *             @OA\Property(property="status", type="string", example="pending", description="Estado de la tarea (pending, progress, done)"),
     *             @OA\Property(property="priority", type="string", example="high", description="Prioridad de la tarea (low, medium, high)"),
     *             @OA\Property(property="due_date", type="string", format="date", example="2025-05-15", description="Fecha límite de la tarea")
     *         )
     *     ),
     *     @OA\Response(
     *         response=201,
     *         description="Tarea creada",
     *         @OA\JsonContent(
     *             type="object",
     *             @OA\Property(property="message", type="string", example="Tarea creada"),
     *             @OA\Property(property="data", ref="#/components/schemas/Task")
     *         )
     *     ),
     *     @OA\Response(
     *         response=400,
     *         description="Error de validación",
     *         @OA\JsonContent(
     *             type="object",
     *             @OA\Property(property="message", type="string", example="Error de validación")
     *         )
     *     )
     * )
     */
    public function store(StoreTaskRequest $request)
    {
        $task = Task::create($request->validated());

        return response()->json([
            'message' => 'Tarea creada',
            'data' => $task,
        ], 201);
    }

    /**
     * @OA\Get(
     *     path="/api/task/{id}",
     *     operationId="getTask",
     *     tags={"Task"},
     *     summary="Mostrar tarea",
     *     description="Muestra los detalles de una tarea específica",
     *     @OA\Parameter(
     *         name="id",
     *         in="path",
     *         description="ID de la tarea",
     *         required=true,
     *         @OA\Schema(type="integer")
     *     ),
     *     @OA\Response(
     *         response=200,
     *         description="Tarea encontrada",
     *         @OA\JsonContent(
     *             type="object",
     *             @OA\Property(property="message", type="string", example="Tarea encontrada"),
     *             @OA\Property(property="data", ref="#/components/schemas/Task")
     *         )
     *     ),
     *     @OA\Response(
     *         response=404,
     *         description="Tarea no encontrada",
     *         @OA\JsonContent(
     *             type="object",
     *             @OA\Property(property="message", type="string", example="Tarea no encontrada")
     *         )
     *     )
     * )
     */
    public function show(Task $task)
    {
        $task->load('project');
        return response()->json([
            'message' => 'Tarea encontrada',
            'data' => $task,
        ], 200);
    }

    /**
     * @OA\Put(
     *     path="/api/task/{id}",
     *     operationId="updateTask",
     *     tags={"Task"},
     *     summary="Actualizar tarea",
     *     description="Actualiza los datos de una tarea existente",
     *     @OA\Parameter(
     *         name="id",
     *         in="path",
     *         description="ID de la tarea",
     *         required=true,
     *         @OA\Schema(type="integer")
     *     ),
     *     @OA\RequestBody(
     *         required=true,
     *         @OA\JsonContent(
     *             type="object",
     *             required={"project_id", "title", "status", "priority", "due_date"},
     *             @OA\Property(property="project_id", type="integer", example=2, description="ID del proyecto al que pertenece la tarea"),
     *             @OA\Property(property="title", type="string", example="Tarea actualizada", description="Título de la tarea"),
     *             @OA\Property(property="description", type="string", example="Descripción actualizada", description="Descripción de la tarea"),
     *             @OA\Property(property="status", type="string", example="progress", description="Estado de la tarea (pending, progress, done)"),
     *             @OA\Property(property="priority", type="string", example="medium", description="Prioridad de la tarea (low, medium, high)"),
     *             @OA\Property(property="due_date", type="string", format="date", example="2025-06-01", description="Fecha límite de la tarea")
     *         )
     *     ),
     *     @OA\Response(
     *         response=200,
     *         description="Tarea actualizada",
     *         @OA\JsonContent(
     *             type="object",
     *             @OA\Property(property="message", type="string", example="Tarea actualizada"),
     *             @OA\Property(property="data", ref="#/components/schemas/Task")
     *         )
     *     ),
     *     @OA\Response(
     *         response=400,
     *         description="Error de validación",
     *         @OA\JsonContent(
     *             type="object",
     *             @OA\Property(property="message", type="string", example="Error de validación")
     *         )
     *     ),
     *     @OA\Response(
     *         response=404,
     *         description="Tarea no encontrada",
     *         @OA\JsonContent(
     *             type="object",
     *             @OA\Property(property="message", type="string", example="Tarea no encontrada")
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
     *     path="/api/task/{id}",
     *     operationId="deleteTask",
     *     tags={"Task"},
     *     summary="Eliminar tarea",
     *     description="Elimina una tarea existente",
     *     @OA\Parameter(
     *         name="id",
     *         in="path",
     *         description="ID de la tarea",
     *         required=true,
     *         @OA\Schema(type="integer")
     *     ),
     *     @OA\Response(
     *         response=200,
     *         description="Tarea eliminada",
     *         @OA\JsonContent(
     *             type="object",
     *             @OA\Property(property="message", type="string", example="Tarea eliminada")
     *         )
     *     ),
     *     @OA\Response(
     *         response=404,
     *         description="Tarea no encontrada",
     *         @OA\JsonContent(
     *             type="object",
     *             @OA\Property(property="message", type="string", example="Tarea no encontrada")
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
