<?php
namespace src;

use mysqli;

class Pedido {
    private $conexion;

    public function __construct($conexion) {
        $this->conexion = $conexion;
    }

    public function registrar($idproducto) {
        $sql = "INSERT INTO detallepedido (idproducto) VALUES (?)";
        $stmt = $this->conexion->prepare($sql);
        $stmt->bind_param("i", $idproducto);
        return $stmt->execute();
    }

    public function eliminar($id) {
        $sql = "DELETE FROM detallepedido WHERE iddetalle = ?";
        $stmt = $this->conexion->prepare($sql);
        $stmt->bind_param("i", $id);
        return $stmt->execute();
    }
}
