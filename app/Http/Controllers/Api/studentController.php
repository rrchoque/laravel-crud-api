<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Student;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

/**
 * @OA\Schema(
 *     schema="Student",
 *     title="Student",
 *     description="Datos de un estudiante",
 *     @OA\Property(property="id", type="integer", example=1),
 *     @OA\Property(property="name", type="string", example="Juan Pérez"),
 *     @OA\Property(property="email", type="string", format="email", example="juan@example.com"),
 *     @OA\Property(property="phone", type="string", example="+591 70000000"),
 *     @OA\Property(property="language", type="string", example="Español"),
 *     @OA\Property(property="created_at", type="string", format="date-time"),
 *     @OA\Property(property="updated_at", type="string", format="date-time")
 * )
 * 
 * @OA\Schema(
 *     schema="StudentRequest",
 *     required={"name", "email", "phone", "language"},
 *     @OA\Property(property="name", type="string", example="María Rodríguez"),
 *     @OA\Property(property="email", type="string", format="email", example="maria@example.com"),
 *     @OA\Property(property="phone", type="string", example="+591 77777777"),
 *     @OA\Property(property="language", type="string", example="Inglés")
 * )
 * 
 * @OA\Info(
 *     title="API de Estudiantes",
 *     version="1.0.0",
 *     description="API para la gestión de estudiantes: listar, crear, actualizar y eliminar."
 * )
 *
 * @OA\Server(
 *     url="http://localhost:8000",
 *     description="Servidor local"
 * )
 */
class studentController extends Controller
{
    /**
     * @OA\Get(
     *     path="/api/students",
     *     summary="Listar estudiantes",
     *     tags={"Students"},
     *     @OA\Response(
     *         response=200,
     *         description="Lista de estudiantes obtenida exitosamente",
     *         @OA\JsonContent(
     *             type="object",
     *             @OA\Property(property="status", type="string", example="success"),
     *             @OA\Property(
     *                 property="students",
     *                 type="array",
     *                 @OA\Items(ref="#/components/schemas/Student")
     *             )
     *         )
     *     ),
     *     @OA\Response(
     *         response=204,
     *         description="No se encontraron estudiantes"
     *     )
     * )
     */
    public function index()
    {
        $students = Student::all();

        if ($students->isEmpty()) {
            return response()->json([
                'status' => 'error',
                'message' => 'No students found',
            ], 204);
        }

        //return $students;
        return response()->json([
            'status' => 'success',
            'students' => $students,
        ], 200);
    }

    /**
     * @OA\Get(
     *     path="/api/students/{id}",
     *     summary="Obtener un estudiante por ID",
     *     tags={"Students"},
     *     @OA\Parameter(
     *         name="id",
     *         in="path",
     *         required=true,
     *         description="ID del estudiante",
     *         @OA\Schema(type="integer")
     *     ),
     *     @OA\Response(
     *         response=200,
     *         description="Estudiante encontrado",
     *         @OA\JsonContent(
     *             type="object",
     *             @OA\Property(property="status", type="string", example="success"),
     *             @OA\Property(property="student", ref="#/components/schemas/Student")
     *         )
     *     ),
     *     @OA\Response(
     *         response=404,
     *         description="Estudiante no encontrado"
     *     )
     * )
     */
    public function show($id)
    {
        $student = Student::find($id);

        if (!$student) {
            return response()->json([
                'status' => 'error',
                'message' => 'Estudiante no encontrado',
            ], 404);
        }

        return response()->json([
            'status' => 'success',
            'student' => $student,
        ], 200);
    }

    /**
     * @OA\Post(
     *     path="/api/students",
     *     summary="Crear un nuevo estudiante",
     *     tags={"Students"},
     *     @OA\RequestBody(
     *         required=true,
     *         @OA\JsonContent(ref="#/components/schemas/StudentRequest")
     *     ),
     *     @OA\Response(
     *         response=201,
     *         description="Estudiante creado exitosamente",
     *         @OA\JsonContent(
     *             type="object",
     *             @OA\Property(property="status", type="string", example="success"),
     *             @OA\Property(property="student", ref="#/components/schemas/Student")
     *         )
     *     ),
     *     @OA\Response(
     *         response=422,
     *         description="Error de validación"
     *     ),
     *     @OA\Response(
     *         response=500,
     *         description="Error del servidor"
     *     )
     * )
     */
    public function store(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'name' => 'required|string|max:255',
            'email' => 'required|email|max:255|unique:student,email',
            'phone' => 'required|string|max:15',
            'language' => 'required|string|max:10',
        ]);

        if ($validator->fails()) {
            return response()->json([
                'status' => 'error',
                'message' => 'Error en la validación de datos',
                'errors' => $validator->errors(),
            ], 422);
        }

        $student = Student::create($request->all());

        if (!$student) {
            return response()->json([
                'status' => 'error',
                'message' => 'Error al crear el estudiante',
            ], 500);
        }

        return response()->json([
            'status' => 'success',
            'student' => $student,
        ], 201);
    }

    /**
     * @OA\Put(
     *     path="/api/students/{id}",
     *     summary="Actualizar estudiante",
     *     tags={"Students"},
     *     @OA\Parameter(
     *         name="id",
     *         in="path",
     *         required=true,
     *         description="ID del estudiante a actualizar",
     *         @OA\Schema(type="integer")
     *     ),
     *     @OA\RequestBody(
     *         required=true,
     *         @OA\JsonContent(ref="#/components/schemas/StudentRequest")
     *     ),
     *     @OA\Response(
     *         response=200,
     *         description="Estudiante actualizado",
     *         @OA\JsonContent(
     *             type="object",
     *             @OA\Property(property="status", type="string", example="success"),
     *             @OA\Property(property="student", ref="#/components/schemas/Student")
     *         )
     *     ),
     *     @OA\Response(
     *         response=404,
     *         description="Estudiante no encontrado"
     *     ),
     *     @OA\Response(
     *         response=422,
     *         description="Error de validación"
     *     )
     * )
     */
    public function update(Request $request, $id)
    {
        $student = Student::find($id);

        if (!$student) {
            return response()->json([
                'status' => 'error',
                'message' => 'Estudiante no encontrado',
            ], 404);
        }

        $validator = Validator::make($request->all(), [
            'name' => 'string|max:255',
            'email' => 'email|max:255|unique:students,email,' . $id, // Corrección: nombre de la tabla
            'phone' => 'string|max:15',
            'language' => 'string|max:10',
        ]);

        if ($validator->fails()) {
            return response()->json([
                'status' => 'error',
                'message' => 'Error en la validación de datos',
                'errors' => $validator->errors(),
            ], 422);
        }

        $student->update($request->all());

        return response()->json([
            'status' => 'success',
            'student' => $student,
        ], 200);
    }

    /**
     * @OA\Delete(
     *     path="/api/students/{id}",
     *     summary="Eliminar estudiante",
     *     tags={"Students"},
     *     @OA\Parameter(
     *         name="id",
     *         in="path",
     *         required=true,
     *         description="ID del estudiante a eliminar",
     *         @OA\Schema(type="integer")
     *     ),
     *     @OA\Response(
     *         response=200,
     *         description="Estudiante eliminado",
     *         @OA\JsonContent(
     *             type="object",
     *             @OA\Property(property="status", type="string", example="success"),
     *             @OA\Property(property="message", type="string", example="Estudiante eliminado correctamente")
     *         )
     *     ),
     *     @OA\Response(
     *         response=404,
     *         description="Estudiante no encontrado"
     *     )
     * )
     */
    public function destroy($id)
    {
        $student = Student::find($id);

        if (!$student) {
            return response()->json([
                'status' => 'error',
                'message' => 'Estudiante no encontrado',
            ], 404);
        }

        $student->delete();

        return response()->json([
            'status' => 'success',
            'message' => 'Estudiante eliminado correctamente',
        ], 200);
    }
}
