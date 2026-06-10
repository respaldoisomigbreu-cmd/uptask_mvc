<?php

namespace Controllers;

use Model\Proyecto;
use Model\Usuario;
use MVC\Router;


class DashboardController {
    public static function index(Router $router){

    session_start();
    isAuth();
    $id = $_SESSION['id'];
    $proyectos = Proyecto::belongsTo('propietarioId', $id); 

    //debuguear($proyectos);
        // Render a la vista
        $router->render('dashboard/index', [
            'titulo' => 'Proyectos',
            'proyectos' => $proyectos
        ]);
    }

    public static function crear_proyecto(Router $router){
        session_start();
        isAuth();
        $alertas = [];

        if($_SERVER['REQUEST_METHOD'] === 'POST'){
            $proyecto = new Proyecto($_POST);

            // Validar que el proyecto tenga un nombre
            $alertas = $proyecto->validarProyecto();

            if(empty($alertas)){
                // Generar una URL única
                $hash = md5(uniqid());
                $proyecto->url = $hash;

                // Almacenar el Id del Propietario del Proyecto
                $proyecto->propietarioId = $_SESSION['id'];

                // Guardar el Proyecto
                $proyecto->guardar();

                // Redireccionar
                header('Location: /proyecto?url=' . $proyecto->url);
            }

        }


        // Render a la vista
        $router->render('dashboard/crear-proyecto', [
            'titulo' => 'Crear Proyecto',
            'alertas' => $alertas
        ]);
    }
    public static function proyecto(Router $router){
        session_start();
        isAuth();
        //validar que la URL sea correcta
        $token = $_GET['url']  ?? null;
        if(!$token){
            header('Location: /dashboard');
            exit;
        }

        //revisar que el proyecto exista
        $proyecto = Proyecto::where('url', $token);

        //revisar que la persona que visita el proyecto es el creador
        if(!$proyecto || $proyecto->propietarioId != $_SESSION['id']){
            header('Location: /dashboard');
            exit;
        }

      //  debuguear($proyecto);

         // Render a la vista
        $router->render('dashboard/proyecto', [
            'titulo' => $proyecto->proyecto,
            'proyecto' => $proyecto
        ]);

    }

    public static function perfil(Router $router){
        session_start();
        isAuth();
        $alertas = [];
        $usuario = Usuario::find($_SESSION['id']);
        

        //actualizar el perfil
        if($_SERVER['REQUEST_METHOD'] === 'POST'){
            $usuario->sincronizar($_POST);
            $alertas = $usuario->validar_perfil();

            if(empty($alertas)){
                //validar que el email no exista
                $existeUsuario = Usuario::where('email', $usuario->email);
                
                if($existeUsuario && $existeUsuario->id != $usuario->id){
                    Usuario::setAlerta('error', 'El Email ya está registrado');
                    $alertas = $usuario->getAlertas();
                } else {
                    //gusrdar el usuario
                    $usuario->guardar();
                    $_SESSION['nombre'] = $usuario->nombre;
                    Usuario::setAlerta('exito', 'Guardado Correctamente');
                    $alertas = $usuario->getAlertas();
                }
            }
            
        }

        // Render a la vista
        $router->render('dashboard/perfil', [
            'titulo' => 'Perfil',
            'alertas' => $alertas,
            'usuario' => $usuario
        ]);
    }

    public static function cambiar_password(Router $router) {
        session_start();
        isAuth();
        $alertas = [];

        if($_SERVER['REQUEST_METHOD'] === 'POST'){
            $usuario = Usuario::find($_SESSION['id']);

            //sincronizar con los datos del usuario
            $usuario->sincronizar($_POST);
            $alertas = $usuario->nuevo_password();

            if(empty($alertas)){
                $resultado = $usuario->comprobar_password();

                if($resultado){
                    $usuario->password =$usuario->password_nuevo;

                    //eliminar propiedades no necesarias 
                    unset($usuario->password_actual);
                    unset($usuario->password_nuevo);

                    //hashear el Password
                    $usuario->hashPassword();

                    //Actualizar password
                    $resultado = $usuario->guardar();

                    if($resultado){
                        Usuario::setAlerta('exito', 'Password Guardado Corectamente');
                    $alertas = $usuario->getAlertas();
                    }

                }else{
                    Usuario::setAlerta('error', 'Password Incorrecto');
                    $alertas = $usuario->getAlertas();
                }

                
            }

        }
        //render a la vista 
        $router->render('dashboard/cambiar-password', [
            'titulo' => 'Cambiar Password',
            'alertas'=> $alertas

        ]);
    }

}