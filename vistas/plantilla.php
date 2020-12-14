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
  <meta name="viewport" content="width=device-width, initial-scale=1">
  <meta http-equiv="x-ua-compatible" content="ie=edge">

  <title>AdminLTE3 | InV</title>
  
  <link rel="shortcut icon" type="image/x-icon" href="extensiones/dist/img/favicon.ico"/>

  <!-- Font Awesome Icons -->
  <link rel="stylesheet" href="extensiones/plugins/font-awesome/css/font-awesome.min.css">
  
  <!-- IonIcons  -->
  <link rel="stylesheet" href="extensiones/plugins/ionicons/css/ionicons.min.css">
  
  <!-- Theme style -->
  <link rel="stylesheet" href="extensiones/dist/css/adminlte.css?v=01092200">
  <link rel="stylesheet" href="extensiones/dist/css/estilos.css?v=010920">  
  
  
  <!-- iCheck -->
  <link rel="stylesheet" href="extensiones/plugins/iCheck/square/blue.css">
    
  <!-- DataTables -->
  <!-- <link rel="stylesheet" href="extensiones/plugins/datatables/dataTables.bootstrap4.css">  
  <link rel="stylesheet" href="extensiones/plugins/datatables/jquery.dataTables.css">  
  <link rel="stylesheet" href="extensiones/plugins/datatables/buttons.dataTables.min.css">  
  <link rel="stylesheet" href="extensiones/plugins/datatables/extensions/Responsive/css/dataTables.responsive.css"> -->
  
  <!--sweetalert.min.js>-->  
  <script src="extensiones/plugins/sweetalert/sweetalert.min.js"></script>

  <!-- Google Font: Source Sans Pro 
  <link href="https://fonts.googleapis.com/css?family=Source+Sans+Pro:300,400,400i,700" rel="stylesheet"> -->
  <link href="extensiones/dist/css/css.css" rel="stylesheet">


 <!-- datepicker -->
  <link rel="stylesheet" href="extensiones/plugins/datepicker/datepicker3.css">

<!-- daterangepicker -->
  <link rel="stylesheet" href="extensiones/plugins/daterangepicker/daterangepicker-bs3.css">
  
<!-- Select2 -->
  <link rel="stylesheet" href="extensiones/plugins/select2/select2.min.css">  
  
  <link rel="stylesheet" href="extensiones/plugins/jquery-ui-1.12.1/jquery-ui.css">  
  
<!-- DataTables -->
<link rel="stylesheet" href="extensiones/datatables/datatables.min.css?v=250820">   

<!-- DROPZONE -->
<script src="extensiones/dropzone/dropzone.js"></script>
<link rel="stylesheet" href="extensiones/dropzone/dropzone.css">  


</head>

	<!--==== CUERPO DEL DOCUMENTO ===-->
	<body class="hold-transition sidebar-collapse sidebar-mini login-page" onLoad="show5();">



	<?php
	if(isset($_SESSION["iniciarSesion"]) && $_SESSION["iniciarSesion"]=="ok"){
		
	echo '<div class="wrapper">';
	  
	  // ======== cabecera ========
	  include "modulos/header.php";
	  
	// ======== menu ========
	  include "modulos/menu.php";
	   if(isset($_GET["ruta"])){
		if($_GET["ruta"]=="inicio" ||
		   $_GET["ruta"]=="usuarios" ||
		   $_GET["ruta"]=="salidas" ||
		   $_GET["ruta"]=="salidasalmacen" ||
		   $_GET["ruta"]=="entradas" ||
		   $_GET["ruta"]=="entradasalmacen" ||
		   $_GET["ruta"]=="devolucion-tecnicos" ||
		   $_GET["ruta"]=="osvilla" ||
		   $_GET["ruta"]=="clientes" ||
		   $_GET["ruta"]=="tecnicos" ||
		   $_GET["ruta"]=="proveedores" ||
		   $_GET["ruta"]=="categorias" ||
		   $_GET["ruta"]=="medidas" ||
		   $_GET["ruta"]=="productos" ||
		   $_GET["ruta"]=="empresa" ||
		   $_GET["ruta"]=="modalAviso" ||
		   $_GET["ruta"]=="crear-almacen" ||
		   $_GET["ruta"]=="almacen" ||
		   $_GET["ruta"]=="adminalmacenes" ||
		   $_GET["ruta"]=="adminsalidas" ||
		   $_GET["ruta"]=="reporteinventario" ||
		   $_GET["ruta"]=="respaldo" ||
		   $_GET["ruta"]=="control-facturas" ||
		   $_GET["ruta"]=="control-viaticos" ||
		   $_GET["ruta"]=="ajusteinventario" ||
		   $_GET["ruta"]=="adminseries" ||
		   $_GET["ruta"]=="permisos" ||
		   $_GET["ruta"]=="salir"){
			include "modulos/".$_GET["ruta"].".php";
		}else{
			include "modulos/404.php";
		}
	   }else{
		   include "modulos/inicio.php";
	   }
		
		/* Main Footer */
		include 'modulos/footer.php';
	
	echo '</div>';
	/*-- fin de wrapper --*/
}else{
	include 'modulos/login.php';
}	
?>
<!-- AQUI SE VINCULAN LOS ARCHIVOS JS -->
<script src="vistas/js/plantilla.js?v=02092020"></script>
<script src="vistas/js/usuario.js?v=02092020"></script>
<script src="vistas/js/categorias.js?v=02092020"></script>
<script src="vistas/js/medidas.js?v=02092020"></script>
<script src="vistas/js/clientes.js?v=02092020"></script>
<script src="vistas/js/proveedores.js?v=02092020"></script>
<script src="vistas/js/productos.js?v=01122020"></script>
<script src="vistas/js/devolucion-tecnicos.js?v=02092020"></script>
<script src="vistas/js/entradas.js?v=02092020"></script>
<script src="vistas/js/salidas.js?v=02092020"></script>
<script src="vistas/js/crear-almacen.js?v=02092020"></script>
<script src="vistas/js/adminalmacenes.js?v=01122020"></script>  
<script src="vistas/js/tecnicos.js?v=03092020"></script>  
<script src="vistas/js/adminsalidas.js?v=02092020"></script> 
<script src="vistas/js/reporteinventario.js?v=02092020"></script> 
<script src="vistas/js/osvilla.js?v=02092020"></script> 
<script src="vistas/js/control-facturas.js?v=29092020"></script> 
<script src="vistas/js/adminseries.js?v=02092020"></script>
<script defer src="vistas/js/funciones.js?v=04092020"></script>                  
<!--<script defer src="https://cdn.jsdelivr.net/npm/axios/dist/axios.min.js"></script>
 <script src="vistas/js/control-viaticos.js"></script>  -->

