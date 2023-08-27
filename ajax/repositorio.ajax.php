<?php
session_start();
error_reporting(E_ALL);
ini_set('display_errors', 1);
require_once "../controladores/repositorio.controlador.php";
require_once "../modelos/repositorio.modelo.php";

require_once "../controladores/permisos.controlador.php";
require_once "../modelos/permisos.modelo.php";
require_once '../funciones/funciones.php';

switch ($_GET["op"]){
        
	
	case 'listsfiles':

		if(!isset($_GET["id_user"])){
            break;
        }

		$tabla="usuarios";
		$module="pctfacts";
		$campo="administracion";
		$acceso=accesomodulo($tabla, $_SESSION['id'], $module, $campo);    
	
		$item = "user_id";
    	$valor = $_GET["id_user"];
    	//$orden = "id";
		$ispublic="1";

  		$storefiles = ControladorRepositorio::ctrListsFiles($item, $valor, $ispublic);

        //var_dump($storefiles);
    
  		if(is_countable((array)$storefiles) && count((array)$storefiles) <1){
  			echo '{"data": []}';           //arreglar, checar como va
		  	return;
  		}    

           foreach($storefiles as $key => $value){
			
			//if(getAccess($acceso, ACCESS_SELECT)){
				$botonLock=$value["is_public"]==1?"<button class='btn btn-warning btn-sm px-1 py-1 btnCambiaStatus' data-id-file='".$value['id']."' data-estado='".$value['is_public']."' title='Archivo para todos los usuarios'><i class='fa fa-unlock'></i> P&uacute;blico</button>":
				"<button class='btn btn-success px-1 py-1 btn-sm btnCambiaStatus' data-id-file='".$value['id']."' data-estado='".$value['is_public']."' title='Solo puede ver el usuario que subio archivo'><i class='fa fa-lock'></i> Privado</button>";
			//}

			//$file=$value['nombrearchivo'];
			$file=urlencode($value['nombrearchivo']);
			$rutaactual=substr($value["ruta"], 1);
			$enlace=$rutaactual.$file;

			if (file_exists("../".$rutaactual.$value["nombrearchivo"])) {
				$tamano=filesize("../".$value["ruta"].$value["nombrearchivo"]);
				$peso=formatBytes($tamano, 2);
			} else {
				$peso='0';
			}
			
			$boton1=getAccess($acceso, ACCESS_EDIT)?"<button class='btn btn-circle btn-primary btn-sm px-1 py-1 btnEditarFactura' idFile='".$value['id']."' title='Editar datos'><i class='fa fa-pencil'></i></button> ":"";
			$boton2=getAccess($acceso, ACCESS_PRINTER)?"<button class='btn btn-circle btn-info btn-sm px-1 py-1 btnViewFile' data-description='".$value['descripcion']."' data-viewfile='".$value['nombrearchivo']."' data-viewruta='".$value['ruta']."' data-toggle='modal' data-target='#modalViewFile' title='Visualizar archivo '><i class='fa fa-eye'></i></button> ":"";
    	  	$boton3=getAccess($acceso, ACCESS_DELETE)?"<button class='btn btn-circle btn-danger btn-sm px-1 py-1 btnDeleteFile' data-deletefile='".$value['id']."' data-namefile='".$value['nombrearchivo']."' title='Borrar archivo'><i class='fa fa-trash'></i></button> ":"";
    	  	$boton4=getAccess($acceso, ACCESS_VIEW)?"<a class='btn btn-circle btn-dark btn-sm px-1' href='".urldecode($enlace)."' role='button' title='Descargar archivo' download target='_blank'><i class='fa fa-download'></i></a>":"";
			$botones = $boton1.$boton2.$boton3.$boton4;
			
			//$rutaActual = getcwd();
			//$rutaActualModificada = $rutaActual.DIRECTORY_SEPARATOR.$value["ruta"];
			//$enlace="<a href='vistas/modulos/download.php?filename=$file&ruta='>$file</a>";
			$tipo = pathinfo($value["nombrearchivo"], PATHINFO_EXTENSION);


		  	$data[]=array(
                  $value["id"],
				  substr($value["descripcion"],0,75),
				  substr($value["nombrearchivo"],0,40),
				  $value["usuario"],
                  $botonLock,
			      $tipo,
                  $peso,
                  $botones
           );
        }
    
    //var_dump($data);
    
        $results = array(
					"sEcho"=>1, //Informacion para el datatables
					"iTotalRecords"=>count($data), //enviamos el total registros al datatable
					"iTotalDisplayRecords"=>count($data), //enviamos el total registros a visualizar
					"aaData"=>$data);
    echo json_encode($results);        
            
    break;
        

	case 'UpLoadFiles':

        if(isset($_POST["nombrearchivo"])){
            $descripcion=strtoupper($_POST["descripcion"]);
            $nombrearchivo=$_POST["nombrearchivo"];
            //$ruta=$_POST["ruta"];
            $is_public=$_POST['is_public'];
            $ultusuario=$_SESSION['id'];
            
			$ruta="/archivos/repositorio/".$_SESSION['usuario']."/";

            $respuesta = ControladorRepositorio::ctrUpLoadFiles($descripcion, $nombrearchivo, $ruta, $is_public, $ultusuario);
            
            echo json_encode($respuesta);    

        }else{
            
            $respuesta = array("error" =>$nombre_archivo);;
            echo json_encode($respuesta);
        }
        
     break;

     case 'delFileRep':

        if(isset($_GET["iddelete"]) && !empty($_GET["iddelete"])){
            $stat="error";
            $tabla="repositorio";
            $campo="id";

            $respuesta = ControladorRepositorio::ctrDelFileRep($tabla, $_GET["iddelete"], $campo);

            echo json_encode($respuesta);    
        }else{
			$respuesta = array("respuesta:" =>$stat);
			echo json_encode($respuesta);
        }
    break;     

	case 'ChangeStatFile':
		
		if(isset($_GET["dataidfile"]) && !empty($_GET["dataidfile"]) ){ 
			$tabla="repositorio";
			$campo="id";
			$getDataFile=ModeloRepositorio::mdlDelFileRep($tabla, $_GET["dataidfile"], $campo, $file=true);

			$sesion=intval($_SESSION['id']);
			$user_id=intval($getDataFile['user_id']);

			if($sesion===$user_id && (intval($_GET["dataestado"])==0 || intval($_GET["dataestado"]==1))){

				$dataestado=!intval($_GET["dataestado"]);		//segun estado, lo invierte
				$rsp=ModeloRepositorio::mdlChangeStatFile($tabla, $_GET["dataidfile"], $campo, $dataestado);
				echo json_encode($rsp);
			}else{

				$respuesta = array("status" =>400, "tipo"=>"No es posible cambiar estado. No eres propietario de este archivo");
				echo json_encode($respuesta);
			}

		}else{
			$respuesta = array("status" =>401, "tipo"=>"Error indefinido.");
			echo json_encode($respuesta);
		}

    break;     

}  //FIN DE SWITCH
