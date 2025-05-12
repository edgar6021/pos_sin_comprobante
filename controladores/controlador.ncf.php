<?php

class ControladorNCF {

    /**
     * Generar un NCF válido basado en el tipo de comprobante
     * - Tipo debe ser como: B01, B02, E31...
     */
    static public function ctrGenerarNCF($tipoComprobante) {

        $archivo = "ncf-secuencia.json";

        // Si no existe el archivo, inicialízalo
        if (!file_exists($archivo)) {
            $dataInicial = [
                "B01" => 1,
                "B02" => 1,
                "B03" => 1,
                "B04" => 1,
                "E31" => 1
            ];
            file_put_contents($archivo, json_encode($dataInicial, JSON_PRETTY_PRINT));
        }

        $contenido = json_decode(file_get_contents($archivo), true);

        // Validar tipo de comprobante
        if (!isset($contenido[$tipoComprobante])) {
            // Si no existe ese tipo, lo crea
            $contenido[$tipoComprobante] = 1;
        }

        // Secuencia actual
        $secuencia = $contenido[$tipoComprobante];

        // Generar NCF formateado, ej: B01000000012
        $ncfGenerado = $tipoComprobante . str_pad($secuencia, 8, "0", STR_PAD_LEFT);

        // Incrementar la secuencia y guardar
        $contenido[$tipoComprobante]++;
        file_put_contents($archivo, json_encode($contenido, JSON_PRETTY_PRINT));

        return $ncfGenerado;
    }
}
