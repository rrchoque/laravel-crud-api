<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Student;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

class studentController extends Controller
{
    public function index()
    {
        $students = Student::all();

        if ($students->isEmpty()) {
            return response()->json([
                'status' => 'error',
                'message' => 'No students found',
            ], 200);
        }

        //return $students;
        return response()->json([
            'status' => 'success',
            'students' => $students,
        ], 200);
    }

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
            'email' => 'email|max:255|unique:student,email,' . $id,
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
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
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
