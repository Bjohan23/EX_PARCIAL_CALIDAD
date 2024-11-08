<?php
namespace tests;

use PHPUnit\Framework\TestCase;
use src\Pedido;
use src\Producto;
use mysqli;

class PedidoTest extends TestCase {
    private $connection;
    private $pedidoInstance;
    private $productoInstance;

    protected function setUp(): void {
        $this->connection = new mysqli("localhost", "root", "", "examen_parcial_calidad");
        // Comprobar la conexión
        if ($this->connection->connect_error) {
            die("Error de conexión: " . $this->connection->connect_error);
        }

        $this->pedidoInstance = new Pedido($this->connection);
        $this->productoInstance = new Producto($this->connection);
    }

    // Test para registrar un pedido con un producto ya existente
    public function testAddOrderWithExistingProduct() {
        // Añadir un producto
        $this->productoInstance->registrar("Producto_Test_Pedido", 12.99);
        $productId = $this->connection->insert_id;

        // Añadir un pedido con el producto existente
        $result = $this->pedidoInstance->registrar($productId);
        echo "Resultado de registrar pedido con producto existente: " . ($result ? "true" : "false") . "\n";
        $this->assertTrue($result, "El pedido debería haberse añadido correctamente.");
    }

    // Test para intentar eliminar un producto que está asociado a un pedido
    public function testRemoveProductFromOrder() {
        // Añadir un producto y un pedido
        $this->productoInstance->registrar("Producto_Test_Eliminar", 15.99);
        $productId = $this->connection->insert_id;
        $this->pedidoInstance->registrar($productId);
        $orderId = $this->connection->insert_id; // Obtener ID del pedido registrado

        // Intentar eliminar el producto
        try {
            $result = $this->productoInstance->eliminar($productId); // Método que debería fallar
            echo "Resultado de intentar eliminar producto asociado a pedido: " . ($result ? "true" : "false") . "\n";
            $this->assertFalse($result, "El producto no debería poder eliminarse mientras esté asociado a un pedido.");
        } catch (\mysqli_sql_exception $e) {
            echo "Excepción al intentar eliminar producto asociado a pedido: " . $e->getMessage() . "\n";
            $this->assertStringContainsString('Cannot delete or update a parent row', $e->getMessage());
        }

        // Limpiar: primero elimina el pedido
        $this->pedidoInstance->eliminar($orderId); // Eliminar el pedido

        // Luego intenta eliminar el producto
        $result = $this->productoInstance->eliminar($productId);
        echo "Resultado de eliminar producto después de eliminar pedido: " . ($result ? "true" : "false") . "\n";
        $this->assertTrue($result, "El producto debería haberse eliminado correctamente.");
    }

    // Test para eliminar un pedido
    public function testRemoveOrder() {
        // Añadir un producto y un pedido
        $this->productoInstance->registrar("Producto_Test_Pedido_Eliminar", 19.99);
        $productId = $this->connection->insert_id;
        $this->pedidoInstance->registrar($productId);
        $orderId = $this->connection->insert_id; // Obtener ID del pedido registrado

        // Eliminar el pedido
        $result = $this->pedidoInstance->eliminar($orderId);
        echo "Resultado de eliminar pedido: " . ($result ? "true" : "false") . "\n";
        $this->assertTrue($result, "El pedido debería haberse eliminado correctamente.");
    }

    protected function tearDown(): void {
        // Cerrar la conexión
        $this->connection->close();
    }
}
