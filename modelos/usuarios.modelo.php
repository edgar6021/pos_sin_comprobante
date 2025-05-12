<?php

require_once "conexion.php";

class ModeloUsuarios {

  /*=============================================
  MOSTRAR USUARIOS
  =============================================*/
  static public function mdlMostrarUsuarios($tabla, $item, $valor) {
    $pdo = Conexion::conectar();

    if ($item !== null) {
      $stmt = $pdo->prepare("SELECT * FROM $tabla WHERE $item = :valor");
      $stmt->bindParam(":valor", $valor, PDO::PARAM_STR);
      $stmt->execute();
      $resultado = $stmt->fetch(PDO::FETCH_ASSOC);
    } else {
      $stmt = $pdo->prepare("SELECT * FROM $tabla");
      $stmt->execute();
      $resultado = $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    $stmt = null;
    return $resultado;
  }

  /*=============================================
  REGISTRO DE USUARIO
  =============================================*/
  static public function mdlIngresarUsuario($tabla, $datos) {
    $sql = "INSERT INTO $tabla (nombre, usuario, password, perfil, foto) 
            VALUES (:nombre, :usuario, :password, :perfil, :foto)";

    $stmt = Conexion::conectar()->prepare($sql);

    $stmt->bindParam(":nombre", $datos["nombre"], PDO::PARAM_STR);
    $stmt->bindParam(":usuario", $datos["usuario"], PDO::PARAM_STR);
    $stmt->bindParam(":password", $datos["password"], PDO::PARAM_STR);
    $stmt->bindParam(":perfil", $datos["perfil"], PDO::PARAM_STR);
    $stmt->bindParam(":foto", $datos["foto"], PDO::PARAM_STR);

    if ($stmt->execute()) {
      $stmt = null;
      return "ok";
    } else {
      error_log("Error mdlIngresarUsuario: " . implode(" | ", $stmt->errorInfo()));
      $stmt = null;
      return "error";
    }
  }

  /*=============================================
  EDITAR USUARIO
  =============================================*/
  static public function mdlEditarUsuario($tabla, $datos) {
    $sql = "UPDATE $tabla 
            SET nombre = :nombre, password = :password, perfil = :perfil, foto = :foto 
            WHERE usuario = :usuario";

    $stmt = Conexion::conectar()->prepare($sql);

    $stmt->bindParam(":nombre", $datos["nombre"], PDO::PARAM_STR);
    $stmt->bindParam(":password", $datos["password"], PDO::PARAM_STR);
    $stmt->bindParam(":perfil", $datos["perfil"], PDO::PARAM_STR);
    $stmt->bindParam(":foto", $datos["foto"], PDO::PARAM_STR);
    $stmt->bindParam(":usuario", $datos["usuario"], PDO::PARAM_STR);

    if ($stmt->execute()) {
      $stmt = null;
      return "ok";
    } else {
      error_log("Error mdlEditarUsuario: " . implode(" | ", $stmt->errorInfo()));
      $stmt = null;
      return "error";
    }
  }

  /*=============================================
  ACTUALIZAR USUARIO (campo específico)
  =============================================*/
  static public function mdlActualizarUsuario($tabla, $item1, $valor1, $item2, $valor2) {
    $sql = "UPDATE $tabla SET $item1 = :valor1 WHERE $item2 = :valor2";

    $stmt = Conexion::conectar()->prepare($sql);

    $stmt->bindParam(":valor1", $valor1, PDO::PARAM_STR);
    $stmt->bindParam(":valor2", $valor2, PDO::PARAM_STR);

    if ($stmt->execute()) {
      $stmt = null;
      return "ok";
    } else {
      error_log("Error mdlActualizarUsuario: " . implode(" | ", $stmt->errorInfo()));
      $stmt = null;
      return "error";
    }
  }

  /*=============================================
  BORRAR USUARIO
  =============================================*/
  static public function mdlBorrarUsuario($tabla, $idUsuario) {
    $stmt = Conexion::conectar()->prepare("DELETE FROM $tabla WHERE id = :id");

    $stmt->bindParam(":id", $idUsuario, PDO::PARAM_INT);

    if ($stmt->execute()) {
      $stmt = null;
      return "ok";
    } else {
      error_log("Error mdlBorrarUsuario: " . implode(" | ", $stmt->errorInfo()));
      $stmt = null;
      return "error";
    }
  }

}
