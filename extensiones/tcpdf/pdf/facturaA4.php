<?php

require_once "../../../controladores/ventas.controlador.php";
require_once "../../../modelos/ventas.modelo.php";
require_once "../../../controladores/clientes.controlador.php";
require_once "../../../modelos/clientes.modelo.php";
require_once "../../../controladores/usuarios.controlador.php";
require_once "../../../modelos/usuarios.modelo.php";
require_once "../../../controladores/productos.controlador.php";
require_once "../../../modelos/productos.modelo.php";
require_once __DIR__ . '/../../../extensiones/tcpdf/tcpdf.php';

class ImprimirFacturaA4 {
    public $codigo;

    public function generarFactura() {
        $venta = $this->obtenerVenta();
        if (!$venta) {
            die("🚫 Error: Venta no encontrada.");
        }

        $cliente = $this->obtenerCliente($venta['id_cliente']);
        $vendedor = $this->obtenerVendedor($venta['id_vendedor']);
        $productos = $this->obtenerProductos($venta['productos']);
        $detallesTotales = $this->calcularTotales($venta);

        $pdf = new TCPDF('P', 'mm', 'A4', true, 'UTF-8', false);
        $pdf->SetCreator(PDF_CREATOR);
        $pdf->SetAuthor('Techeande');
        $pdf->SetTitle('Factura N. ' . $venta['codigo']);
        $pdf->SetSubject('Factura de Compra');
        $pdf->SetMargins(15, 20, 15);
        $pdf->SetAutoPageBreak(true, 20);
        $pdf->AddPage();

        $this->agregarEncabezado($pdf, $venta, $cliente, $vendedor);
        $this->agregarInformacionCliente($pdf, $cliente, $vendedor, $venta['fecha']);
        $this->agregarDetalleProductos($pdf, $productos);
        $this->agregarTotales($pdf, $detallesTotales);
        $this->agregarPiePagina($pdf);

        $pdf->Output('factura_' . $venta['codigo'] . '.pdf', 'I');
    }

    private function obtenerVenta() {
        return ControladorVentas::ctrMostrarVentas("codigo", $this->codigo);
    }

    private function obtenerCliente($idCliente) {
        return ControladorClientes::ctrMostrarClientes("id", $idCliente);
    }

    private function obtenerVendedor($idVendedor) {
        return ControladorUsuarios::ctrMostrarUsuarios("id", $idVendedor);
    }

    private function obtenerProductos($productosJson) {
        $productos = json_decode($productosJson, true);
        foreach ($productos as &$producto) {
            $datosProducto = ControladorProductos::ctrMostrarProductos("descripcion", $producto['descripcion'], null);
            $producto['precio_unitario'] = number_format($datosProducto['precio_venta'], 2);
        }
        return $productos;
    }

    private function calcularTotales($venta) {
        return [
            'neto' => number_format($venta['neto'], 2),
            'impuesto' => number_format($venta['impuesto'], 2),
            'total' => number_format($venta['total'], 2),
        ];
    }

    private function agregarEncabezado($pdf, $venta, $cliente, $vendedor) {
        $fechaFormateada = date('d-m-Y', strtotime($venta['fecha']));
        $html = <<<HTML
        <div style="text-align: center;">
            <img src="./images/techeande.png" width="100">
            <h2 style="color: #007bff;">Techeande</h2>
            <p>
                RNC: 71759963-9<br>
                La Guayiga Km20 Autosmallista Duarte Vieja<br>
                Teléfono: 829-380-8296<br>
                techeande@gmail.com
            </p>
            <hr>
        </div>
HTML;
        $pdf->writeHTML($html, false, false, false, false, '');
    }

    private function agregarInformacionCliente($pdf, $cliente, $vendedor, $fecha) {
        $fechaFormateada = date('d-m-Y', strtotime($fecha));
        $html = <<<HTML
        <table style="font-size: 12px; margin-top: 20px; width: 100%; border:1px;" cellpadding="6">
            <tr>
                <td><strong>Cliente:</strong> {$cliente['nombre']}<br><strong>RNC:</strong> {$cliente['documento']}<br><strong>Dirección:</strong> {$cliente['direccion']}</td>
                <td><strong>Vendedor:</strong> {$vendedor['nombre']}<br><strong>Fecha:</strong> $fechaFormateada</td>
            </tr>
        </table>
HTML;
        $pdf->writeHTML($html, false, false, false, false, '');
    }

    private function agregarDetalleProductos($pdf, $productos) {
        $html = "<table border='1' style='font-size: 12px; margin-top: 20px;' cellpadding='6'>";
        $html .= "<thead style='background-color: #007bff; color: white;'><tr><th>Producto</th><th>Cantidad</th><th>Precio Unitario</th><th>Total</th></tr></thead><tbody>";
        foreach ($productos as $producto) {
            $html .= "<tr><td>{$producto['descripcion']}</td><td>{$producto['cantidad']}</td><td>$ {$producto['precio_unitario']}</td><td>$ {$producto['total']}</td></tr>";
        }
        $html .= "</tbody></table>";
        $pdf->writeHTML($html, false, false, false, false, '');
    }

    private function agregarTotales($pdf, $totales) {
        $html = <<<HTML
        <table style="font-size: 12px; margin-top: 20px; text-align: right;">
            <tr><td>Subtotal: $ {$totales['neto']}</td></tr>
            <tr><td>Impuesto: $ {$totales['impuesto']}</td></tr>
            <tr><td><strong>Total: $ {$totales['total']}</strong></td></tr>
        </table>
HTML;
        $pdf->writeHTML($html, false, false, false, false, '');
    }

    private function agregarPiePagina($pdf) {
        $html = <<<HTML
        <hr>
        <div style="text-align: center; font-size: 10px;">
            <p>Gracias por su compra!</p>
            <p>Documento válido conforme a la normativa vigente.</p>
            <p>Soporte técnico: 829-380-8296 | Email: soporte@techeande.com</p>
        </div>
HTML;
        $pdf->writeHTML($html, false, false, false, false, '');
    }
}

if (isset($_GET["codigo"]) && !empty($_GET["codigo"])) {
    $factura = new ImprimirFacturaA4();
    $factura->codigo = htmlspecialchars($_GET["codigo"]);
    $factura->generarFactura();
} else {
    die("Error: código de factura no especificado.");
}
