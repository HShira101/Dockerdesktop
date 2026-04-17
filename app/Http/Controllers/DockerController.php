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
                    'id'     => $datos['ID'] ?? '',
                    'creado' => $datos['CreatedAt'] ?? '',
                    'puerto' => $datos['Ports'] ?? '',
                    'network' => $datos['Networks'] ?? '',
                    'ip' => $datos['IPAddress'] ?? ''
                ];
            });
        }

        return view('contenedores', compact('lista'));
    }
    // {---- Llama a la Docker Engine API por el socket Unix usando curl de PHP directamente ----}
    private function dockerPost(string $endpoint): int
    {
        $ch = curl_init();
        curl_setopt($ch, CURLOPT_UNIX_SOCKET_PATH, '/var/run/docker.sock'); // {-- ← usa el socket, no TCP --}
        curl_setopt($ch, CURLOPT_URL,           "http://localhost/v1.44{$endpoint}");
        curl_setopt($ch, CURLOPT_POST,          true);
        curl_setopt($ch, CURLOPT_POSTFIELDS,    '');
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        curl_exec($ch);
        $status = (int) curl_getinfo($ch, CURLINFO_HTTP_CODE);
        curl_close($ch);
        return $status;
    }
    // {---- Fin Llama a la Docker Engine API ----}

    // {---- Inicia un contenedor (recibe id y nombre desde el formulario de la tarjeta) ----}
    public function iniciar(Request $request)
    {
        $id     = $request->input('id');     // {-- ← ID corto del contenedor --}
        $nombre = $request->input('nombre'); // {-- ← nombre para mostrar en notificación --}

        $status = $this->dockerPost("/containers/{$id}/start");

        // {-- ← 204 = arrancado, 304 = ya estaba corriendo; ambos son éxito --}
        return redirect('/')->with('notificacion', [
            'accion' => in_array($status, [204, 304]) ? 'encendido' : 'error',
            'nombre' => $nombre,
        ]);
    }
    // {---- Fin Inicia un contenedor ----}

    // {---- Para un contenedor (recibe id y nombre desde el formulario de la tarjeta) ----}
    public function parar(Request $request)
    {
        $id     = $request->input('id');     // {-- ← ID corto del contenedor --}
        $nombre = $request->input('nombre'); // {-- ← nombre para mostrar en notificación --}

        $status = $this->dockerPost("/containers/{$id}/stop");

        // {-- ← 204 = detenido, 304 = ya estaba detenido; ambos son éxito --}
        return redirect('/')->with('notificacion', [
            'accion' => in_array($status, [204, 304]) ? 'apagado' : 'error',
            'nombre' => $nombre,
        ]);
    }
    // {---- Fin Para un contenedor ----}

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
