<?php

class ControladorUsuarios {

  /*=============================================
  INGRESO DE USUARIO
  =============================================*/
  static public function ctrIngresoUsuario() {
    

    if (isset($_POST["ingUsuario"], $_POST["ingPassword"])) {

      if (!isset($_POST["csrf_token"]) || $_POST["csrf_token"] !== $_SESSION["csrf_token"]) {
        echo '<div class="alert alert-danger">Token CSRF inválido</div>';
        return;
      }

      $usuario = trim($_POST["ingUsuario"]);
      $password = $_POST["ingPassword"];

      if (empty($usuario) || empty($password)) {
        self::alert("error", "Todos los campos son obligatorios", "login");
        return;
      }

      if (!preg_match('/^[a-zA-Z0-9]+$/', $usuario)) {
        self::alert("error", "Usuario inválido", "login");
        return;
      }

      $respuesta = ModeloUsuarios::mdlMostrarUsuarios("usuarios", "usuario", $usuario);

      if (!$respuesta || !password_verify($password, $respuesta["password"])) {
        self::alert("error", "Usuario o contraseña incorrectos", "login");
        return;
      }

      if ((int)$respuesta["estado"] !== 1) {
        self::alert("error", "Usuario no activado", "login");
        return;
      }

      session_regenerate_id(true);
      $_SESSION["iniciarSesion"] = "ok";
      $_SESSION["id"] = $respuesta["id"];
      $_SESSION["nombre"] = $respuesta["nombre"];
      $_SESSION["usuario"] = $respuesta["usuario"];
      $_SESSION["foto"] = $respuesta["foto"];
      $_SESSION["perfil"] = $respuesta["perfil"];
      $_SESSION["ip_check"] = $_SERVER["REMOTE_ADDR"];
      $_SESSION["agent_check"] = $_SERVER["HTTP_USER_AGENT"];
      $_SESSION["last_active"] = time();

      date_default_timezone_set("America/Santo_Domingo");
      $fechaActual = date("Y-m-d H:i:s");

      ModeloUsuarios::mdlActualizarUsuario("usuarios", "ultimo_login", $fechaActual, "id", $respuesta["id"]);

      echo '<script>window.location = "inicio";</script>';
    }
  }

  /*=============================================
  REGISTRO DE USUARIO
  =============================================*/
  static public function ctrCrearUsuario() {
    

    if (isset($_POST["nuevoUsuario"])) {

      if (!isset($_POST["csrf_token"]) || $_POST["csrf_token"] !== $_SESSION["csrf_token"]) {
        self::alert("error", "Token inválido", "usuarios");
        return;
      }

      $nombre = htmlspecialchars(trim($_POST["nuevoNombre"]));
      $usuario = htmlspecialchars(trim($_POST["nuevoUsuario"]));
      $password = $_POST["nuevoPassword"];
      $perfil = $_POST["nuevoPerfil"];

      if (empty($nombre) || empty($usuario) || empty($password) || empty($perfil)) {
        self::alert("error", "¡Todos los campos son obligatorios!", "usuarios");
        return;
      }

      if (
        !preg_match('/^[a-zA-Z0-9ñÑáéíóúÁÉÍÓÚ ]+$/', $nombre) ||
        !preg_match('/^[a-zA-Z0-9]+$/', $usuario) ||
        !self::validarPassword($password)
      ) {
        self::alert("error", "Datos inválidos. Usa una contraseña fuerte (mínimo 8 caracteres, símbolos, mayúsculas, números).", "usuarios");
        return;
      }

      $ruta = self::procesarImagen($usuario, $_FILES["nuevaFoto"] ?? null);

      $datos = array(
        "nombre" => $nombre,
        "usuario" => $usuario,
        "password" => password_hash($password, PASSWORD_DEFAULT),
        "perfil" => $perfil,
        "foto" => $ruta
      );

      if (ModeloUsuarios::mdlIngresarUsuario("usuarios", $datos) === "ok") {
        self::alert("success", "¡El usuario ha sido guardado correctamente!", "usuarios");
      } else {
        self::alert("error", "Error al guardar el usuario", "usuarios");
      }
    }
  }

  /*=============================================
  MOSTRAR USUARIO
  =============================================*/
  static public function ctrMostrarUsuarios($item, $valor) {
    return ModeloUsuarios::mdlMostrarUsuarios("usuarios", $item, $valor);
  }

  /*=============================================
  EDITAR USUARIO
  =============================================*/
  static public function ctrEditarUsuario() {
    

    if (isset($_POST["editarUsuario"])) {

      if (!isset($_POST["csrf_token"]) || $_POST["csrf_token"] !== $_SESSION["csrf_token"]) {
        self::alert("error", "Token inválido", "usuarios");
        return;
      }

      $nombre = htmlspecialchars(trim($_POST["editarNombre"]));
      $usuario = htmlspecialchars(trim($_POST["editarUsuario"]));
      $password = $_POST["editarPassword"];
      $perfil = $_POST["editarPerfil"];
      $ruta = $_POST["fotoActual"];

      if (!preg_match('/^[a-zA-Z0-9ñÑáéíóúÁÉÍÓÚ ]+$/', $nombre)) {
        self::alert("error", "¡Nombre inválido!", "usuarios");
        return;
      }

      if (!empty($_FILES["editarFoto"]["tmp_name"])) {
        $ruta = self::procesarImagen($usuario, $_FILES["editarFoto"]);
        if ($_POST["fotoActual"]) unlink($_POST["fotoActual"]);
      }

      $passwordHash = !empty($password) && self::validarPassword($password)
        ? password_hash($password, PASSWORD_DEFAULT)
        : $_POST["passwordActual"];

      $datos = array(
        "nombre" => $nombre,
        "usuario" => $usuario,
        "password" => $passwordHash,
        "perfil" => $perfil,
        "foto" => $ruta
      );

      if (ModeloUsuarios::mdlEditarUsuario("usuarios", $datos) === "ok") {
        self::alert("success", "El usuario ha sido editado correctamente", "usuarios");
      } else {
        self::alert("error", "Error al editar el usuario", "usuarios");
      }
    }
  }

  /*=============================================
  BORRAR USUARIO
  =============================================*/
  static public function ctrBorrarUsuario() {
    

    if (isset($_GET["idUsuario"])) {

      $tabla = "usuarios";
      $id = $_GET["idUsuario"];
      $foto = $_GET["fotoUsuario"];
      $usuario = $_GET["usuario"];

      if (!empty($foto)) {
        unlink($foto);
        rmdir("vistas/img/usuarios/" . $usuario);
      }

      if (ModeloUsuarios::mdlBorrarUsuario($tabla, $id) === "ok") {
        self::alert("success", "El usuario ha sido borrado correctamente", "usuarios");
      } else {
        self::alert("error", "Error al eliminar usuario", "usuarios");
      }
    }
  }

  /*=============================================
  MÉTODOS AUXILIARES
  =============================================*/
  private static function alert($tipo, $mensaje, $ruta) {
    echo "<script>
      swal({
        type: '$tipo',
        title: '$mensaje',
        showConfirmButton: true,
        confirmButtonText: 'Cerrar'
      }).then(function(result){
        if(result.value){
          window.location = '$ruta';
        }
      });
    </script>";
  }

  private static function validarPassword($password) {
    return preg_match('/^(?=.*[a-z])(?=.*[A-Z])(?=.*\d)(?=.*[\W_]).{8,}$/', $password);
  }

  private static function procesarImagen($usuario, $file) {
    if (!$file || empty($file["tmp_name"])) return "";

    $mimeType = mime_content_type($file["tmp_name"]);
    $allowedTypes = ['image/jpeg', 'image/png'];
    if (!in_array($mimeType, $allowedTypes)) return "";

    list($ancho, $alto) = getimagesize($file["tmp_name"]);
    $nuevoAncho = 500;
    $nuevoAlto = 500;
    $directorio = "vistas/img/usuarios/$usuario";

    if (!is_dir($directorio)) mkdir($directorio, 0755, true);

    $aleatorio = mt_rand(100, 999);
    $extension = $mimeType === 'image/jpeg' ? '.jpg' : '.png';
    $ruta = "$directorio/$aleatorio$extension";

    $origen = $mimeType === 'image/jpeg' ? imagecreatefromjpeg($file["tmp_name"]) : imagecreatefrompng($file["tmp_name"]);
    $destino = imagecreatetruecolor($nuevoAncho, $nuevoAlto);
    imagecopyresized($destino, $origen, 0, 0, 0, 0, $nuevoAncho, $nuevoAlto, $ancho, $alto);
    $mimeType === 'image/jpeg' ? imagejpeg($destino, $ruta) : imagepng($destino, $ruta);

    return $ruta;
  }

}
