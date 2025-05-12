<?php

if ($_SESSION["perfil"] == "Especial") {
  echo '<script>window.location = "inicio";</script>';
  return;
}

$xml = ControladorVentas::ctrDescargarXML();

if ($xml) {
  rename($_GET["xml"] . ".xml", "xml/" . $_GET["xml"] . ".xml");

  echo '<a class="btn btn-block btn-success abrirXML" archivo="xml/' . $_GET["xml"] . '.xml" href="ventas">
    Se ha creado correctamente el archivo XML 
    <span class="fa fa-times pull-right"></span>
  </a>';
}
?>

<div class="content-wrapper">
  <section class="content-header">
    <h1>Administrar ventas</h1>
    <ol class="breadcrumb">
      <li><a href="inicio"><i class="fa fa-dashboard"></i> Inicio</a></li>
      <li class="active">Administrar ventas</li>
    </ol>
  </section>

  <section class="content">
    <div class="box">

      <div class="box-header with-border">
        <a href="crear-venta">
          <button class="btn btn-primary">Agregar venta</button>
        </a>

        <button type="button" class="btn btn-default pull-right" id="daterange-btn">
          <span>
            <i class="fa fa-calendar"></i>
            <?php
            if (isset($_GET["fechaInicial"])) {
              echo $_GET["fechaInicial"] . " - " . $_GET["fechaFinal"];
            } else {
              echo 'Rango de fecha';
            }
            ?>
          </span>
          <i class="fa fa-caret-down"></i>
        </button>
      </div>

      <div class="box-body">

        <table class="table table-bordered table-striped dt-responsive tablas" width="100%">
          <thead>
            <tr>
              <th>#</th>
              <th>Código factura</th>
              <th>Cliente</th>
              <th>Vendedor</th>
              <th>Forma de pago</th>
              <th>Neto</th>
              <th>Total</th>
              <th>Fecha</th>
              <th>Acciones</th>
            </tr>
          </thead>

          <tbody>
            <?php
            $fechaInicial = $_GET["fechaInicial"] ?? null;
            $fechaFinal = $_GET["fechaFinal"] ?? null;
            $idVendedor = ($_SESSION["perfil"] == "Vendedor") ? $_SESSION["id"] : null;

            $ventas = ControladorVentas::ctrRangoFechasVentas($fechaInicial, $fechaFinal, $idVendedor);

            foreach ($ventas as $key => $venta) {
              $cliente = ControladorClientes::ctrMostrarClientes("id", $venta["id_cliente"]);
              $vendedor = ControladorUsuarios::ctrMostrarUsuarios("id", $venta["id_vendedor"]);

              echo '<tr>
                      <td>' . ($key + 1) . '</td>
                      <td>' . $venta["codigo"] . '</td>
                      <td>' . ($cliente["nombre"] ?? '') . '</td>
                      <td>' . ($vendedor["nombre"] ?? '') . '</td>
                      <td>' . $venta["metodo_pago"] . '</td>
                      <td>$ ' . number_format($venta["neto"], 2) . '</td>
                      <td>$ ' . number_format($venta["total"], 2) . '</td>
                      <td>' . $venta["fecha"] . '</td>
                      <td>
                        <div class="btn-group">';

              if ($_SESSION["perfil"] == "Administrador" || $venta["id_vendedor"] == $_SESSION["id"]) {
                echo '<a class="btn btn-info" href="index.php?ruta=ventas&xml=' . $venta["codigo"] . '">xml</a>
                      <button class="btn btn-success btnImprimirFactura" codigoVenta="' . $venta["codigo"] . '">
                        <i class="fa fa-print"></i>
                      </button>';
              }

              if ($_SESSION["perfil"] == "Administrador") {
                echo '<button class="btn btn-primary btnEditarVenta" idVenta="' . $venta["id"] . '"><i class="fa fa-pencil"></i></button>
                      <button class="btn btn-danger btnEliminarVenta" idVenta="' . $venta["id"] . '"><i class="fa fa-times"></i></button>';
              }

              echo '</div></td></tr>';
            }
            ?>
          </tbody>
        </table>

        <?php
        $eliminarVenta = new ControladorVentas();
        $eliminarVenta->ctrEliminarVenta();
        ?>
      </div>
    </div>
  </section>
</div>
