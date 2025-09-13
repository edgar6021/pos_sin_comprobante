// vistas/js/clientes.js
(function ($) {
  "use strict";

  // ====== Salir si no existe la tabla ======
  const $tabla = $(".tablaClientes");
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
  const nombreArchivo = `Clientes_TECHEANDE_${yyyy}-${mm}-${dd}`;
  const tituloPantalla = "Listado de Clientes";

  // Evitar doble inicialización
  if ($.fn.DataTable.isDataTable($tabla)) {
    $tabla.DataTable().destroy();
    $tabla.find("thead").find("th").removeClass("sorting sorting_asc sorting_desc"); // limpia clases residuales
  }

  // ====== Inicialización DataTable ======
  const dt = $tabla.DataTable({
    ajax: {
      url: "ajax/datatable-clientes.ajax.php",
      type: "GET",
      data: function (d) {
        d.perfilOculto = perfilOculto;
      },
      error: function (xhr) {
        console.error("Error DataTable:", xhr.responseText || xhr.statusText);
        if (typeof Swal !== "undefined") {
          Swal.fire({
            icon: "error",
            title: "Error cargando clientes",
            text: "No fue posible obtener los datos. Inténtalo nuevamente.",
          });
        }
      },
    },
    deferRender: true,
    processing: true,
    responsive: true,
    retrieve: true,
    language: DT_ES,

    // Botonera + length a la izquierda, buscador a la derecha
    // IMPORTANTE: “B” DEBE ESTAR EN EL DOM PARA QUE SE VEAN LOS BOTONES
    dom: '<"row m-b-10"<"col-sm-6"lB><"col-sm-6"f>>rtip',

    // Si tienes una columna de acciones, márcala con class "no-export" en el <th>
    columnDefs: [
      { targets: "no-export", orderable: false, searchable: false },
    ],

    // ====== Botones (exportación) ======
    buttons: [
      {
        extend: "copyHtml5",
        text: "<i class='fa fa-copy'></i> Copiar",
        className: "btn btn-default",
        titleAttr: "Copiar",
        title: null,
        exportOptions: { columns: ":visible:not(.no-export)" },
      },
      {
        extend: "csvHtml5",
        text: "<i class='fa fa-file-text-o'></i> CSV",
        className: "btn btn-info",
        titleAttr: "Exportar a CSV",
        filename: nombreArchivo,
        title: null,
        exportOptions: { columns: ":visible:not(.no-export)" },
      },
      {
        extend: "excelHtml5",
        text: "<i class='fa fa-file-excel-o'></i> Excel",
        className: "btn btn-success",
        titleAttr: "Exportar a Excel",
        filename: nombreArchivo,
        title: null,
        exportOptions: { columns: ":visible:not(.no-export)" },
        customize: function (xlsx) {
          try {
            const sheet = xlsx.xl.worksheets["sheet1.xml"];
            // jQuery puede manipular XML; si no está, salimos
            if (!sheet || typeof $ === "undefined") return;
            const $sheet = $(sheet);

            // Fila de encabezado
            const $firstRow = $("sheetData row", $sheet).first();
            if ($firstRow.length === 0) return;

            const headerRow = $firstRow.attr("r") || "1";
            const headerCells = $(`row[r="${headerRow}"] c`, $sheet).length || 1;
            const lastCol = colLetter(headerCells);

            // AutoFilter
            if ($("autoFilter", $sheet).length === 0) {
              $("worksheet", $sheet).append(
                `<autoFilter ref="A${headerRow}:${lastCol}${headerRow}"/>`
              );
            }

            // Congelar encabezado
            if ($("sheetViews", $sheet).length === 0) {
              const topLeftCell = `A${parseInt(headerRow, 10) + 1}`;
              $("sheetData", $sheet).before(
                `<sheetViews><sheetView workbookViewId="0">
                   <pane ySplit="1" topLeftCell="${topLeftCell}" activePane="bottomLeft" state="frozen"/>
                 </sheetView></sheetViews>`
              );
            }

            // Negrita encabezado (estilo 51 suele ser bold por defecto en DataTables)
            $(`row[r="${headerRow}"] c`, $sheet).attr("s", "51");

            // Ancho de columnas
            let colsXML = "<cols>";
            for (let i = 1; i <= headerCells; i++) {
              const width = i <= 3 ? 22 : 18;
              colsXML += `<col min="${i}" max="${i}" width="${width}" customWidth="1"/>`;
            }
            colsXML += "</cols>";

            $("cols", $sheet).remove();
            $("sheetData", $sheet).before(colsXML);
          } catch (e) {
            console.warn("Excel customize falló; exportando básico:", e);
          }
        },
      },
      {
        extend: "pdfHtml5",
        text: "<i class='fa fa-file-pdf-o'></i> PDF",
        className: "btn btn-danger",
        titleAttr: "Exportar a PDF",
        filename: nombreArchivo,
        title: tituloPantalla,
        exportOptions: { columns: ":visible:not(.no-export)" },
        // Si quieres orientar u optimizar tamaños:
        // orientation: 'landscape',
        // pageSize: 'A4',
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
    // order: [[0, "asc"]],
  });

  // ====== Editar cliente ======
  $tabla.on("click", "button.btnEditarCliente", function () {
    const idCliente = $(this).attr("idCliente");
    if (!idCliente) return;

    const datos = new FormData();
    datos.append("idCliente", idCliente);

    $.ajax({
      url: "ajax/clientes.ajax.php",
      method: "POST",
      data: datos,
      cache: false,
      contentType: false,
      processData: false,
      dataType: "json",
    })
      .done(function (r) {
        if (!r) return;

        // Rellena el formulario de edición
        $("#idCliente").val(r["id"]);
        $("#editarCliente").val(r["nombre"]);
        $("#editarDocumentoId").val(r["documento"]);
        $("#editarTipoDocumento").val(r["tipo_documento"]);
        $("#editarEmail").val(r["email"]);
        $("#editarTelefono").val(r["telefono"]);
        $("#editarDireccion").val(r["direccion"]);
        $("#editarFechaNacimiento").val(r["fecha_nacimiento"]);
      })
      .fail(logAjaxFail);
  });

  // ====== Eliminar cliente ======
  $tabla.on("click", "button.btnEliminarCliente", function () {
    const idCliente = $(this).attr("idCliente");
    if (!idCliente) return;

    const ask = () => {
      if (typeof Swal !== "undefined") {
        Swal.fire({
          icon: "warning",
          title: "¿Borrar cliente?",
          text: "Esta acción no se puede deshacer.",
          showCancelButton: true,
          confirmButtonColor: "#3085d6",
          cancelButtonColor: "#d33",
          confirmButtonText: "Sí, borrar",
          cancelButtonText: "Cancelar",
        }).then((res) => {
          if (res.isConfirmed) go();
        });
      } else if (confirm("¿Borrar cliente? Esta acción no se puede deshacer.")) {
        go();
      }
    };

    const go = () => {
      const url = new URL(window.location.href);
      url.searchParams.set("ruta", "clientes");
      url.searchParams.set("idCliente", idCliente);
      window.location.href = "index.php?" + url.searchParams.toString();
    };

    ask();
  });

  // ====== Validaciones rápidas (opcionales) ======
  const emailRegex = /^[^\s@]+@[^\s@]+\.[^\s@]{2,}$/i;

  $("#nuevoEmail, #editarEmail").on("change", function () {
    const v = $(this).val();
    if (v && !emailRegex.test(v)) {
      if (typeof Swal !== "undefined") {
        Swal.fire({
          icon: "warning",
          title: "Correo inválido",
          text: "Verifica el formato del correo electrónico.",
        });
      } else {
        alert("Correo inválido. Verifica el formato.");
      }
      $(this).focus();
    }
  });

  $("#nuevoTelefono, #editarTelefono").on("input", function () {
    // Permite dígitos, +, espacios y guiones
    this.value = this.value.replace(/[^0-9+\-\s]/g, "");
  });

  // ====== Helper de errores ======
  function logAjaxFail(xhr, status, err) {
    console.error("AJAX fail:", status, err, xhr && xhr.responseText);
    if (typeof Swal !== "undefined") {
      Swal.fire({
        icon: "error",
        title: "Error de comunicación",
        text: "No fue posible completar la operación. Inténtalo nuevamente.",
      });
    } else {
      alert("Error de comunicación. Inténtalo nuevamente.");
    }
  }

  // Recarga manual si la necesitas:
  // $("#btnRecargarClientes").on("click", () => dt.ajax.reload(null, false));
})(jQuery);
