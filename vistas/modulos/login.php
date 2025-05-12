<?php
if (session_status() === PHP_SESSION_NONE) {
  session_start();
}
if (empty($_SESSION['csrf_token'])) {
  $_SESSION['csrf_token'] = bin2hex(random_bytes(32));
}
?>

<div id="back"></div>

<div class="login-box">

  <div class="login-box-body">

    <h2 class="login-box-msg">LOGIN</h2>

    <form method="post" autocomplete="off" id="formLogin">

      <!-- CSRF token oculto -->
      <input type="hidden" name="csrf_token" value="<?php echo $_SESSION['csrf_token']; ?>">

      <!-- Usuario -->
      <div class="form-group has-feedback">
        <input type="text" class="form-control" placeholder="Usuario" name="ingUsuario" required>
        <span class="glyphicon glyphicon-user form-control-feedback"></span>
      </div>

      <!-- Contraseña -->
      <div class="form-group has-feedback">
        <input type="password" class="form-control" placeholder="Contraseña" name="ingPassword" required>
        <span class="glyphicon glyphicon-lock form-control-feedback"></span>
      </div>

      <!-- Botón de ingresar -->
      <div class="row">
        <div class="col-xs-4">
          <button type="submit" class="btn btn-primary btn-block btn-flat">Ingresar</button>
        </div>
      </div>

      <!-- Procesar login -->
      <?php
        $login = new ControladorUsuarios();
        $login->ctrIngresoUsuario();
      ?>

    </form>

  </div>

</div>

<!-- Validación JS simple (opcional) -->
<script>
document.getElementById("formLogin").addEventListener("submit", function(e) {
  const usuario = document.querySelector('input[name="ingUsuario"]').value.trim();
  const password = document.querySelector('input[name="ingPassword"]').value.trim();

  if (usuario.length === 0 || password.length === 0) {
    e.preventDefault();
    alert("Por favor completa todos los campos.");
  }
});
</script>
