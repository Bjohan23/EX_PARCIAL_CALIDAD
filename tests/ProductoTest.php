<?php
namespace tests;

use PHPUnit\Framework\TestCase;
use src\Producto;
use mysqli;

class ProductoTest extends TestCase {
    private $dbConnection;
    private $product;

    protected function setUp(): void {
        // Conectar a la base de datos
        $this->dbConnection = new mysqli("localhost", "root", "", "examen_parcial_calidad");
        if ($this->dbConnection->connect_error) {
            die("Connection failed: " . $this->dbConnection->connect_error);
        }
        // Inicializar la clase Producto
        $this->product = new Producto($this->dbConnection);
    }

    public function testRegisterProduct() {
        // Probar el registro de un producto
        $result = $this->product->registrar("gaseosa", 10.99);
        echo "Resultado de registrar producto: " . ($result ? "true" : "false") . "\n"."Nombre del producto registrado: gaseosa\n";
        $this->assertTrue($result, "Producto registrado exitosamente.");
    }

    public function testUpdateProduct() {
        // Registrar un producto para asegurarse de que existe
        $this->product->registrar("agua", 12.99);
        $lastProductId = $this->dbConnection->insert_id; // Obtener el ID del producto recién registrado
        echo "ID del producto a modificar: " . $lastProductId . "\n" ,"Nombre del producto a modificar: agua\n";
        // Modificar el producto registrado
        $result = $this->product->modificar($lastProductId, "Agua carbonatada", 15.99);
        echo "Resultado de modificar producto: " . ($result ? "true" : "false") . "\n" . "Nombre del producto modificado: Agua carbonatada\n";
        $this->assertTrue($result, "Producto modificado exitosamente.");
    }

    public function testDeleteProduct() {
        // Registrar un producto para asegurarse de que existe
        $this->product->registrar("yogur", 9.99);
        $lastProductId = $this->dbConnection->insert_id; // Obtener el ID del producto recién registrado
        echo "ID del producto a eliminar: " . $lastProductId . "\n". "Nombre del producto a eliminar: yogur\n";

        // Eliminar el producto registrado
        $result = $this->product->eliminar($lastProductId);
        echo "Resultado de eliminar producto: " . ($result ? "true" : "false") . "\n". "Producto eliminado exitosamente.\n";
        $this->assertTrue($result, "Producto eliminado exitosamente.");
    }

    protected function tearDown(): void {
        // Cerrar la conexión a la base de datos
        $this->dbConnection->close();
    }
}
