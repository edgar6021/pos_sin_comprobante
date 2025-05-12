<?php

use BcMath\Number;

require_once "../../../controladores/ventas.controlador.php";
require_once "../../../modelos/ventas.modelo.php";
require_once "../../../controladores/clientes.controlador.php";
require_once "../../../modelos/clientes.modelo.php";
require_once "../../../controladores/usuarios.controlador.php";
require_once "../../../modelos/usuarios.modelo.php";
require_once "../../../controladores/productos.controlador.php";
require_once "../../../modelos/productos.modelo.php";
require_once 'tcpdf_include.php';

class ImprimirFactura {
    public $codigo;

    public function generarFactura() {
        $venta = $this->obtenerVenta();
        if (!$venta) die("Error: Venta no encontrada.");

        $cliente = $this->obtenerCliente($venta['id_cliente']);
        $vendedor = $this->obtenerVendedor($venta['id_vendedor']);
        $productos = $this->obtenerProductos($venta['productos']);
        $detallesTotales = $this->calcularTotales($venta);

        $pdf = new TCPDF(PDF_PAGE_ORIENTATION, PDF_UNIT, PDF_PAGE_FORMAT, true, 'UTF-8', false);
        $pdf->SetCreator(PDF_CREATOR);
        $pdf->SetAuthor('Techeande');
        $pdf->SetTitle('Factura N. ' . $venta['codigo']);
        $pdf->SetSubject('Factura de Compra');
        $pdf->SetMargins(15, 20, 15);
        $pdf->SetAutoPageBreak(TRUE, 10);
        $pdf->AddPage();

        $this->agregarEncabezado($pdf, $venta['codigo']/* $venta['ncf'], $venta['tipo_comprobante']*/);
        $this->agregarInformacionCliente($pdf, $cliente, $vendedor, $venta['fecha']);
        $this->agregarDetalleProductos($pdf, $productos);
        $this->agregarTotales($pdf, $detallesTotales);
        $this->agregarCodigoQR($pdf, $venta, $cliente, $detallesTotales);
        $this->agregarNotaFinal($pdf);
        /*$this->agregarFirmaDGII($pdf, $venta);*/

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
            if ($datosProducto) {
                $producto['precio_unitario'] = number_format($datosProducto['precio_venta'], 2);
                $producto['total'] = number_format($producto['cantidad'] * $datosProducto['precio_venta'], 2);
            } else {
                $producto['precio_unitario'] = number_format(0, 2);
                $producto['total'] = number_format(0, 2);
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

    private function agregarEncabezado($pdf, $numeroFactura /*$ncf, $tipoComprobante*/) {
        $html = <<<HTML
        <table>
            <tr>
                <td style="width: 150px;">
                    <img src="./images/techeande.png" width="150" />
                </td>
                <td style="text-align: center;">
                    <h1 style="color: #4CAF50;">Techeande</h1>
                    <small>RNC: 71759963-9</small><br>
                    <small>La Guayiga Km20 Autosmallista Duarte Vieja</small><br>
                    <small>Teléfono: 829-380-8296</small><br>
                    <small>techeande@gmail.com</small>
                </td>
            </tr>
        </table>
        <hr style="border: 2px solid #4CAF50; margin-top: 10px;">
        <h3 style="text-align: center; background-color: #f1f1f1;">FACTURA N. $numeroFactura</h3>
      <!--  <table style="width: 100%; font-size: 12px;">
            <tr>
                <td><strong>NCF:</strong>//</td>
                <td><strong>Tipo de Comprobante:</strong> </td>
            </tr>
        </table>-->
HTML;
        $pdf->writeHTML($html, false, false, false, false, '');
    }

    private function agregarInformacionCliente($pdf, $cliente, $vendedor, $fecha) {
        $fechaFormateada = date('d-m-Y', strtotime($fecha));
        $html = <<<HTML
        <table style="font-size: 12px; margin-top: 20px;" border="1" cellpadding="6">
            <tr>
                <td><strong>Cliente:</strong> {$cliente['nombre']}<br><strong>RNC:</strong> {$cliente['documento']}<br><strong>Dirección:</strong> {$cliente['direccion']}</td>
                <td><strong>Vendedor:</strong> {$vendedor['nombre']}<br><strong>Fecha:</strong> $fechaFormateada</td>
            </tr>
        </table>
HTML;
        $pdf->writeHTML($html, false, false, false, false, '');
    }

    private function agregarDetalleProductos($pdf, $productos) {
        $html = <<<HTML
        <table border="1" style="font-size: 12px; margin-top: 20px;" cellpadding="6">
            <thead style="background-color: #4CAF50; color: white;">
                <tr>
                    <th>Producto</th>
                    <th>Cantidad</th>
                    <th>Precio Unitario</th>
                    <th>Total</th>
                </tr>
            </thead>
            <tbody>
HTML;
        foreach ($productos as $producto) {
            $html .= <<<HTML
                <tr>
                    <td>{$producto['descripcion']}</td>
                    <td>{$producto['cantidad']}</td>
                    <td>$ {$producto['precio_unitario']}</td>
                    <td>$ {$producto['total']}</td>
                </tr>
HTML;
        }
        $html .= "</tbody></table>";
        $pdf->writeHTML($html, false, false, false, false, '');
    }

    private function agregarTotales($pdf, $totales) {
        $html = <<<HTML
        <table style="font-size: 12px; margin-top: 20px; text-align: right;">
            <tr><td>Subtotal: $ {$totales['neto']}</td></tr>
            <tr><td>ITBIS:18% $ {$totales['impuesto']}</td></tr>
            <tr><td><strong>Total: $ {$totales['total']}</strong></td></tr>
        </table>
HTML;
        $pdf->writeHTML($html, false, false, false, false, '');
    }

    private function agregarCodigoQR($pdf, $venta, $cliente, $totales) {
        $qrData = "Factura: {$venta['codigo']}\n Cliente: {$cliente['nombre']}\nTotal: {$totales['total']}";
        $pdf->write2DBarcode($qrData, 'QRCODE,H', 150, 220, 30, 30, [], 'N');
    }

    private function agregarNotaFinal($pdf) {
        $html = <<<HTML
        <div style="text-align: center; font-size: 10px; margin-top: 10px;">
            <strong>¡Gracias por su compra!</strong><br>
            Esta factura es válida como comprobante fiscal conforme a la normativa de la DGII.<br>
        </div>
HTML;
        $pdf->writeHTML($html, false, false, false, false, '');
    }

    /*private function agregarFirmaDGII($pdf, $venta) {
        $pdf->Ln(10);
        $pdf->SetFont('helvetica', 'I', 8);
        $pdf->MultiCell(0, 10, "Documento firmado digitalmente por Techeande\nAutorización DGII - NCF: {$venta['ncf']}", 0, 'C');
    }*/
}

// Validación del parámetro 'codigo'
if (isset($_GET["codigo"]) && !empty($_GET["codigo"])) {
    $factura = new ImprimirFactura();
    $factura->codigo = htmlspecialchars($_GET["codigo"]);
    $factura->generarFactura();
} else {
    die("Error: Parámetro 'codigo' no especificado.");
}
