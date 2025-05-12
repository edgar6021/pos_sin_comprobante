<?php

require_once "../../../controladores/ventas.controlador.php";
require_once "../../../modelos/ventas.modelo.php";
require_once "../../../controladores/clientes.controlador.php";
require_once "../../../modelos/clientes.modelo.php";
require_once "../../../controladores/usuarios.controlador.php";
require_once "../../../modelos/usuarios.modelo.php";
require_once "../../../controladores/productos.controlador.php";
require_once "../../../modelos/productos.modelo.php";
require_once 'tcpdf_include.php';

class ImprimirFacturaTicket {
    public $codigo;

    public function generarFactura() {
        $venta = $this->obtenerVenta();
        if (!$venta) die("Venta no encontrada.");

        $cliente = $this->obtenerCliente($venta['id_cliente']);
        $vendedor = $this->obtenerVendedor($venta['id_vendedor']);
        $productos = $this->obtenerProductos($venta['productos']);
        $detallesTotales = $this->calcularTotales($venta);

        $pdf = new TCPDF('P', 'mm', [80, 220], true, 'UTF-8', false);
        $pdf->SetCreator(PDF_CREATOR);
        $pdf->SetAuthor('Techeande');
        $pdf->SetTitle('Factura N. ' . $venta['codigo']);
        $pdf->SetSubject('Factura de Compra');
        $pdf->SetMargins(5, 5, 5);
        $pdf->SetAutoPageBreak(true, 5);
        $pdf->AddPage();

        $this->agregarEncabezado($pdf, $venta['codigo']);
        $this->agregarInformacionCliente($pdf, $cliente, $vendedor, $venta['fecha']);
        $this->agregarDetalleProductos($pdf, $productos);
        $this->agregarTotales($pdf, $detallesTotales);
        $this->agregarCodigoQR($pdf, $venta, $cliente, $detallesTotales);
        $this->agregarNotaFinal($pdf);

        $pdf->Output('factura_ticket_' . $venta['codigo'] . '.pdf', 'I');
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
            if ($datosProducto) {
                $producto['precio_unitario'] = number_format($datosProducto['precio_venta'], 2);
                $producto['total'] = number_format($producto['cantidad'] * $datosProducto['precio_venta'], 2);
            } else {
                $producto['precio_unitario'] = "0.00";
                $producto['total'] = "0.00";
            }
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

    private function agregarEncabezado($pdf, $numeroFactura) {
        $html = <<<HTML
        <table style="text-align: center; width: 100%; background-color: #d8eaff; padding: 5px;">
            <tr>
                <td>
                    <img src="./images/techeande.png" width="50" alt="Techeande" /><br>
                    <strong style="font-size: 10px;">Techeande</strong><br>
                    <small style="font-size: 8px;">RNC: 71759963-9 | La Guayiga Km20 | Tel: 829-380-8296</small>
                </td>
            </tr>
        </table>
        <hr style="border: 0.5px solid #4CAF50;">
        <h3 style="text-align: center; font-size: 12px; margin: 5px 0;">
            FACTURA N. $numeroFactura
        </h3>
HTML;
        $pdf->writeHTML($html, false, false, false, false, '');
    }

    private function agregarInformacionCliente($pdf, $cliente, $vendedor, $fecha) {
        $fechaFormateada = date('d-m-Y', strtotime($fecha));
        $html = <<<HTML
        <table style="font-size: 8px; width: 100%; margin-top: 5px;">
            <tr>
                <td>
                    <strong>Cliente:</strong> {$cliente['nombre']}<br>
                    <strong>RNC:</strong> {$cliente['documento']}
                </td>
                <td style="text-align: right;">
                    <strong>Vendedor:</strong> {$vendedor['nombre']}<br>
                    <strong>Fecha:</strong> $fechaFormateada
                </td>
            </tr>
        </table>
HTML;
        $pdf->writeHTML($html, false, false, false, false, '');
    }

    private function agregarDetalleProductos($pdf, $productos) {
        $html = <<<HTML
        <table style="font-size: 8px; width: 100%; margin-top: 5px;">
            <thead>
                <tr style="border-bottom: 1px solid #000; background-color:rgb(50, 133, 193); color:white;">
                    <th>Producto</th>
                    <th style="text-align: right;">Cant</th>
                    <th style="text-align: right;">P/U</th>
                    <th style="text-align: right;">Total</th>
                </tr>
            </thead>
            <tbody>
HTML;
        foreach ($productos as $producto) {
            $html .= <<<HTML
                <tr>
                    <td>{$producto['descripcion']}</td>
                    <td style="text-align: right;">{$producto['cantidad']}</td>
                    <td style="text-align: right;">{$producto['precio_unitario']}</td>
                    <td style="text-align: right;">{$producto['total']}</td>
                </tr>
HTML;
        }
        $html .= "</tbody></table>";
        $pdf->writeHTML($html, false, false, false, false, '');
    }

    private function agregarTotales($pdf, $totales) {
        $html = <<<HTML
        <table style="font-size: 9px; width: 100%; margin-top: 5px; text-align: right;">
            <tr>
                <td>Subtotal:</td>
                <td>$ {$totales['neto']}</td>
            </tr>
            <tr>
                <td>ITBIS:</td>
                <td>$ {$totales['impuesto']}</td>
            </tr>
            <tr style="font-weight: bold;">
                <td>Total:</td>
                <td>$ {$totales['total']}</td>
            </tr>
        </table>
HTML;
        $pdf->writeHTML($html, false, false, false, false, '');
    }

    private function agregarCodigoQR($pdf, $venta, $cliente, $totales) {
        $pdf->SetY($pdf->GetY() + 10);
        $qrData = "Factura: {$venta['codigo']}\nCliente: {$cliente['nombre']}\nTotal: {$totales['total']}";
        $pdf->write2DBarcode($qrData, 'QRCODE,H', 30, $pdf->GetY(), 18, 18, [], 'N');
    }

    private function agregarNotaFinal($pdf) {
        $pdf->SetY($pdf->GetY() + 2);
        $html = <<<HTML
        <div style="text-align: center; font-size: 8px;">
            <strong>¡Gracias por su compra!</strong><br>
            Esta factura es válida como comprobante fiscal<br>
            conforme a la normativa vigente.
        </div>
HTML;
        $pdf->writeHTML($html, false, false, false, false, '');
    }
}

if (isset($_GET["codigo"]) && !empty($_GET["codigo"])) {
    $factura = new ImprimirFacturaTicket();
    $factura->codigo = htmlspecialchars($_GET["codigo"]);
    $factura->generarFactura();
} else {
    die("Error: código de factura no especificado.");
}
