<?php
namespace Controllers;

use Classes\Email;
use MVC\Router;
use Model\Usuario;

class LoginController {
    public static function login(Router $router) {
        $alertas = [];
        if($_SERVER['REQUEST_METHOD'] === 'POST') {
            $auth = new Usuario($_POST);

            $alertas = $auth->validarLogin();
            //debuguear($auth);
            if(empty($alertas)) {
                //comprobar que exista usuario
                $usuario = Usuario::where('email', $auth->email);

                if($usuario) {
                    //Verificar el password
                    if( $usuario->comprobarPasswordAndVerificado($auth->password)) {
                        // Autenticar el usuario
                        session_start();
                        $_SESSION['id'] = $usuario->id;
                        $_SESSION['nombre'] = $usuario->nombre . " " . $usuario->apellidos;
                        $_SESSION['email'] = $usuario->email;
                        $_SESSION['login'] = true;

                        //Redireccionamiento
                        if($usuario->admin === "1") {
                            $_SESSION['admin'] = $usuario->admin ?? null;
                            header('Location: /admin');
                        } else {
                            header('Location: /cita');
                        }

                    }
                } else {
                    Usuario::setAlerta('error', 'Usuario no encontrado');
                }


            }
        }

        $alertas = Usuario::getAlertas();

        $router->render('auth/login', [
            'alertas' => $alertas
        ]);
        
    }

    public static function logout() {
        //session_start();
        //debuguear($_SESSION);

        $_SESSION = [];

        header('Location: /');
    }

    public static function olvide(Router $router) {
        $alertas = [];

        if($_SERVER['REQUEST_METHOD'] === 'POST') {
            $auth = new Usuario($_POST);
            $alertas = $auth->validarEmail();
            if(empty($alertas)) {
                $usuario = Usuario::where('email', $auth->email);
                if($usuario && $usuario->confirmado === "1") {
                    //Generar un token
                    $usuario->crearToken();
                    $usuario->guardar();

                    //Enviar email
                    $email = new Email($usuario->email, $usuario->nombre, $usuario->token);
                    $email->enviarInstrucciones();

                    Usuario::setAlerta('exito', 'Revisa tu email');
                } else {
                    Usuario::setAlerta('error', 'El usuario no existe o noo está confirmado');
                }
            }
        }

        $alertas = Usuario::getAlertas();
        $router->render('auth/olvide-password', [
            'alertas' => $alertas 
        ]);
    }
    public static function recuperar(Router $router) {
        //Alertas vacías
        $alertas = [];
        $error = false;

        $token = s($_GET['token']);
        //debuguear($token);
        //Buscando usuario por su token
        $usuario = Usuario::where('token', $token);
        if(empty($usuario)) {
            Usuario::setAlerta('error','Token no válido');
            $error = true;
        }

        if($_SERVER['REQUEST_METHOD'] === 'POST') {
            //leer el nuevo password y guardarlo
            $password = new Usuario($_POST);
            $alertas = $password->validarPassword();
            //debuguear($password);
            if(empty($alertas)) {
                $usuario->password = null;
                $usuario->password = $password->password;
                $usuario->hashPassword();
                $usuario->token = null;
                $resultado = $usuario->guardar();
                if($resultado) {
                    header('Location: /');
                }
            }

        }



        //debuguear($usuario);
        $alertas = Usuario::getAlertas();
        $router->render('auth/recuperar-password', [
            'alertas' => $alertas,
            'error' => $error

        ]);

    }
    public static function crear(Router $router) {
        $usuario = new Usuario;
        //Alertas vacías
        $alertas = [];
        $mensaje = '';
        if($_SERVER['REQUEST_METHOD'] === 'POST') {
            //echo "Enviaste el formulario";
           $usuario->sincronizar($_POST);
           $alertas = $usuario->validarNuevaCuenta();
           //Revisar que $alertas este vacío
           if(empty($alertas)) {
                //echo "Pasaste la validación";
                //Verificaqr que el usuario no este registrado
                $resultado = $usuario->existeUsuario();
                if($resultado->num_rows) {
                    $alertas = Usuario::getAlertas();
                } else {
                    //<no está registrado
                    $usuario->hashPassword();
                    //Generar un token único
                    $usuario->crearToken();
                    //Enviar un email
                    $email = new Email($usuario->email, $usuario->nombre, $usuario->token);
                    //debuguear($email);
                    $mensaje = $email->enviarConfirmacion();
                    //Crear el usuario
                    $resultado = $usuario->guardar();
                    if($resultado) {
                        //echo "Guardado correctamente";
                        header('Location: /mensaje');
                    }
                    
                    //debuguear($usuario);

                    
                }

           }

        }
        
        $router->render('auth/crear-cuenta', [
            'usuario'=> $usuario,
            'alertas'=> $alertas,
            'mensaje' => $mensaje
        ]);
    }

    public static function mensaje(Router $router) {
        $router->render('auth/mensaje');

    }

    public static function confirmar(Router $router) {
        $alertas = [];
        $token = s($_GET['token']);
        $usuario = Usuario::where('token', $token);
        if (empty($usuario)) {
            //Mostrar mensaje de error
            Usuario::setAlerta('error','Token no válido');
        } else {
            //Modificar a usuario confirmado
            $usuario->confirmado = "1";
            $usuario->token = null;
            $usuario->guardar();
            Usuario::setAlerta('exito','Cuenta comprobada correctamente');
        }
        //Obtener alertas 
        $alertas = Usuario::getAlertas();
        //Renderizar la vista
        $router->render('auth/confirmar-cuenta', [
            'alertas' => $alertas
        ]);
    }
}

