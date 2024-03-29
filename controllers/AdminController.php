<?php

namespace Controllers;
use Model\AdminCita;
use MVC\Router;


class AdminController {
    public static function index ( Router $router) {

        isAdmin();
        
        $fecha = $_GET['fecha'] ?? date('Y-m-d');
        $fechas = explode('-', $fecha);
        //debuguear($fecha);

        if ( !checkdate($fechas[1], $fechas[2], $fechas[0])) {
            header('Location: /404');
        }

        //debuguear($fecha);
        //Consultar la base de datos
        $consulta = "SELECT citas.id, CONCAT(citas.fecha, ' ',citas.hora) as hora, CONCAT( usuarios.nombre, ' ', usuarios.apellidos) as cliente, ";
        $consulta .= " usuarios.email, usuarios.telefono, servicios.nombre as servicio, servicios.precio  ";
        $consulta .= " FROM citas  ";
        $consulta .= " LEFT OUTER JOIN usuarios ";
        $consulta .= " ON citas.usuarioId=usuarios.id  ";
        $consulta .= " LEFT OUTER JOIN citasServicios ";
        $consulta .= " ON citasServicios.citaId=citas.id ";
        $consulta .= " LEFT OUTER JOIN servicios ";
        $consulta .= " ON servicios.id=citasServicios.servicioId ";
        $consulta .= " WHERE fecha =  '{$fecha}' ";
        //debuguear($consulta);
        $citas = AdminCita::SQL($consulta);
       // debuguear($citas);
        $router ->render('admin/index', [
            'nombre' => $_SESSION['nombre'],
            'citas' => $citas,
            'fecha' => $fecha
        ]);

    }
}


