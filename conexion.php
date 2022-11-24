<?php

class Conexion
{
    private $servidor = 'localhost';
    private $usuario = 'root';
    private $clave = '';
    private $bd = 'super';
    private $conexion;

    public function __construct()
    {
        try {
            $this->conexion = new PDO("mysql:host=".$this->servidor.";dbname=".$this->bd, $this->usuario, $this->clave);
            $this->conexion->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
        } catch (PDOException $e) {
            echo "Ha ocurrido un error. ERROR: " . $e->getMessage();
        }
    }

    public function ejecutar($sql) { //insertar/eliminar/actualizar
        $this->conexion->exec($sql);
        return $this->conexion->lastInsertId();
    }

    public function seleccionar($sql) { //seleccionar varios
        $sentencia = $this->conexion->prepare($sql);
        $sentencia->execute();
        return $sentencia->fetchAll();
    }

    public function consultar($sql) { //seleccionar un registro
        $sentencia = $this->conexion->prepare($sql);
        $sentencia->execute();
        return $sentencia->fetch();
    }
    
}

?>