<?php
require_once 'controladores/plantilla.controlador.php';
require_once 'controladores/categorias.controlador.php';
require_once 'controladores/clientes.controlador.php';
require_once 'controladores/proveedores.controlador.php';
require_once 'controladores/salidasalmacen.controlador.php';
require_once 'controladores/entradas.controlador.php';
require_once 'controladores/entradasalmacen.controlador.php';
require_once 'controladores/usuarios.controlador.php';
require_once 'controladores/medidas.controlador.php';
require_once 'controladores/productos.controlador.php';
require_once 'controladores/permisos.controlador.php';
require_once 'controladores/crear-almacen.controlador.php';
require_once 'controladores/entradas.controlador.php';
require_once 'controladores/tecnicos.controlador.php';
require_once 'controladores/respaldo.controlador.php';
require_once 'controladores/ajusteinventario.controlador.php';
//require_once 'controladores/control-depositos.controlador.php';


require_once 'modelos/salidas.modelo.php';
require_once 'modelos/usuarios.modelo.php';
require_once 'modelos/categorias.modelo.php';
require_once 'modelos/medidas.modelo.php';
require_once 'modelos/clientes.modelo.php';
require_once 'modelos/proveedores.modelo.php';
require_once 'modelos/productos.modelo.php';
require_once 'modelos/permisos.modelo.php';
require_once 'modelos/crear-almacen.modelo.php';
require_once 'modelos/salidasalmacen.modelo.php';
require_once 'modelos/entradasalmacen.modelo.php';
require_once 'modelos/tecnicos.modelo.php';
require_once 'modelos/ajusteinventario.modelo.php';
//require_once 'modelos/control-depositos.modelo.php';


$plantilla=new ControladorPlantilla();

$plantilla->ctrPlantilla();