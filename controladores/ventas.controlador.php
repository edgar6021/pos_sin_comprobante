<?php
use Mike42\Escpos\Printer;
use Mike42\Escpos\PrintConnectors\WindowsPrintConnector;

require_once "controlador.ncf.php";

class ControladorVentas {

    static public function ctrMostrarVentas($item, $valor) {
        $tabla = "ventas";
        return ModeloVentas::mdlMostrarVentas($tabla, $item, $valor);
    }

    static public function ctrRangoFechasVentas($fechaInicial, $fechaFinal, $idVendedor = null) {
        $tabla = "ventas";
        return ModeloVentas::mdlRangoFechasVentas($tabla, $fechaInicial, $fechaFinal, $idVendedor);
    }

    static public function ctrCrearVenta() {

        if (isset($_POST["nuevaVenta"])) {

            $camposRequeridos = [
                "idVendedor", "seleccionarCliente", "nuevaVenta", "listaProductos",
                "nuevoPrecioImpuesto", "nuevoPrecioNeto", "totalVenta",
                "listaMetodoPago" 
            ];

            foreach ($camposRequeridos as $campo) {
                if (empty($_POST[$campo])) {
                    echo '<script>
                        swal({
                            type: "error",
                            title: "El campo obligatorio [' . $campo . '] está vacío.",
                            showConfirmButton: true,
                            confirmButtonText: "Cerrar"
                        }).then(function(result){
                            if (result.value) {
                                 $("#modalCrearVentas").modal("show")
                            }
                        });
                    </script>';
                    return;
                }
            }

            if ($_POST["listaProductos"] == "") {
                echo '<script>
                    swal({
                        type: "error",
                        title: "La venta no se ha ejecutado porque no hay productos",
                        showConfirmButton: true,
                        confirmButtonText: "Cerrar"
                    }).then(function(result){
                        if (result.value) {
                            $("#modalCrearVentas").modal("show")
                        }
                    });
                </script>';
                return;
            }
            

                   $rawPago = $_POST["listaMetodoPago"] ?? '[]';
        $metodoPago = json_decode($rawPago, true);

        if (json_last_error() !== JSON_ERROR_NONE || !is_array($metodoPago)) {
            echo '<script>
                swal({
                    icon: "error",
                    title: "Error al procesar los métodos de pago.",
                    text: "La información recibida no tiene el formato esperado.",
                    button: "Cerrar"
                }).then(function(){
                    $("#modalCrearVentas").modal("show");
                });
            </script>';
            return;
        }

        // Calcular total pagado
        $totalPagado = 0;
        foreach ($metodoPago as $pago) {
            if (isset($pago["monto"]) && is_numeric($pago["monto"])) {
                $totalPagado += floatval($pago["monto"]);
            }
        }

        $totalVenta = isset($_POST["totalVenta"]) && is_numeric($_POST["totalVenta"]) 
            ? floatval($_POST["totalVenta"]) 
            : 0;

        // Validar si el monto pagado es suficiente
        if ($totalPagado < $totalVenta) {
            $faltante = number_format($totalVenta - $totalPagado, 2);
            echo '<script>
                swal({
                    icon: "error",
                    title: "El monto pagado no cubre el total de la venta.",
                    text: "Faltan $' . $faltante . ' para completar el total.",
                    button: "Cerrar"
                }).then(function(){
                    $("#modalCrearVentas").modal("show");
                });
            </script>';
            return;
        }

        // EXTRAEMOS SOLO LOS TIPOS DE PAGO
        $tiposMetodoPago = array_column($metodoPago, 'tipo');
        $metodoPagoStr = implode(", ", $tiposMetodoPago);


            /*if (empty($_POST["ncf"]) && !empty($_POST["tipo_comprobante"])) {
                $_POST["ncf"] = ControladorNCF::ctrGenerarNCF($_POST["tipo_comprobante"]);
            }*/

            $listaProductos = json_decode($_POST["listaProductos"], true);
            $totalProductosComprados = [];

            foreach ($listaProductos as $value) {
                array_push($totalProductosComprados, $value["cantidad"]);
                $producto = ModeloProductos::mdlMostrarProductos("productos", "id", $value["id"], "id");

                ModeloProductos::mdlActualizarProducto("productos", "ventas", $value["cantidad"] + $producto["ventas"], $value["id"]);
                ModeloProductos::mdlActualizarProducto("productos", "stock", $value["stock"], $value["id"]);
            }

            //  Sincronizamos el valor de compras al registrar la venta
            ModeloClientes::mdlContarCompras($_POST["seleccionarCliente"]);

            
            

            date_default_timezone_set('America/Santo_Domingo');
            $fechaHora = date('Y-m-d H:i:s');
            ModeloClientes::mdlActualizarCliente("clientes", "ultima_compra", $fechaHora, $_POST["seleccionarCliente"]);

            $datos = array(
                "id_vendedor" => $_POST["idVendedor"],
                "id_cliente" => $_POST["seleccionarCliente"],
                "codigo" => $_POST["nuevaVenta"],
                "productos" => $_POST["listaProductos"],
                "impuesto" => $_POST["nuevoPrecioImpuesto"],
                "neto" => $_POST["nuevoPrecioNeto"],
                "total" => $_POST["totalVenta"],
                "metodo_pago" => $metodoPagoStr
                /*"ncf" => $_POST["ncf"],
                "tipo_comprobante" => $_POST["tipo_comprobante"]*/
            );

            $respuesta = ModeloVentas::mdlIngresarVenta("ventas", $datos);

            if ($respuesta == "ok") {
                echo '<script>
                    localStorage.removeItem("rango");
                    swal({
                        type: "success",
                        title: "La venta ha sido guardada correctamente",
                        showConfirmButton: true,
                        confirmButtonText: "Cerrar"
                    }).then(function(result){
                        if (result.value) {
                            window.location = "ventas";
                        }
                    });
                </script>';
            }
        }
    }

    static public function ctrEditarVenta() {
        if (isset($_POST["editarVenta"])) {

            $camposRequeridos = [
                "idVendedor", "seleccionarCliente", "editarVenta", "listaProductos",
                "nuevoPrecioImpuesto", "nuevoPrecioNeto", "totalVenta",
                "listaMetodoPago" /*"tipo_comprobante"*/
            ];

            foreach ($camposRequeridos as $campo) {
                if (empty($_POST[$campo])) {
                    echo '<script>
                        swal({
                            type: "error",
                            title: "El campo obligatorio [' . $campo . '] está vacío.",
                            showConfirmButton: true,
                            confirmButtonText: "Cerrar"
                        }).then(function(result){
                            if (result.value) {
                                 $("#modalCrearVentas").modal("show");
                            }
                        });
                    </script>';
                    return;
                }
            }

            if ($_POST["listaProductos"] == "") {
                echo '<script>
                    swal({
                        type: "error",
                        title: "La venta no se ha ejecutado porque no hay productos",
                        showConfirmButton: true,
                        confirmButtonText: "Cerrar"
                    }).then(function(result){
                        if (result.value) {
                             $("#modalCrearVentas").modal("show");
                        }
                    });
                </script>';
                return;
            }

            $metodoPago = json_decode($_POST["listaMetodoPago"], true);
            $totalPagado = 0;

            foreach ($metodoPago as $pago) {
                $totalPagado += floatval($pago["monto"]);
            }

            if ($totalPagado < floatval($_POST["totalVenta"])) {
                echo '<script>
                    swal({
                        type: "error",
                        title: "El monto pagado no cubre el total de la venta.",
                        showConfirmButton: true,
                        confirmButtonText: "Cerrar"
                    }).then(function(result){
                        if (result.value) {
                             $("#modalCrearVentas").modal("show");
                        }
                    });
                </script>';
                return;
            }
           
            $tiposMetodoPago = array_column($metodoPago, 'tipo');
            $metodoPagoStr = implode(", ", $tiposMetodoPago);
           
            $tabla = "ventas";
            $ventaAnterior = ModeloVentas::mdlMostrarVentas($tabla, "codigo", $_POST["editarVenta"]);
            $productosAnteriores = json_decode($ventaAnterior["productos"], true);
            if (!is_array($productosAnteriores)) {
                $productosAnteriores = [];
            }

            foreach ($productosAnteriores as $producto) {
                $productoDB = ModeloProductos::mdlMostrarProductos("productos", "id", $producto["id"], "id");
                ModeloProductos::mdlActualizarProducto("productos", "ventas", $productoDB["ventas"] - $producto["cantidad"], $producto["id"]);
                ModeloProductos::mdlActualizarProducto("productos", "stock", $productoDB["stock"] + $producto["cantidad"], $producto["id"]);
            }

            $nuevosProductos = json_decode($_POST["listaProductos"], true);
            foreach ($nuevosProductos as $producto) {
                $productoDB = ModeloProductos::mdlMostrarProductos("productos", "id", $producto["id"], "id");
                ModeloProductos::mdlActualizarProducto("productos", "ventas", $productoDB["ventas"] + $producto["cantidad"], $producto["id"]);
                ModeloProductos::mdlActualizarProducto("productos", "stock", $productoDB["stock"] - $producto["cantidad"], $producto["id"]);
            }

            $datos = array(
                "id_vendedor" => $_POST["idVendedor"],
                "id_cliente" => $_POST["seleccionarCliente"],
                "codigo" => $_POST["editarVenta"],
                "productos" => $_POST["listaProductos"],
                "impuesto" => $_POST["nuevoPrecioImpuesto"],
                "neto" => $_POST["nuevoPrecioNeto"],
                "total" => $_POST["totalVenta"],
                "metodo_pago" => $metodoPagoStr,
                /*"ncf" => $_POST["ncf"],
                "tipo_comprobante" => $_POST["tipo_comprobante"]*/
            );

            $respuesta = ModeloVentas::mdlEditarVenta($tabla, $datos);

            if ($respuesta == "ok") {
                echo '<script>
                    swal({
                        type: "success",
                        title: "La venta ha sido editada correctamente",
                        showConfirmButton: true,
                        confirmButtonText: "Cerrar"
                    }).then(function(result){
                        if (result.value) {
                            window.location = "ventas";
                        }
                    });
                </script>';
            }
        }
    }

    static public function ctrEliminarVenta() {
        if (isset($_GET["idVenta"])) {
            $venta = ModeloVentas::mdlMostrarVentas("ventas", "id", $_GET["idVenta"]);
            $productos = json_decode($venta["productos"], true);
           if(!is_array($productos)){
              $productos= [];
           }
            foreach ($productos as $producto) {
                $productoDB = ModeloProductos::mdlMostrarProductos("productos", "id", $producto["id"], "id");

                ModeloProductos::mdlActualizarProducto("productos", "ventas", $productoDB["ventas"] - $producto["cantidad"], $producto["id"]);
                ModeloProductos::mdlActualizarProducto("productos", "stock", $productoDB["stock"] + $producto["cantidad"], $producto["id"]);
            }

           // 🔄 Sincronizamos el valor de compras al eliminar la venta
            ModeloClientes::mdlContarCompras($venta["id_cliente"]);

           
           

            $respuesta = ModeloVentas::mdlEliminarVenta("ventas", $_GET["idVenta"]);

            if ($respuesta == "ok") {
                echo '<script>
                    swal({
                        type: "success",
                        title: "La venta ha sido eliminada correctamente",
                        showConfirmButton: true,
                        confirmButtonText: "Cerrar"
                    }).then(function(result){
                        if (result.value) {
                            window.location = "ventas";
                        }
                    });
                </script>';
            }
        }
    }

    public function ctrDescargarReporte() {
        if (isset($_GET["reporte"])) {
            $ventas = (isset($_GET["fechaInicial"]) && isset($_GET["fechaFinal"])) ?
                ModeloVentas::mdlRangoFechasVentas("ventas", $_GET["fechaInicial"], $_GET["fechaFinal"]) :
                ModeloVentas::mdlMostrarVentas("ventas", null, null);

            header("Content-type: application/vnd.ms-excel");
            header("Content-Disposition: attachment; filename=reporte_ventas.xls");

            echo mb_convert_encoding("<table border='1'>
                <thead>
                    <tr>
                        <th>Código</th><th>Cliente</th><th>Vendedor</th><th>Productos</th>
                        <th>Impuesto</th><th>Neto</th><th>Total</th><th>Pago</th><th>Fecha</th>
                    </tr>
                </thead><tbody>", 'ISO-8859-1', 'UTF-8');

            foreach ($ventas as $venta) {
                $cliente = ControladorClientes::ctrMostrarClientes("id", $venta["id_cliente"]);
                $vendedor = ControladorUsuarios::ctrMostrarUsuarios("id", $venta["id_vendedor"]);
                $productos = json_decode($venta["productos"], true);

                echo "<tr>
                    <td>{$venta['codigo']}</td>
                    <td>{$cliente['nombre']}</td>
                    <td>{$vendedor['nombre']}</td>
                    <td>";

                foreach ($productos as $p) {
                    echo $p["descripcion"] . " x" . $p["cantidad"] . "<br>";
                }

                echo "</td>
                    <td>$ {$venta['impuesto']}</td>
                    <td>$ {$venta['neto']}</td>
                    <td>$ {$venta['total']}</td>
                    <td>{$venta['metodo_pago']}</td>
                    <td>{$venta['fecha']}</td>
                </tr>";
            }

            echo "</tbody></table>";
        }
    }

    public function ctrSumaTotalVentas() {
        return ModeloVentas::mdlSumaTotalVentas("ventas");
    }

    static public function ctrDescargarXML() {
        if (isset($_GET["xml"])) {
            $venta = ModeloVentas::mdlMostrarVentas("ventas", "codigo", $_GET["xml"]);
            $productos = json_decode($venta["productos"], true);

            $xml = new XMLWriter();
            $xml->openURI($_GET["xml"] . ".xml");
            $xml->setIndent(true);
            $xml->startDocument('1.0', 'UTF-8');
            $xml->writeRaw('<fe:Invoice xmlns:fe="http://www.dian.gov.co/contratos/facturaelectronica/v1">');
            $xml->writeRaw('<ext:UBLExtensions>');

            foreach ($productos as $p) {
                $xml->text($p["descripcion"] . ", ");
            }

            /*$xml->writeElement("cbc:ID", $venta["ncf"]);
            $xml->writeElement("cbc:InvoiceTypeCode", $venta["tipo_comprobante"]);*/

            $xml->writeRaw('</ext:UBLExtensions>');
            $xml->writeRaw('</fe:Invoice>');
            $xml->endDocument();

            return true;
        }
    }
}
