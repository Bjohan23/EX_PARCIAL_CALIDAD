<?php
namespace src;

use mysqli;

class Producto {
    private $conexion;

    public function __construct($conexion) {
        $this->conexion = $conexion;
    }

    public function registrar($nombre, $precio) {
        $sql = "INSERT INTO producto (nombre, precio) VALUES (?, ?)";
        $stmt = $this->conexion->prepare($sql);
        $stmt->bind_param("sd", $nombre, $precio);
        return $stmt->execute();
    }

    public function modificar($id, $nombre, $precio) {
        $sql = "UPDATE producto SET nombre = ?, precio = ? WHERE id = ?";
        $stmt = $this->conexion->prepare($sql);
        $stmt->bind_param("sdi", $nombre, $precio, $id);
        return $stmt->execute();
    }

    public function eliminar($id) {
        $sql = "DELETE FROM producto WHERE id = ?";
        $stmt = $this->conexion->prepare($sql);
        $stmt->bind_param("i", $id);
        return $stmt->execute();
    }
}
