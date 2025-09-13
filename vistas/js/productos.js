// vistas/js/productos.js
(function ($) {
  "use strict";

  // ====== Salir si no existe la tabla ======
  const $tabla = $(".tablaProductos");
  if ($tabla.length === 0) return;

  // ====== Utilidades ======
  const $perfilOculto = $("#perfilOculto");
  const perfilOculto = ($perfilOculto.val() || "").trim();

  const to2 = (v) => (isFinite(v) ? Number(v).toFixed(2) : "");
  const asNum = (v) => {
    const n = parseFloat(String(v).replace(",", "."));
    return isFinite(n) ? n : 0;
  };

  // Convierte número 1..n a letras Excel A..Z, AA.., etc.
  function colLetter(n) {
    let s = "";
    while (n > 0) {
      const m = (n - 1) % 26;
      s = String.fromCharCode(65 + m) + s;
      n = Math.floor((n - 1) / 26);
    }
    return s;
  }

  // Textos DataTables (ES)
  const DT_ES = {
    sProcessing: "Procesando...",
    sLengthMenu: "Mostrar _MENU_ registros",
    sZeroRecords: "No se encontraron resultados",
    sEmptyTable: "Ningún dato disponible en esta tabla",
    sInfo: "Mostrando del _START_ al _END_ de _TOTAL_ registros",
    sInfoEmpty: "Mostrando 0 a 0 de 0 registros",
    sInfoFiltered: "(filtrado de _MAX_ registros)",
    sSearch: "Buscar:",
    sLoadingRecords: "Cargando...",
    oPaginate: {
      sFirst: "Primero",
      sLast: "Último",
      sNext: "Siguiente",
      sPrevious: "Anterior",
    },
    oAria: {
      sSortAscending: ": Activar para ordenar la columna ascendente",
      sSortDescending: ": Activar para ordenar la columna descendente",
    },
  };

  // Fecha para nombres de archivos
  const hoy = new Date();
  const yyyy = hoy.getFullYear();
  const mm = String(hoy.getMonth() + 1).padStart(2, "0");
  const dd = String(hoy.getDate()).padStart(2, "0");
  const nombreArchivo = `Productos_TECHEANDE_${yyyy}-${mm}-${dd}`;
  const tituloPantalla = "Listado de Productos";

  // Evitar doble inicialización
  if ($.fn.DataTable.isDataTable($tabla)) {
    $tabla.DataTable().destroy();
  }

  // ====== Inicialización DataTable ======
  const dt = $tabla.DataTable({
    // serverSide: true, // habilítalo si tu endpoint soporta procesamiento del servidor
    ajax: {
      url: "ajax/datatable-productos.ajax.php",
      type: "GET",
      data: function (d) {
        d.perfilOculto = perfilOculto;
      },
      error: function (xhr) {
        console.error("Error DataTable:", xhr.responseText || xhr.statusText);
        Swal.fire({
          icon: "error",
          title: "Error cargando productos",
          text: "No fue posible obtener los datos. Inténtalo nuevamente.",
        });
      },
    },
    deferRender: true,
    retrieve: true,
    processing: true,
    responsive: true,
    language: DT_ES,

    // Botonera + length a la izquierda, buscador a la derecha
    dom: '<"row"<"col-sm-6"lB><"col-sm-6"f>>rtip',

    // ====== Botones (exportación estable) ======
    buttons: [
      {
        extend: "copyHtml5",
        text: "<i class='fa fa-copy'></i> Copiar",
        className: "btn btn-default",
        titleAttr: "Copiar",
        title:null,
        exportOptions: { columns: ":visible:not(.no-export)" },
      },
      {
        extend: "csvHtml5",
        text: "<i class='fa fa-file-text-o'></i> CSV",
        className: "btn btn-info",
        titleAttr: "Exportar a CSV",
        filename: nombreArchivo,
        title:null,
        exportOptions: { columns: ":visible:not(.no-export)" },
      },
      {
        extend: "excelHtml5",
        text: "<i class='fa fa-file-excel-o'></i> Excel",
        className: "btn btn-success",
        titleAttr: "Exportar a Excel",
		title:null,
        filename: nombreArchivo,      
        exportOptions: { columns: ":visible:not(.no-export)" },
        customize: function (xlsx) {
          try {
            // Hoja principal
            const sheet = xlsx.xl.worksheets["sheet1.xml"];
            const $sheet = $(sheet);

            // 1) Detectar fila de encabezado (normalmente r="1")
            let $firstRow = $("sheetData row", $sheet).first();
            // Si por cualquier motivo no existe, no tocamos nada
            if ($firstRow.length === 0) return;

            const headerRow = $firstRow.attr("r") || "1";
            const headerCells = $(`row[r="${headerRow}"] c`, $sheet).length || 1;
            const lastCol = colLetter(headerCells);

            // 2) AutoFilter: agrégalo una sola vez
            if ($("autoFilter", $sheet).length === 0) {
              // Inserta autoFilter al final de worksheet (válido para Excel)
              $("worksheet", $sheet).append(
                `<autoFilter ref="A${headerRow}:${lastCol}${headerRow}"/>`
              );
            }

            // 3) Congelar encabezado (pane) – añade sheetViews si no existe
            if ($("sheetViews", $sheet).length === 0) {
              const topLeftCell = `A${parseInt(headerRow, 10) + 1}`;
              // sheetViews debe ir antes de sheetData para mayor compatibilidad
              $("sheetData", $sheet).before(
                `<sheetViews><sheetView workbookViewId="0">
                   <pane ySplit="1" topLeftCell="${topLeftCell}" activePane="bottomLeft" state="frozen"/>
                 </sheetView></sheetViews>`
              );
            }

            // 4) Negrita al encabezado (estilo 51 normalmente es bold)
            $(`row[r="${headerRow}"] c`, $sheet).attr("s", "51");

            // 5) Anchos de columnas homogéneos
            let colsXML = "<cols>";
            for (let i = 1; i <= headerCells; i++) {
              const width = i <= 3 ? 22 : 18;
              colsXML += `<col min="${i}" max="${i}" width="${width}" customWidth="1"/>`;
            }
            colsXML += "</cols>";

            // Coloca <cols> justo antes de sheetData (posición segura)
            $("cols", $sheet).remove();
            $("sheetData", $sheet).before(colsXML);
          } catch (e) {
            console.warn("Excel customize falló; exportando básico:", e);
            // Si algo falla, dejamos que el export sea básico (válido)
          }
        },
      },
      {
        extend: "pdfHtml5",
		text:"<i class='fa fa-pdf'></i>PDF",
		className: "btn btn-danger",
  		titleAttr: "Exportar a PDF",
        filename:nombreArchivo,
  		title:tituloPantalla,
        exportOptions: { columns: ":visible:not(.no-export)" },
      },
      {
        extend: "print",
        text: "<i class='fa fa-print'></i> Imprimir",
        className: "btn btn-default",
        titleAttr: "Imprimir",
        title: null,
        messageTop: () =>
          `<h3 style="margin:0;">TECHEANDE</h3><div>${tituloPantalla}</div><hr/>`,
        exportOptions: { columns: ":visible:not(.no-export)" },
      },
      {
        extend: "colvis",
        text: "<i class='fa fa-columns'></i> Columnas",
        className: "btn btn-default",
      },
    ],

    // order: [[0, "asc"]], // Descomenta si quieres ordenar por la primera columna
  });

  // ====== Precio de venta con porcentaje ======
  const $nuevoPrecioCompra = $("#nuevoPrecioCompra");
  const $editarPrecioCompra = $("#editarPrecioCompra");
  const $nuevoPrecioVenta = $("#nuevoPrecioVenta");
  const $editarPrecioVenta = $("#editarPrecioVenta");
  const $porcentajeInput = $(".nuevoPorcentaje");
  const $chkPorcentaje = $(".porcentaje");

  function aplicarPorcentaje() {
    const usar = isChecked($chkPorcentaje);
    const p = asNum($porcentajeInput.val());
    if (!usar) return;

    const compraNuevo = asNum($nuevoPrecioCompra.val());
    const compraEditar = asNum($editarPrecioCompra.val());
    const calcNuevo = compraNuevo + (compraNuevo * p) / 100;
    const calcEditar = compraEditar + (compraEditar * p) / 100;

    if (compraNuevo > 0) {
      $nuevoPrecioVenta.val(to2(calcNuevo)).prop("readonly", true);
    }
    if (compraEditar > 0) {
      $editarPrecioVenta.val(to2(calcEditar)).prop("readonly", true);
    }
  }

  function isChecked($el) {
    if ($el.data("iCheck")) return $el.prop("checked"); // iCheck refleja prop
    return $el.is(":checked");
  }

  $nuevoPrecioCompra.on("change", aplicarPorcentaje);
  $editarPrecioCompra.on("change", aplicarPorcentaje);
  $porcentajeInput.on("change", function () {
    if (isChecked($chkPorcentaje)) {
      aplicarPorcentaje();
      $nuevoPrecioVenta.prop("readonly", true);
      $editarPrecioVenta.prop("readonly", true);
    }
  });

  // iCheck (si está cargado)
  $chkPorcentaje.on("ifUnchecked", function () {
    $nuevoPrecioVenta.prop("readonly", false);
    $editarPrecioVenta.prop("readonly", false);
  });
  $chkPorcentaje.on("ifChecked", function () {
    aplicarPorcentaje();
    $nuevoPrecioVenta.prop("readonly", true);
    $editarPrecioVenta.prop("readonly", true);
  });

  // Fallback nativo si no hay iCheck
  $chkPorcentaje.on("change", function () {
    if (!$chkPorcentaje.data("iCheck")) {
      if (this.checked) {
        aplicarPorcentaje();
        $nuevoPrecioVenta.prop("readonly", true);
        $editarPrecioVenta.prop("readonly", true);
      } else {
        $nuevoPrecioVenta.prop("readonly", false);
        $editarPrecioVenta.prop("readonly", false);
      }
    }
  });

  // ====== Subida y preview de imagen ======
  // En HTML: <input type="file" class="nuevaImagen" accept="image/png,image/jpeg">
  $(".nuevaImagen").on("change", function () {
    const file = this.files && this.files[0];
    if (!file) return;

    const allowed = ["image/jpeg", "image/png"];
    const maxSize = 2 * 1024 * 1024; // 2MB

    if (!allowed.includes(file.type)) {
      this.value = "";
      Swal.fire({
        icon: "error",
        title: "Formato no válido",
        text: "La imagen debe ser JPG o PNG.",
      });
      return;
    }

    if (file.size > maxSize) {
      this.value = "";
      Swal.fire({
        icon: "error",
        title: "Archivo muy pesado",
        text: "La imagen no debe pesar más de 2MB.",
      });
      return;
    }

    const reader = new FileReader();
    reader.onload = (e) => {
      $(".previsualizar").attr("src", e.target.result);
    };
    reader.readAsDataURL(file);
  });

  // ====== Editar producto ======
  $tabla.on("click", "button.btnEditarProducto", function () {
    const idProducto = $(this).attr("idProducto");
    if (!idProducto) return;

    const datos = new FormData();
    datos.append("idProducto", idProducto);

    $.ajax({
      url: "ajax/productos.ajax.php",
      method: "POST",
      data: datos,
      cache: false,
      contentType: false,
      processData: false,
      dataType: "json",
    })
      .done(function (r) {
        if (!r) return;

        // Cargar categoría
        const datosCat = new FormData();
        datosCat.append("idCategoria", r["id_categoria"]);

        $.ajax({
          url: "ajax/categorias.ajax.php",
          method: "POST",
          data: datosCat,
          cache: false,
          contentType: false,
          processData: false,
          dataType: "json",
        })
          .done(function (cat) {
            if (cat) {
              $("#editarCategoria").val(cat["id"]);
              $("#editarCategoria").html(cat["categoria"]);
            }
          })
          .fail(logAjaxFail);

        $("#editarCodigo").val(r["codigo"]);
        $("#editarDescripcion").val(r["descripcion"]);
        $("#editarStock").val(r["stock"]);
        $("#editarPrecioCompra").val(r["precio_compra"]);
        $("#editarPrecioVenta").val(r["precio_venta"]);

        if (r["imagen"]) {
          $("#imagenActual").val(r["imagen"]);
          $(".previsualizar").attr("src", r["imagen"]);
        }

        aplicarPorcentaje();
      })
      .fail(logAjaxFail);
  });

  // ====== Eliminar producto ======
  $tabla.on("click", "button.btnEliminarProducto", function () {
    const idProducto = $(this).attr("idProducto");
    const codigo = $(this).attr("codigo") || "";
    const imagen = $(this).attr("imagen") || "";
    if (!idProducto) return;

    Swal.fire({
      icon: "warning",
      title: "¿Borrar producto?",
      text: "Esta acción no se puede deshacer.",
      showCancelButton: true,
      confirmButtonColor: "#3085d6",
      cancelButtonColor: "#d33",
      confirmButtonText: "Sí, borrar",
      cancelButtonText: "Cancelar",
    }).then((res) => {
      if (res.isConfirmed) {
        const url = new URL(window.location.href);
        url.searchParams.set("ruta", "productos");
        url.searchParams.set("idProducto", idProducto);
        if (imagen) url.searchParams.set("imagen", imagen);
        if (codigo) url.searchParams.set("codigo", codigo);
        window.location.href = "index.php?" + url.searchParams.toString();
      }
    });
  });

  // ====== Helper de errores ======
  function logAjaxFail(xhr, status, err) {
    console.error("AJAX fail:", status, err, xhr && xhr.responseText);
    Swal.fire({
      icon: "error",
      title: "Error de comunicación",
      text: "No fue posible completar la operación. Inténtalo nuevamente.",
    });
  }

  // Recarga manual si la necesitas:
  // $("#btnRecargarProductos").on("click", () => dt.ajax.reload(null, false));
})(jQuery);
