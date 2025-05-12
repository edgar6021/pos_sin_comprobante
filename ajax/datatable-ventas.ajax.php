<?php

require_once "../controladores/productos.controlador.php";
require_once "../modelos/productos.modelo.php";

class TablaProductosVentas {

    public function mostrarTablaProductosVentas() {

        $item = null;
        $valor = null;
        $orden = "id";

        $productos = ControladorProductos::ctrMostrarProductos($item, $valor, $orden);

        if (count($productos) == 0) {
            echo json_encode(["data" => []]);
            return;
        }

        $datos = [];

        foreach ($productos as $index => $producto) {
            // Imagen del producto
            $imagen = "<img src='" . htmlspecialchars($producto["imagen"]) . "' width='40px'>";

            // Stock con color
            $stock = (int)$producto["stock"];
            if ($stock <= 10) {
                $stockHtml = "<button class='btn btn-danger'>{$stock}</button>";
            } elseif ($stock > 11 && $stock <= 15) {
                $stockHtml = "<button class='btn btn-primary'>{$stock}</button>";
            } else {
                $stockHtml = "<button class='btn btn-success'>{$stock}</button>";
            }

            // Botón de acción
            $botones = "<div class='btn-group'><button class='btn btn-primary agregarProducto recuperarBoton' idProducto='" . $producto["id"] . "'>Agregar</button></div>";

            // Push al array principal
            $datos[] = [
                ($index + 1),
                $imagen,
                htmlspecialchars($producto["codigo"]),
                htmlspecialchars($producto["descripcion"]),
                $stockHtml,
                $botones
            ];
        }

        echo json_encode(["data" => $datos], JSON_UNESCAPED_UNICODE);
    }
}

// Activar tabla
$activarProductosVentas = new TablaProductosVentas();
$activarProductosVentas->mostrarTablaProductosVentas();
