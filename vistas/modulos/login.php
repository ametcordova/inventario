
<div id="fondo_login"></div>
<div class="login-box">
  <div class="login-logo">
    <a href="#" style="color:white;"><b>Admin</b>INV</a>
	<img src="extensiones/dist/img/LOGO_NUNOSCO_SMALL.jpg" class="img-responsive" style="padding:20px 100px 0px 100px">
  </div>
<?php  
  if(isset($_COOKIE['logusuario'])) { 
	$user = $_COOKIE["logusuario"]; //$contenido vale super
} else { 
    //setcookie("logusuario","super",time()+30*24*60*60); 
	//$user = $_COOKIE["logusuario"]; //$contenido vale super
	$user="";
}
?>
  <!-- /.login-logo -->
  <div class="card">
    <div class="card-body login-card-body">
      <p class="login-box-msg">Tus credenciales para Iniciar Sesión</p>

      <form method="post">
        <div class="form-group has-feedback">
		  <span class="fa fa-user form-control-feedback"></span>
          <input type="text" class="form-control" placeholder="Usuario" name="ingUsuario" value="<?php echo $user?>" autofocus required>
          
        </div>
        <div class="form-group has-feedback">
		  <span class="fa fa-lock form-control-feedback"></span>
          <input type="password" class="form-control" placeholder="Contraseña" name="ingPassword" value="" required>
        </div>
		<hr>
        <div class="row">
          <div class="col-8">
            <div class="checkbox icheck">
              <label>
                <input type="checkbox" name="recordarme"> Recordarme
              </label>
            </div>
          </div>
          <!-- /.col -->
          <div class="col-4">
            <button type="submit" class="btn btn-primary btn-block btn-flat">Ingresar</button>
          </div>
          <!-- /.col -->
        </div>
        
        <?php
          $login=new controladorUsuarios();
          $login->ctrIngresoUsuario();
        ?>
        
      </form>

 
      <p class="mb-1">
        <a href="#">He olvidado mi password</a>
      </p>
      <p class="mb-0 text-center">
        <a href="#" class="text-center">Registrarse</a>
      </p>
    </div>
    <!-- /.login-card-body -->
  </div>
</div>
<!-- /.login-box -->

 

<!-- jQuery -->
<script src="extensiones/plugins/jquery/jquery.min.js"></script>
<!-- iCheck -->
<script src="extensiones/plugins/iCheck/icheck.min.js"></script>
<script>
  $(function () {
    $('input').iCheck({
      checkboxClass: 'icheckbox_square-blue',
      radioClass   : 'iradio_square-blue',
      increaseArea : '20%' // optional
    })
  })
 
</script>

