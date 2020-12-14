<?php
session_start();
date_default_timezone_set('America/Mexico_City');
include "../config/parametros.php";
switch ($_GET["op"]){

    case 'listarrespaldos':
        $folder = defined('DRIVEBACKUP')?DRIVEBACKUP:'';
        //chdir("D:\RESPALDO");
        $carpeta=str_replace('/','\\',$folder);
        chdir($carpeta);
        $dir = getcwd();
        if ($directorio = opendir($dir)) {      //Abrimos el directorio

            $archivos = array();
            $carpetas = array();
    
            //$files = array_diff(scandir($path), array('.', '..'));
            //Carpetas y Archivos a excluir
            $excluir = array('.', '..', 'index.php', 'favicon.ico','folder.png','file.png','img','.dropbox');
    
            //while ($f = readdir($directorio)) {
            while (false !== ($files = readdir($directorio))) {
                if (is_dir("$dir/$files") && !in_array($files, $excluir)) {
                    $carpetas[] = $files;
                } else if (!in_array($files, $excluir)){
                    //No es una carpeta, por ende lo mandamos a archivos
                    $archivos[] = $files;
                }
            }
            closedir($directorio);
    
            sort($carpetas,SORT_NATURAL | SORT_FLAG_CASE);
            //sort($archivos,SORT_NATURAL | SORT_FLAG_CASE);

        }else{
            echo 'No Existe la carpeta';
        }

  		if(count($archivos) == 0){
  			echo '{"data": []}';           //arreglar, checar como va
		  	return;
  		}    
            $i = 1;
            foreach($archivos as $key => $value){

                $fechaultmod=date ("d-m-Y h:i:s", filemtime($value));

                $sizefile=round((filesize($value)/1024),2).' KBytes';

                
                $chek='<td><input type="checkbox" class="case"  id="check'.$i++.'" namefile="'.$value.'"></td>';

                $botones = "<button class='btn btn-danger btn-sm btnEliminarRespaldo' idFile='".$value."' title='Eliminar'><i class='fa fa-trash'></i></button>"; 

                $data[]=array(
                    "0"=>($key+1),
                    "1"=>$chek,
                    "2"=>$value,
                    "3"=>$fechaultmod,
                    "4"=>$sizefile,
                    "5"=>$botones,
                );

            }
    
        $results = array(
            "sEcho"=>1, //Información para el datatables
            "iTotalRecords"=>count($data), //enviamos el total registros al datatable
            "iTotalDisplayRecords"=>count($data), //enviamos el total registros a visualizar
            "aaData"=>$data);
        echo json_encode($results);        
    
    break;

    case 'eliminarrespaldo':
        $folder = defined('DRIVEBACKUP')?DRIVEBACKUP:'';
        $carpeta=str_replace('/','\\',$folder);
        chdir($carpeta);  //moverse a la carpeta 
        
            $respuesta=false;

            if(isset($_POST["idFile"])){
                if(!unlink($_POST["idFile"])){
                    //si no puede ser muestro un mensaje
                    echo "no se pudo borrar el archivo :".$_POST["idFile"];
                    $respuesta=false;
                }
                //$respuesta = array('success' => true);
                $respuesta =true;
             }
            
            echo json_encode($respuesta);

 	break;

    case 'eliminararchivos':
        $folder = defined('DRIVEBACKUP')?DRIVEBACKUP:'';
        $carpeta=str_replace('/','\\',$folder);
        chdir($carpeta);  //moverse a la carpeta 

            $wfile = explode(",",$_POST["nameFiles"]);
            $contador=contar_palabras($_POST["nameFiles"]);
            $respuesta=false;

            if(isset($_POST["nameFiles"])){
              for($i=0;$i<$contador;$i++) { 

                if(!unlink($wfile[$i])){
                    //si no puede ser muestro un mensaje
                    echo "no se pudo borrar el archivo :".$_POST["nameFiles"];
                    $respuesta=false;
                }
                //$respuesta = array('success' => true);
                $respuesta =true;
             }
            }
            echo json_encode($respuesta);

 	break;

        
}  //FIN DE SWITCH

//funcion para obtener el numero de palabras 
function contar_palabras($cadena){
    return count(explode(",", $cadena));
}