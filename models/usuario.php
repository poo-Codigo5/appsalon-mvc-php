<?php

namespace Model;

class Usuario extends ActiveRecord {
    // Base de datos
    protected static $tabla = 'usuarios';
    protected static $columnasDB = ['id', 'nombre', 'apellidos', 'email', 'password', 'telefono',
    'admin', 'confirmado', 'token'];

    public $id;
    public $nombre;
    public $apellidos;
    public $email;
    public $password;
    public $telefono;
    public $admin;
    public $confirmado;
    public $token;

    public function __construct($args = []) {
        $this->id = $args['id'] ?? null;       
        $this->nombre = $args['nombre'] ?? '';
        $this->apellidos = $args['apellidos'] ?? '';
        $this->email = $args['email'] ?? '';
        $this->password = $args['password'] ?? '';
        $this->telefono= $args['telefono'] ?? '';
        $this->admin= $args['admin'] ?? '0';
        $this->confirmado= $args['confirmado'] ?? '0';
        $this->token= $args['token'] ?? '';

    }
    // Mensajes de validación para la creación de una cuenta
    public function validarNuevaCuenta() {
        if(!$this->nombre) {
            self::$alertas['error'] [] = 'El nombre es obligatorio';
        }
        if(!$this->apellidos) {
            self::$alertas['error'] [] = 'Los apellidos son obligatorios';
        }
        if(!$this->email) {
            self::$alertas['error'] [] = 'El email es obligatorio';
        }
        if(!$this->password) {
            self::$alertas['error'] [] = 'El password es obligatorio';
        }
        if(strlen($this->password) < 6) {
            self::$alertas['error'] [] = 'El password debe contener al menos 6 caracteres';
        }
        return self::$alertas;
    } 

    public function validarLogin() {
        if(!$this->email) {
            self::$alertas['error'][] = 'El email es obligatorio';
        }
        if(!$this->password) {
            self::$alertas['error'][] = 'El password es obligatorio';
        }
        return self::$alertas;
    }

    public function validarEmail() {
        if(!$this->email) {
            self::$alertas['error'][] = 'El email es obligatorio';
        }
        return self::$alertas;
    }
    public function validarPassword() {
        if(!$this->password) {
            self::$alertas['error'][] = 'El password es obligatorio';
        }
        if(strlen($this->password) < 6) {
            self::$alertas['error'][] = 'El password debe tener al menos 6 caracteres';
        }

        return self::$alertas;

    }



    // Revisa si el usuario existe
    public function existeUsuario() {
        $query ="SELECT * FROM " . self::$tabla . " WHERE email = '" . $this->email . "' LIMIT 1";

        $resultado = self::$db->query($query);
        
        if($resultado->num_rows) {
            self::$alertas['error'] [] = "El usuario ya está registrado";

        } 

        return $resultado;
    }

    public function hashPassword() {
        $this->password = password_hash($this->password, PASSWORD_BCRYPT);
    }

    public function crearToken() {
        $this->token = uniqid();
    }

    public function comprobarPasswordAndVerificado($password) {
        
        $resultado = password_verify($password, $this->password);
        if( !$resultado || !$this->confirmado) {
            //debuguear('El usuario no está confirmado');
            self::$alertas['error'][]='Password incorrecto o tu cuenta no ha sido confirmada';
        } else {
            return true;
        }
        //debuguear($resultado);
        //debuguear($this);

    }

}
