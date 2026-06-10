<?php

namespace Controllers;

use Model\Proyecto;
use Model\Tarea;



class TareaControllers {
    public static function index(){
        session_start();                                                 // Verificar que el usuario esté autenticado
        $proyectoId = $_GET['url'];                                      // Verificar que se haya proporcionado un ID de proyecto

        if(!$proyectoId)header('Location: /dashboard');                  // Obtener el proyecto para verificar que exista y que el usuario tenga acceso

        $proyecto = Proyecto::where('url', $proyectoId);                 // Verificar que el proyecto exista y que el usuario tenga acceso a él

        if(!$proyecto || $proyecto->propietarioId != $_SESSION['id']){   // Si el proyecto no existe o el usuario no tiene acceso, redirigir a una página de error o mostrar un mensaje
            header('Location: /404'); 
        }

        $tareas = Tarea::belongsTo('proyectoId', $proyecto->id);

        echo json_encode($tareas);
    }

    public static function crear(){
        if($_SERVER['REQUEST_METHOD'] === 'POST'){
            session_start();
            $proyectoID = $_POST['proyectoId'];
            $proyecto = Proyecto::where('url', $proyectoID);

            if(!$proyecto || $proyecto->propietarioId != $_SESSION['id']){
                $respuesta = [
                    'tipo' => 'error',
                    'mensaje' => 'hubo un error al agregar la tarea'
                ];
                echo json_encode($respuesta);
                return;
            } 
            //construir peticion
            $tarea = new Tarea($_POST);
            $tarea->proyectoId = $proyecto->id;
            $resultado = $tarea->guardar();
            $respuesta = [
                'tipo' => 'exito',
                'mensaje' => 'Tarea creada correctamente',
                'id' => $resultado['id'],
                'proyectoId' => $proyecto->id
            ];
            echo json_encode($respuesta);

        }
    }

    public static function actualizar(){

        if($_SERVER['REQUEST_METHOD'] === 'POST'){
            //validar que el proyecto exista
            $proyecto = Proyecto::where('url', $_POST['proyectoId']); 

            session_start();

            if(!$proyecto || $proyecto->propietarioId != $_SESSION['id']){
                $respuesta = [
                    'tipo' => 'error',
                    'mensaje' => 'hubo un error al actualizar la tarea'
                ];
                echo json_encode($respuesta);
                return;
            }
            $tarea = new Tarea($_POST);
            $tarea->proyectoId = $proyecto->id;

            $resultado = $tarea->guardar();
            if($resultado){
                $respuesta = [
                    'tipo' => 'exito',                    
                    'id' => $tarea->id,
                    'proyectoId' => $proyecto->id,
                    'mensaje' =>'Actualizado Correctamente'
                ];
                echo json_encode(['respuesta' => $respuesta]);
            }            
        }
    }

    public static function eliminar(){
        if($_SERVER['REQUEST_METHOD'] === 'POST'){
            //validar que el proyecto exista
            $proyecto = Proyecto::where('url', $_POST['proyectoId']); 
            session_start();

            if(!$proyecto || $proyecto->propietarioId != $_SESSION['id']){
                $respuesta = [
                    'tipo' => 'error',
                    'mensaje' => 'hubo un error al actualizar la tarea'
                    
                ];
                echo json_encode($respuesta);
                return;
            }
            $tarea =new Tarea($_POST);
            $resultado = $tarea->eliminar();

            $resultado = [
                'resultado' => $resultado,
                'mensaje' => 'Eliminando Correctamente',
                'tipo' => 'exito'
            ];

            echo json_encode($resultado);
        }
    }
}