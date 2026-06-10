<?php

namespace Model;


class Usuario extends ActiveRecord {
    protected static $tabla = 'usuarios';
    protected static $columnasDB = ['id',
                                    'nombre',
                                    'email',
                                    'password',
                                    'token',
                                    'confirmado'
                                    ]; 

    public ?int $id; // El ? permite que sea null al principio
    public string $nombre;
    public string $email;
    public string $password;
    public string $password2 = ''; // Aunque no esté en la DB, es un string
    public string $password_actual = ''; // Aunque no esté en la DB, es un string
    public string $password_nuevo = ''; // Aunque no esté en la DB, es un string
    public string $token;
    public int $confirmado; // O 'int' si en tu DB es un número

    
    public function __construct($args = [])
    {
        $this->id = $args['id'] ?? null;
        $this->nombre = $args['nombre'] ?? '';
        $this->email = $args['email'] ?? '';
        $this->password = $args['password'] ?? '';
        $this->password2 = $args['password2'] ?? '';
        $this->password_actual = $args['password_actual'] ?? '';
        $this->password_nuevo = $args['password_nuevo'] ?? '';
        $this->token = $args['token'] ?? '';
        $this->confirmado = $args['confirmado'] ?? 0;
    }
    // Validar el Login de Usuario
    public function ValidarLogin(){

        if(!$this->email){
            self::$alertas['error'][] = 'El Email del Usuario es Obligatorio';
        }
        if(!filter_var($this->email, FILTER_VALIDATE_EMAIL)){
            self::$alertas['error'][] = 'El Email no es válido';
        }
        if(!$this->password){
            self::$alertas['error'][] = 'El Password del Usuario es Obligatorio';
        }
        return self::$alertas;
    }

    //validar cuenta nueva
    public function validarNuevaCuenta(){
        if(!$this->nombre){
            self::$alertas['error'][] = 'El Nombre del Usuario es Obligatorio';
        }
                
        if(!$this->email){
            self::$alertas['error'][] = 'El Email del Usuario es Obligatorio';
        }
        if(!$this->password){
            self::$alertas['error'][] = 'El Password del Usuario es Obligatorio';
        }
        if(strlen($this->password) < 6 ){
            self::$alertas['error'][] = 'El password del Usuario debe Contener Almenos 6 Caracteres';
        }
        if($this->password !== $this->password2 ){
            self::$alertas['error'][] = 'Los Password deben de ser Iguales';
        }

        return self::$alertas;
    }

    public function nuevo_password() :array {
        if(!$this->password_actual){
            self::$alertas['error'][] = 'Los Password Actual No Pueder ir Vacio';
        }
        if(!$this->password_nuevo){
            self::$alertas['error'][] = 'Los Password Nuevo No Pueder ir Vacio';
        }
        if(strlen($this->password_nuevo) < 6 ){
            self::$alertas['error'][] = 'El password Nuevo debe Contener Almenos 6 Caracteres';
        }
        return self::$alertas;

    }

    // Validar email de Usuario
    public function validarEmail(){
        if(!$this->email){
            self::$alertas['error'][] = 'El Email del Usuario es Obligatorio';
        }
        if(!filter_var($this->email, FILTER_VALIDATE_EMAIL)){
            self::$alertas['error'][] = 'El Email no es válido';
        }
        return self::$alertas;
    }

    // Validar el password
    public function validarPassword(){
        
        if(!$this->password){
            self::$alertas['error'][] = 'El Password del Usuario es Obligatorio';
        }
        if(strlen($this->password) < 6 ){
            self::$alertas['error'][] = 'El password del Usuario debe Contener Almenos 6 Caracteres';
        }
        if($this->password !== $this->password2 ){
            self::$alertas['error'][] = 'Los Passwor deben de ser Iguales';
        }

        return self::$alertas;
    }

    //| Validar el perfil del Usuario
    public function validar_perfil(){
        if(!$this->nombre){
            self::$alertas['error'][] = 'El Nombre del Usuario es Obligatorio';
        }
                
        if(!$this->email){
            self::$alertas['error'][] = 'El Email del Usuario es Obligatorio';
        }
        return self::$alertas;
    }

    //comprovar el password antes de cambiarlo
    public function comprobar_password(): bool {
        return password_verify($this->password_actual, $this->password);
    }

    //hashea el password
    public function hashPassword() : void{
        $this->password = password_hash($this->password, PASSWORD_BCRYPT);
    }
    //generar un token
    public function crearToken() : void {
        $this->token = uniqid();
    }

}