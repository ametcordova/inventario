<?php

 $mysqlDatabaseName =defined('DATABASE')?DATABASE:null;
  if($mysqlDatabaseName!=null){
    //Introduzca aquí la información de su base de datos y el nombre del archivo de copia de seguridad.  
    $mysqlUserName ='root';
    $mysqlPassword =' ';
    $mysqlHostName ='localhost';
    $mysqlExportPath ='respaldo';

    $fecha = date("Ymd-His");

    // Construimos el nombre de archivo SQL Ejemplo: mibase_20170101-081120.sql
    //$respaldo_sql ='bd/'.$mysqlExportPath.'_'.$fecha.'.sql';
    $respaldo_sql =$mysqlExportPath.'_'.$fecha.'.sql';
    $salida_sql =$mysqlExportPath.'_'.$fecha.'.sql';
    $command = "mysqldump --host=$mysqlHostName --user=$mysqlUserName --password=$mysqlPassword $mysqlDatabaseName > $respaldo_sql";

    system($command,$resultado);

        $zip = new ZipArchive(); //Objeto de Libreria ZipArchive

        //Construimos el nombre del archivo ZIP Ejemplo: mibase_20160101-081120.zip
        $salida_zip = 'bd/'.$mysqlExportPath.'_'.$fecha.'.zip';
        $copiar_zip = $mysqlExportPath.'_'.$fecha.'.zip';

        if($zip->open($salida_zip,ZIPARCHIVE::CREATE)===true) { //Creamos y abrimos el archivo ZIP
            $zip->addFile($respaldo_sql); //Agregamos el archivo SQL a ZIP
            $zip->close(); //Cerramos el ZIP
            unlink($respaldo_sql); //Eliminamos el archivo temporal SQL
              //echo $resultado; 
              //header ("Location: $salida_zip"); // Redireccionamos para descargar el Arcivo ZIP
              //header("Location:inicio.php"); // Redireccionamos 
			  unset($_POST['submit']);
        } else {
            echo 'Error'; //Enviamos el mensaje de error

        }

        //$carpeta = 'D:/RESPALDO';
        $carpeta = defined('DRIVEBACKUP')?DRIVEBACKUP:'';
        //$carpeta = 'C:\Users\Jose\Documents\RESPALDO';
        if (!file_exists($carpeta)) {
            mkdir($carpeta, 0777, true);
        }        

        $origen = $salida_zip;

        $destino = $carpeta.'/'.$copiar_zip;

        $resulta = copy($origen, $destino);
        if ($resulta) {
            //echo "Se ha copiado $origen a $destino";
            unlink($origen);
        } else {
            echo "Error copiando $origen a $destino";
        }

        switch($resultado){
        case 0:
        echo 'La base de datos <b>' .$mysqlDatabaseName .'</b> se ha almacenado correctamente en la siguiente ruta '.getcwd().'/' .$mysqlExportPath .'</b>';
        break;
        case 1:
            echo 'Se ha producido un error al exportar <b>' .$mysqlDatabaseName .'</b> a '.getcwd().'/' .$respaldo_sql.'</b>';
        break;
        case 2:
            echo 'Se ha producido un error de exportación, compruebe la siguiente información: <br/><br/><table><tr><td>Nombre de la base de datos MySQL:</td><td><b>' .$mysqlDatabaseName .'</b></td></tr><tr><td>Nombre de usuario MySQL:</td><td><b>' .$mysqlUserName .'</b></td></tr><tr><td>Contraseña MySQL:</td><td><b>NOTSHOWN</b></td></tr><tr><td>Nombre de host MySQL:</td><td><b>' .$mysqlHostName .'</b></td></tr></table>';
        break;
        }	
    }else{
		echo '<script>                
              swal.fire({
                   title: "ERROR",
				   text: "¡No existe base de datos a Respaldar!!. Llame a Soporte Técnico",
                   icon: "error",
                   }).then(function(result){
                    if(result){
                           window.location = "inicio";
                    }
                    });                    
                </script>';		
    }


session_destroy();
session_unset();
echo
 '<script>

	window.location = "ingreso";

 </script>';