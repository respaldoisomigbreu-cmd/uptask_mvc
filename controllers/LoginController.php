<?php

namespace Controllers;

use Classes\Email;
use Model\Usuario;
use MVC\Router;

class LoginController {

    public static function login(Router $router){
        $alertas = [];
        if($_SERVER['REQUEST_METHOD'] === 'POST'){
            $usuario = new Usuario($_POST);
            $alertas = $usuario->ValidarLogin();

            if(empty($alertas)){
                //verificar que el usuario exista
                $usuario = Usuario::where('email', $usuario->email);

                if(!$usuario || !$usuario->confirmado){
                    Usuario::setAlerta('error', 'El Usuario no Existe o no está confirmado');
                }else{
                    //el usuario existe, verificar el password
                    if(password_verify($_POST['password'], $usuario->password)){
                        //iniciar la sesión
                        session_start();
                        $_SESSION['id'] = $usuario->id;
                        $_SESSION['nombre'] = $usuario->nombre;
                        $_SESSION['email'] = $usuario->email;
                        $_SESSION['login'] = true; 

                        //redireccionar
                        header('Location: /dashboard');
                    }else{
                        Usuario::setAlerta('error', 'Password Incorrecto');
                    }

                }
            }
        }
        $alertas = Usuario::getAlertas();
        // Render a la vista
        $router->render('auth/login', [
            'titulo' => 'Iniciar Sesión',
            'alertas' => $alertas
        ]);
    } 

    public static function logout(){
        session_start();
        $_SESSION = [];
        header('Location: /');
    }

    public static function crear(Router $router){
        $alertas = [];
        $usuario = new Usuario;

        if($_SERVER['REQUEST_METHOD'] === 'POST'){
            $usuario ->sincronizar($_POST);
            $alertas = $usuario->validarNuevaCuenta();

            if(empty($alertas)){
                $existeUsuario = Usuario::where('email',$usuario->email);
                /** @var Usuario $usuario */ // <-- Añade esta línea para indicarle al editor que $existeUsuario es una instancia de Usuario
                if($existeUsuario){
                    Usuario::setAlerta('error', 'El Usuario Ya esta Registrado');
                    $alertas = Usuario::getAlertas();
                } else{
                    //hashear el password
                    $usuario->hashPassword();
                    //eliminar un elemento en este caso "password2"
                    unset($usuario->password2);
                    //generar Token
                    $usuario->crearToken();
                    //crear un nuevo ususario
                    $resultado = $usuario->guardar();
                    //Enviar Email
                    $email = new Email($usuario->email, $usuario->nombre, $usuario->token);
                    $email->enviarConfirmacion();


                    if($resultado){
                        header('Location: /mensaje');
                    }
                }
            }
        }
        // Render a la vista
        $router->render('auth/crear', [
            'titulo' => 'Crear Cuenta',
            'usuario' => $usuario,
            'alertas' => $alertas
        ]);

    }

    public static function olvide(Router $router){
        $alertas = [];

        if($_SERVER['REQUEST_METHOD'] === 'POST'){
            $usuario = new Usuario($_POST);
            $alertas = $usuario->validarEmail();

                if(empty($alertas)){
                    /** @var Usuario $usuario */ // <-- Añade esta línea para indicarle al editor que $usuario es una instancia de Usuario

                    //buscar Usuario
                    $usuario = Usuario::where('email', $usuario->email);
                    if($usuario && $usuario->confirmado) {

                        //generar un nuevo token
                        $usuario->crearToken();

                        //eliminar del objeto el password2
                        if (isset($usuario->password2)) {
                            unset($usuario->password2);
                        }  

                        //actualizar el usuario
                        $usuario->guardar();

                        //enviar email
                        $email = new Email($usuario->email, $usuario->nombre, $usuario->token);
                        $email->enviarInstrucciones();

                        //Alerta de exito
                        Usuario::setAlerta('exito', 'Se han enviado las instrucciones a tu email');
                        header('Location: /mensaje');
                    } else{
                        Usuario::setAlerta('error', 'El Usuario No Existe o no esta Confirmado');
                    }
                }
                $alertas = Usuario::getAlertas();
        }

        // Render a la vista
        $router->render('auth/olvide', [
            'titulo' => 'Recuperar Password',
            'alertas' => $alertas
        ]);
    }

    public static function reestablecer(Router $router){
        //validar el token
        $token = s($_GET['token']);
        $mostar = true;
        if(!$token) header('Location: /');
        
        //encontrar el usuario con este token
        $usuario = Usuario::where('token',$token);
        /** @var Usuario $usuario */ 
        if(empty($usuario)) {
            Usuario::setAlerta('error', 'Token No Valido');
            $mostar = false;
        }
        
        if($_SERVER['REQUEST_METHOD'] === 'POST' && $usuario){
            //leer el nuevo password 
            $usuario->sincronizar($_POST);

            //validar el nuevo password
            $alertas = $usuario->validarPassword();
            if(empty($alertas)){
                //hashear el password
                $usuario->hashPassword();
                //eliminar el password2
                unset($usuario->password2);
                //eliminar el token
                $usuario->token = '';
                //guardar el usuario
                $resultado = $usuario->guardar();
                //redireccionar
                if($resultado){
                    header('Location: /');
                }
            }
        }
        $alertas = Usuario::getAlertas();
        // Render a la vista
        $router->render('auth/reestablecer', [
            'titulo' => 'Reestablecer Password',
            'alertas' => $alertas,
            'mostar' => $mostar
        ]);
    }

    public static function mensaje(Router $router){

        // Render a la vista
        $router->render('auth/mensaje', [
            'titulo' => 'Cuenta Creada Correctamente'
        ]);
    }

    public static function confirmar(Router $router){
        $token = s($_GET['token']); 

        if(!$token) header('Location: /');
        //encontar el usuario con este Token
        $usuario = Usuario::where('token',$token);

        /** @var Usuario $usuario */ // <--- Esta línea le dice al editor qué es $usuario
        if(empty($usuario)){
            //no se encontro el usuario
            Usuario::setAlerta('error', 'Token No Valido');
        }else{
            //confirmar la cuenta 
            $usuario->confirmado =1;
            $usuario->token = '';
            unset($usuario->password2);
            //guardar en la base de dato.
            $usuario->guardar();
            Usuario::setAlerta('exito', 'Cuenta Verificada Correctamente');


        }
        
        $alertas = Usuario::getAlertas();

    // Render a la vista
        $router->render('auth/confirmar', [
            'titulo' => 'Cuenta Confirmada',
            'alertas' => $alertas
        ]);
    }
    
}