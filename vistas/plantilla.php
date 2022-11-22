<?php
 session_start();
?>
<!DOCTYPE html>
<!--
This is a starter template page. Use this page to start your new project from
scratch. This page gets rid of all links and provides the needed markup only.
-->
<html lang="en">
<head>
  <meta charset="utf-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <meta http-equiv="x-ua-compatible" content="ie=edge">

  <title>AdminLTE3 | InV</title>
  
  <link rel="shortcut icon" type="image/x-icon" href="extensiones/dist/img/favicon.ico"/>

  	<script src="extensiones/plugins/Signature/js/jquery.min.js"></script>
	<script src="extensiones/plugins/Signature/js/jquery-ui.min.js"></script>



  <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.2.1/css/bootstrap.min.css" integrity="sha384-GJzZqFGwb1QTTN6wy59ffF1BuGJpLSa9DkKMp0DgiMDm4iYMj70gZWKYbI706tWS" crossorigin="anonymous">

	<!-- Font Unica-One -->
	<link rel="preconnect" href="https://fonts.googleapis.com"> 
	<link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
	<link href="https://fonts.googleapis.com/css2?family=Unica+One&display=swap" rel="stylesheet">
	
  <!-- Font Awesome Icons -->
  <link rel="stylesheet" href="extensiones/plugins/font-awesome/css/font-awesome.min.css">
  
  <!-- IonIcons  -->
  <link rel="stylesheet" href="extensiones/plugins/ionicons/css/ionicons.min.css">
  
  <!-- Theme style -->
  <link rel="stylesheet" href="extensiones/dist/css/adminlte.css?v=03012022">
  <link rel="stylesheet" href="extensiones/dist/css/estilos.css?v=01012022">  
  
  
  <!-- iCheck -->
  <link rel="stylesheet" href="extensiones/plugins/iCheck/square/blue.css">
    
  <!--sweetalert.min.js>-->  
  <script src="extensiones/plugins/sweetalert/sweetalert.min.js"></script>

   <!-- Google Font: Source Sans Pro 
  <link href="https://fonts.googleapis.com/css?family=Source+Sans+Pro:300,400,400i,700" rel="stylesheet"> -->
  <link href="extensiones/dist/css/css.css" rel="stylesheet">

 <!-- datepicker -->
  <link rel="stylesheet" href="extensiones/plugins/datepicker/datepicker3.css">

<!-- daterangepicker -->
  <link rel="stylesheet" href="extensiones/plugins/daterangepicker/daterangepicker-bs3.css">

<link rel="stylesheet" href="//cdnjs.cloudflare.com/ajax/libs/timepicker/1.3.5/jquery.timepicker.min.css">  

<!-- Select2 -->
  <link rel="stylesheet" href="extensiones/plugins/select2/select2.min.css"> 
  <!--  https://github.com/ttskch/select2-bootstrap4-theme -->
  <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/@ttskch/select2-bootstrap4-theme@x.x.x/dist/select2-bootstrap4.min.css">

  <!--<link href="https://cdn.jsdelivr.net/npm/bootstrap@5.0.2/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-EVSTQN3/azprG1Anm3QDgpJLIm9Nao0Yz1ztcQTwFspd3yD65VohhpuuCOmLASjC" crossorigin="anonymous">
  <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.0.2/dist/js/bootstrap.bundle.min.js" integrity="sha384-MrcW6ZMFYlzcLA8Nl+NtUVF0sA7MsXsP1UyJoMp4YLEuNSfAP+JcXn/tWtIaxVXM" crossorigin="anonymous"></script> -->

  <link type="text/css" href="https://ajax.googleapis.com/ajax/libs/jqueryui/1.12.1/themes/south-street/jquery-ui.css" rel="stylesheet"> 
<!--  <link rel="stylesheet" href="extensiones/plugins/jquery-ui-1.12.1/jquery-ui.css">   -->
<!-- DataTables -->
<link rel="stylesheet" href="extensiones/datatables/datatables.css?v=25082021">   


<!-- DROPZONE -->
<script src="extensiones/dropzone/dropzone.js"></script>
<link rel="stylesheet" href="extensiones/dropzone/dropzone.css">  

<!-- ALERTAS NOTIE -->
<link rel="stylesheet" type="text/css" href="https://unpkg.com/notie/dist/notie.min.css">
<!-- ALERTAS ohSnap -->
<link rel="stylesheet" type="text/css" href="extensiones/plugins/ohSnap/ohsnap.css">

<!-- ALERTAS ohSnap -->
<link rel="stylesheet" type="text/css" href="extensiones/plugins/waitme/waitMe.min.css">

<!-- https://getdatepicker.com/5-4/Usage/ -->
<link rel="stylesheet" href="extensiones/plugins/tempusdominus-bootstrap4/tempusdominus-bootstrap-4.min.css" />

<link href="extensiones/plugins/Signature/css/jquery.signature.css" rel="stylesheet">

<script src="extensiones/plugins/rxjs/rxjs.umd.min.js"></script>

</head>

	<!--==== CUERPO DEL DOCUMENTO MANDA A LLAMAR fechahora y networkStatus===-->
	<body class="hold-transition sidebar-collapse sidebar-mini login-page" onLoad="fechahora(); networkStatus();">

	<?php
	if(isset($_SESSION["iniciarSesion"]) && $_SESSION["iniciarSesion"]=="ok"){
		
		echo '<div class="wrapper">';
		
		// ======== cabecera ========
		include_once "modulos/header.php";
		
		// ======== menu ========
		include_once "modulos/menu.php";

		if(isset($_GET["ruta"])){
			if($_GET["ruta"]=="inicio" ||
				$_GET["ruta"]=="dash" ||
				$_GET["ruta"]=="usuarios" ||
				$_GET["ruta"]=="template-inicial" ||
				$_GET["ruta"]=="salidasalmacen" ||
				$_GET["ruta"]=="gestion-empresas" ||
				$_GET["ruta"]=="entradasalmacen" ||
				$_GET["ruta"]=="adminoservicio" ||
				$_GET["ruta"]=="devolucion-tecnicos" ||
				$_GET["ruta"]=="control-depositos" ||
				$_GET["ruta"]=="osvilla" ||
				$_GET["ruta"]=="clientes" ||
				$_GET["ruta"]=="tecnicos" ||
				$_GET["ruta"]=="proveedores" ||
				$_GET["ruta"]=="categorias" ||
				$_GET["ruta"]=="medidas" ||
				$_GET["ruta"]=="productos" ||
				$_GET["ruta"]=="empresa" ||
				$_GET["ruta"]=="reporteportecnico" ||
				$_GET["ruta"]=="crear-almacen" ||
				$_GET["ruta"]=="almacen" ||
				$_GET["ruta"]=="adminalmacenes" ||
				$_GET["ruta"]=="adminsalidas" ||
				$_GET["ruta"]=="reporteinventario" ||
				$_GET["ruta"]=="respaldo" ||
				$_GET["ruta"]=="control-facturas" ||
				$_GET["ruta"]=="facturaingreso" ||
				$_GET["ruta"]=="control-viaticos" ||
				$_GET["ruta"]=="ajusteinventario" ||
				$_GET["ruta"]=="adminseries" ||
				$_GET["ruta"]=="permisos" ||
				$_GET["ruta"]=="repositorio" ||
				$_GET["ruta"]=="download" ||
				$_GET["ruta"]=="sistema" ||
				$_GET["ruta"]=="adminquejas" ||
				$_GET["ruta"]=="tablero" ||
				$_GET["ruta"]=="salir"){
				
				include_once "modulos/".$_GET["ruta"].".php";

			}else{
				include "modulos/404.php";
			}
		}else{
			include_once "modulos/inicio.php";
		}
			
			/* Main Footer */
			require_once 'modulos/footer.php';
		
		echo '</div>';
		/*-- fin de wrapper --*/
	}else{
		include 'modulos/login.php';
		require_once 'modulos/footer.php';
	}	
?>
<!-- AQUI SE VINCULAN LOS ARCHIVOS JS -->
<script src="vistas/js/funciones.js?v=04092020"></script>                  
<script src="vistas/js/plantilla.js?v=10052022"></script>
<script src="vistas/js/usuario.js?v=091120221643"></script>
<script src="vistas/js/categorias.js?v=02092020"></script>
<script src="vistas/js/medidas.js?v=02092020"></script>
<script src="vistas/js/proveedores.js?v=02092020"></script>
<script src="vistas/js/productos.js?v=01122020"></script>
<script src="vistas/js/devolucion-tecnicos.js?v=02092020"></script>
<script src="vistas/js/entradas.js?v=02092020"></script>
<script src="vistas/js/salidas.js?v=02092020"></script>
<script src="vistas/js/crear-almacen.js?v=02092020"></script>
<script src="vistas/js/adminalmacenes.js?v=01122020"></script>  
<script src="vistas/js/adminsalidas.js?v=02092020"></script> 
<script src="vistas/js/reporteinventario.js?v=02092020"></script> 
<script src="vistas/js/osvilla.js?v=02092020"></script> 
<script src="vistas/js/adminseries.js?v=02092020"></script>
<script src="vistas/js/adminoservicio.js?v=031120220104"></script>

<!--<script defer src="https://cdn.jsdelivr.net/npm/axios/dist/axios.min.js"></script>


