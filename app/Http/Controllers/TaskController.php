<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Task;
use Illuminate\Support\Facades\Validator;

class TaskController extends Controller
{
    public function ReadsTask()
    {
        $task = Task::orderBy('id', 'desc')->get();
        return response()->json($task, 200);
    }
    public function ReadTask($id)
    {
        $task = Task::where(['id' => $id])->first();
        if (!$task) {
            $array =
                array(
                    'response' => array(
                        'estado' => 'Bad Request',
                        'mensaje' => 'La peticion HTTP no trae datos para procesar'
                    )
                );
            return response()->json($array, 400);
        } else {
            return response()->json($task, 200);
        }
    }
    public function CreateTask(Request $request)
    {
        $json = json_decode(file_get_contents('php://input'), true);
        if (!is_array($json)) {
            $array =
                array(
                    'response' => array(
                        'estado' => 'Bad Request',
                        'mensaje' => 'La peticion HTTP no trae datos para procesar'
                    )
                );
            return response()->json($array, 400);
        }
        $validator = Validator::make(
            $request->all(),
            [
                'title' => 'required|max:100',
                'description' => 'required|max:200',
                'status' => 'required',
                'priority' => 'required'
            ],
            [
                'title.required' => 'El :attribute es un campo requerido',
                'title.max' => 'El :attribute únicamente acepta hasta 100 caracteres',
                'description.required' => 'La :attribute es un campo requerido',
                'description.max' => 'La :attribute únicamente acepta hasta 200 caracteres',
                'status.required' => 'El :attribute es un campo requerido',
                'priority.required' => 'La :attribute es un campo requerido',
            ]
        );

        if ($validator->fails()) {
            return response()->json(['errors' => $validator->errors()], 422);
        }
        Task::create(
            [
                'title' => $request->input('title'),
                'description' => $request->input('description'),
                'priority' => $request->input('priority'),
                'status' => $request->input('status'),
                'create_date' => now(),
            ]
        );
        $array =
            array(
                'response' => array(
                    'estado' => 'OK',
                    'mensaje' => 'Se creo el registro exitosamente'
                )
            );
        return response()->json($array, 200);
    }
    public function UpdateTask(Request $request, $id)
    {
        $json = json_decode(file_get_contents('php://input'), true);
        if (!is_array($json)) {
            $array =
                array(
                    'response' => array(
                        'estado' => 'Bad Request',
                        'mensaje' => 'La peticion HTTP no trae datos para procesar'
                    )
                );
            return response()->json($array, 400);
        }
        $datos = Task::where(['id' => $id])->firstOrFail();
        $datos->title = $request->input('title');
        $datos->description = $request->input('description');
        $datos->priority = $request->input('priority');
        $datos->status = $request->input('status');
        $datos->expiration_date = now();
        $datos->save();
        $array =
            array(
                'response' => array(
                    'estado' => 'OK',
                    'mensaje' => 'Se actualio el registro exitosamente'
                )
            );
        return response()->json($array, 200);
    }
    public function DeleteTask(Request $request, $id)
    {
        $datos = Task::where(['id' => $id])->firstOrFail();
        if ($datos) {
            Task::where(['id' => $id])->delete();
            $array =
                array(
                    'response' => array(
                        'estado' => 'OK',
                        'mensaje' => 'Se elimino el registro exitosamente'
                    )
                );
            return response()->json($array, 200);
        } else {
            $array =
                array(
                    'response' => array(
                        'estado' => 'Bad Request',
                        'mensaje' => 'No se puede eliminar el registro'
                    )
                );
            return response()->json($array, 400);
        }
    }
}
