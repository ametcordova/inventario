<?php
session_start();
require_once "../controladores/salidas.controlador.php";
require_once "../modelos/salidas.modelo.php";

require_once "../controladores/control-presupuesto.controlador.php";
require_once "../modelos/control-presupuesto.modelo.php";

/*=============================================
	VALIDA QUE NUMERO DE SALIDA NO SE REPITA
=============================================*/	 

switch ($_GET["op"]){

    case 'ultnumdeventa':
        /*=============================================
            ASIGNAR NUMERO DE SALIDA
        =============================================*/	 
        if(isset( $_GET["numConsecutivo"])){

            $item = "num_salida";
            $valor = "";

            $respuesta = ControladorSalidas::ctrAsignarNumSalida($item, $valor);

            echo $respuesta ? $respuesta[0] : 0;

        }
    break;    

    case 'querydeproductos':
        try{      
            if(isset($_POST["idProducto"])){
                $tabla="productos";
                $item="id";
                $valor=$_POST["idProducto"];
                $estado=1;

                $respuesta = ControladorSalidas::ctrQuerydeProductos($tabla, $item, $valor, $estado);

                echo json_encode($respuesta);
                //echo $respuesta;
            }else{
                echo json_encode("Error");
            };
        } catch (Exception $e) {
            echo "Failed: " . $e->getMessage();
        }

     break;

     case 'traertotalventas':
        if(isset($_POST["numcaja"])){
            $tabla="hist_salidas";
            $item=$_POST["fechasalida"];
            $fechacutvta=null;
            $valor=$_POST["numcaja"];
            $cerrado=$_POST["cerrado"];

            $respuesta = ControladorSalidas::ctrSumaTotalVentas($tabla, $item, $valor, $cerrado, $fechacutvta);

            echo json_encode($respuesta);

        };
     break;

     case 'traertotalenvases':
        if(isset($_POST["numcaja"])){
            $tabla="hist_salidas";
            $item=$_POST["fechasalida"];
            $fechacutvta=null;
            $valor=$_POST["numcaja"];
            $cerrado=$_POST["cerrado"];

            $respuesta = ControladorSalidas::ctrSumTotVtasEnv($tabla, $item, $valor, $cerrado, $fechacutvta);

            echo json_encode($respuesta);

        };
     break;
     
     case 'traertotalservicios':
        if(isset($_POST["numcaja"])){
            $tabla="hist_salidas";
            $item=$_POST["fechasalida"];
            $fechacutvta=null;
            $valor=$_POST["numcaja"];
            $cerrado=$_POST["cerrado"];

            $respuesta = ControladorSalidas::ctrSumTotVtasServ($tabla, $item, $valor, $cerrado, $fechacutvta);

            echo json_encode($respuesta);

        };
     break;

     case 'traertotalotros':
        if(isset($_POST["numcaja"])){
            $tabla="hist_salidas";
            $item=$_POST["fechasalida"];
            $fechacutvta=null;
            $valor=$_POST["numcaja"];
            $cerrado=$_POST["cerrado"];

            $respuesta = ControladorSalidas::ctrSumTotVtasOtros($tabla, $item, $valor, $cerrado, $fechacutvta);

            echo json_encode($respuesta);

        };
     break;

     case 'traertotalacredito':
        if(isset($_POST["numcaja"])){
            $tabla="hist_salidas";
            $item=$_POST["fechasalida"];
            $fechacutvta=$_POST["fechaactual"];
            $valor=$_POST["numcaja"];
            $cerrado=$_POST["cerrado"];

            $respuesta = ControladorSalidas::ctrSumTotVtasCred($tabla, $item, $valor, $cerrado, $fechacutvta);

            $arr = array('error' => $_POST["numcaja"]);

            if($respuesta){
                echo json_encode($respuesta);
            }else{
                echo json_encode($arr);                
            }
        }else{
            $arr = array('error' => 'fallado');
            echo json_encode($arr);                
        };
     break;

     case 'importecajachica':
        if(isset($_POST["item"])){
            $item =$_POST["item"] ;
            $valor = $_POST["iddeCaja"];
            $cerrado = $_POST["cerrado"];
            $fecha_actual = $_POST["fechaactual"];

            $respuesta = ControladorPresupuesto::ctrImporteCajaChica($item, $valor, $cerrado, $fecha_actual);

			echo json_encode($respuesta);

        };
 	break;


     case 'ingresoegreso':
        if(isset($_POST["item"])){
            $item =$_POST["item"] ;
            $valor = $_POST["iddeCaja"];
            $cerrado = $_POST["cerrado"];

            $respuesta = ControladorPresupuesto::ctringresoegreso($item, $valor, $cerrado);

			if($respuesta==null){
				echo json_encode(null);
			}else{
				echo json_encode($respuesta);
			}
        };
 	break;


     case 'buscararticulo':
        if(isset( $_GET["almacen"])){
            require_once "../controladores/almacen.controlador.php";
            require_once "../modelos/almacen.modelo.php";
        
            $campo = "id_producto";
            $valor =$_GET['idProducto'];
            $tabla = trim(strtolower($_GET['almacen']));
        
            $respuesta = ControladorAlmacen::ctrMostrarAlmacen($tabla, $campo, $valor);
            
            echo json_encode($respuesta);
            
        }
    break;  

    case 'cerrarcajavta':
    //CIERRE DE CAJA DE VENTA DEL DIA
        if(isset( $_GET["cierre"])){
            $tabla="hist_salidas";

            if(isset($_GET["idcaja"])){
                $id_caja=$_GET["idcaja"];
            }else{
                $id_caja=$_SESSION['idcaja'];
            };

            if(isset($_GET["idcorte"])){
                $id_corte=$_GET["idcorte"];
            }else{
                $id_corte=null;
            };

            if(isset($_GET["idfechaventa"])){
                $id_fecha=$_GET["idfechaventa"];
            }else{
                $id_fecha=null;
            };
            
            if($id_corte==null && $id_fecha==null){
                $respuesta = ControladorSalidas::ctrCierreDia($tabla, $id_caja, $id_corte, $id_fecha);
                echo $respuesta ? "Hecho" : 0;
            }else{
                $respuesta = ControladorSalidas::ctrCierreForzoso($tabla, $id_caja, $id_corte, $id_fecha);
                echo $respuesta ? "Hecho" : 0;
            };
        }
    break;  

}

    