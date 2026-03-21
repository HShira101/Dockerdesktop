<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Process;

class DockerController extends Controller
{
    /**
     * Muestra la vista principal de contenedores.
     */
    public function index()
    {
        // Pide una lista de contenedores a docker y otorga un formato JSON.
        $json = Process::run('docker ps -a --format "json"');
        
        // Recibe la lista y quita los saltos de línea.
        $lista = trim($json->output());

        if (empty($lista)) {
            $lista = [];
        } else {
            // Mapeamos los datos para que tengan nombres sencillos (nombre y estado).
            $lista = collect(explode("\n", $lista))->map(function($contenedor) {
                $datos = json_decode($contenedor, true);
                return [
                    'nombre' => $datos['Names'] ?? 'Sin nombre',
                    'estado' => $datos['State'] ?? 'Desconocido',
                    'id'     => $datos['ID'] ?? ''
                ];
            });
        }

        return view('contenedores', compact('lista'));
    }
    public function obtener_contenedores()
    { //Función para solicitar lista de contenedores.
        // Pide una lista de contendores a docker y otorga un formato JSON con los contenedores.
        $json = Process::run('docker ps -a --format "json"');

        //recibe la lista y quita los saltos de linea y forma una lista.
        $lista = trim($json->output());

        if (empty($lista)){
            $lista = [];
        } else{
            $lista = collect(explode("\n", $lista))->map(function($contenedor){
                return json_decode($contenedor, true);
            });
        }

        // Retorna la lista de contenedores en formato un array de objetos JSON a la variable contenedores.
        return response()->json([
            'contenedores' => $lista,
            'error' => $json->errorOutput(),
            'exito' => $json->successful()
        ]);
    }
}
