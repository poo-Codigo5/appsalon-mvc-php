<?php

namespace Model;

class Servicio extends ActiveRecord {
    // Base de datos
    protected static $tabla = 'servicios';
    protected static $columnasDB = ['id', 'nombre', 'precio'];

    public $id;
    public $nombre;
    public $precio;

    public function __construct($args = [])
    {
        $this->id = $args['id'] ?? null;
        $this->nombre = $args['nombre'] ?? '';
        $this->precio = $args['precio'] ?? '';
    }

    public function validar()
    {
        if(!$this->nombre) { //no hay nombre o vacío
            self::$alertas['error'][] = 'El nombre del servicio es obligatorio';
        }
        
        if(!$this->precio) { //no hay precio o vacío
            self::$alertas['error'][] = 'El precio del servicio es obligatorio';
        }
        if(!is_numeric($this->precio)) { //no es numérico
            self::$alertas['error'][] = 'El precio no es válido';
        }

        return self::$alertas;
    }
}