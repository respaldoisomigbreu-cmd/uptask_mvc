<?php

namespace Model;

use Model\ActiveRecord;


class Proyecto extends ActiveRecord {
    protected static $tabla = 'proyectos';
    protected static $columnasDB = ['id',
                                    'proyecto',
                                    'url',
                                    'propietarioId'
                                    ]; 

    public ?int $id; // El ? permite que sea null al principio
    public string $proyecto;
    public string $url;
    public string $propietarioId;
    
    public function __construct($args = [])
    {
        $this->id = $args['id'] ?? null;
        $this->proyecto = $args['proyecto'] ?? '';
        $this->url = $args['url'] ?? '';
        $this->propietarioId = $args['propietarioId'] ?? '';    
    }

    public function validarProyecto(){
        if(!$this->proyecto){
            self::$alertas['error'][] = 'El Nombre del Proyecto es Obligatorio';
        }
        return self::$alertas;
    }

}    