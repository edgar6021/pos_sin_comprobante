<?php

require_once "conexion.php";

class ModeloClientes{

	/*=============================================
	CREAR CLIENTE
	=============================================*/

	static public function mdlIngresarCliente($tabla, $datos){

		$stmt = Conexion::conectar()->prepare("INSERT INTO $tabla(nombre,  documento, tipo_documento, email, telefono, direccion, fecha_nacimiento, compras) VALUES (:nombre, :documento, :tipo_documento, :email, :telefono, :direccion, :fecha_nacimiento, 0)");

		$stmt->bindParam(":nombre", $datos["nombre"], PDO::PARAM_STR);
		$stmt->bindParam(":documento", $datos["documento"], PDO::PARAM_INT);
        $stmt->bindParam(":tipo_documento", $datos["tipo_documento"],PDO::PARAM_STR);
		$stmt->bindParam(":email", $datos["email"], PDO::PARAM_STR);
		$stmt->bindParam(":telefono", $datos["telefono"], PDO::PARAM_STR);
		$stmt->bindParam(":direccion", $datos["direccion"], PDO::PARAM_STR);
		$stmt->bindParam(":fecha_nacimiento", $datos["fecha_nacimiento"], PDO::PARAM_STR);

		if($stmt->execute()){

			return "ok";

		}else{

			return "error";
		
		}

		$stmt->close();
		$stmt = null;

	}

	/*=============================================
	MOSTRAR CLIENTES
	=============================================*/

	static public function mdlMostrarClientes($tabla, $item, $valor){

		if($item != null){

			$stmt = Conexion::conectar()->prepare("SELECT * FROM $tabla WHERE $item = :$item ORDER BY id DESC");

			$stmt -> bindParam(":".$item, $valor, PDO::PARAM_STR);

			$stmt -> execute();

			return $stmt -> fetch();

		}else{

			$stmt = Conexion::conectar()->prepare("SELECT * FROM $tabla ORDER BY id DESC");

			$stmt -> execute();

			return $stmt -> fetchAll();

		}

		$stmt -> close();

		$stmt = null;

	}

	/*=============================================
	EDITAR CLIENTE
	=============================================*/

	static public function mdlEditarCliente($tabla, $datos){

		$stmt = Conexion::conectar()->prepare("UPDATE $tabla SET nombre = :nombre,  documento = :documento, tipo_documento = :tipo_documento, email = :email, telefono = :telefono, direccion = :direccion, fecha_nacimiento = :fecha_nacimiento WHERE id = :id");

		$stmt->bindParam(":id", $datos["id"], PDO::PARAM_INT);
		$stmt->bindParam(":nombre", $datos["nombre"], PDO::PARAM_STR);
		$stmt->bindParam(":documento", $datos["documento"], PDO::PARAM_INT);
		$stmt->bindParam(":tipo_documento", $datos["tipo_documento"], PDO::PARAM_STR);
		$stmt->bindParam(":email", $datos["email"], PDO::PARAM_STR);
		$stmt->bindParam(":telefono", $datos["telefono"], PDO::PARAM_STR);
		$stmt->bindParam(":direccion", $datos["direccion"], PDO::PARAM_STR);
		$stmt->bindParam(":fecha_nacimiento", $datos["fecha_nacimiento"], PDO::PARAM_STR);

		if($stmt->execute()){

			return "ok";

		}else{

			return "error";
		
		}

		$stmt->close();
		$stmt = null;

	}

	/*=============================================
	ELIMINAR CLIENTE
	=============================================*/

	static public function mdlEliminarCliente($tabla, $datos){

		$stmt = Conexion::conectar()->prepare("DELETE FROM $tabla WHERE id = :id");

		$stmt -> bindParam(":id", $datos, PDO::PARAM_INT);

		if($stmt -> execute()){

			return "ok";
		
		}else{

			return "error";	

		}

		$stmt -> close();

		$stmt = null;

	}

	/*=============================================
	ACTUALIZAR CLIENTE
	=============================================*/

	static public function mdlActualizarCliente($tabla, $item1, $valor1, $valor){

		$stmt = Conexion::conectar()->prepare("UPDATE $tabla SET $item1 = :$item1 WHERE id = :id");

		if ($item1 == "compras") {
    		$stmt -> bindParam(":".$item1, $valor1, PDO::PARAM_INT);
				} else {
   			 $stmt -> bindParam(":".$item1, $valor1, PDO::PARAM_STR);
			}
		$stmt -> bindParam(":id", $valor, PDO::PARAM_INT);

		

		if($stmt -> execute()){

			return "ok";
		
		}else{

			return "error";	

		}

		$stmt -> close();

		$stmt = null;

	}









static public function mdlContarCompras($idCliente) {
    try {
        // 🔎 Contar las compras del cliente en la tabla de ventas
        $stmt = Conexion::conectar()->prepare("
            SELECT COUNT(*) AS cantidad_compras 
            FROM ventas 
            WHERE id_cliente = :idCliente
        ");
        $stmt->bindParam(":idCliente", $idCliente, PDO::PARAM_INT);
        $stmt->execute();
        $resultado = $stmt->fetch();
        
        // Si encuentra compras, actualiza la tabla de clientes
        if ($resultado) {
            $cantidadCompras = $resultado["cantidad_compras"];

            // 🔄 Actualizamos en la tabla de clientes
            $stmtUpdate = Conexion::conectar()->prepare("
                UPDATE clientes 
                SET compras = :compras 
                WHERE id = :idCliente
            ");
            $stmtUpdate->bindParam(":compras", $cantidadCompras, PDO::PARAM_INT);
            $stmtUpdate->bindParam(":idCliente", $idCliente, PDO::PARAM_INT);
            $stmtUpdate->execute();

            // 🏷️ Devolvemos la cantidad actualizada
            return $cantidadCompras;
        } else {
            return 0;
        }
    } catch (PDOException $e) {
        return "error: " . $e->getMessage();
    } finally {
        $stmt = null;
        $stmtUpdate = null;
    }
}










}