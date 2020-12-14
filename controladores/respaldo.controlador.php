<?php

class ControladorRespaldo{

/*=============================================
CREAR RESPALDO: PARA USAR mysqldump HAY QUE AÑADIR 
EN LAS VARIABLES DE ENTORNO DE WINDOWS EL PATH 
DONDE SE ENCUENTRA mysqldump
=============================================*/

public function ctrCrearRespaldo(){

if(isset($_POST['submit'])){
    //Introduzca aquí la información de su base de datos y el nombre del archivo de copia de seguridad.
    $mysqlDatabaseName ='inventario';
    $mysqlUserName ='root';
    $mysqlPassword =' ';
    $mysqlHostName ='localhost';
    $mysqlExportPath ='respaldo';

    $fecha = date("Ymd-His");

    // Construimos el nombre de archivo SQL Ejemplo: mibase_20170101-081120.sql
    $respaldo_sql ='bd/'.$mysqlExportPath.'_'.$fecha.'.sql';
    $salida_sql =$mysqlExportPath.'_'.$fecha.'.sql';
    $command = "mysqldump --host=$mysqlHostName --user=$mysqlUserName --password=$mysqlPassword $mysqlDatabaseName > $respaldo_sql";

    system($command,$resultado);

        $zip = new ZipArchive(); //Objeto de Libreria ZipArchive

        //Construimos el nombre del archivo ZIP Ejemplo: mibase_20160101-081120.zip
        $salida_zip = 'bd/'.$mysqlExportPath.'_'.$fecha.'.zip';

        if($zip->open($salida_zip,ZIPARCHIVE::CREATE)===true) { //Creamos y abrimos el archivo ZIP
            $zip->addFile($respaldo_sql); //Agregamos el archivo SQL a ZIP
            $zip->close(); //Cerramos el ZIP
            unlink($respaldo_sql); //Eliminamos el archivo temporal SQL
            echo "codigo:".$resultado.'<br>'; 
              //header ("Location: $salida_zip"); // Redireccionamos para descargar el Arcivo ZIP
			  //unset($_POST['submit']);
              //header ("Location:inicio.php"); // Redireccionamos para descargar el Arcivo ZIP
        } else {
            echo '<p style="color:white;">Error</p>'; //Enviamos el mensaje de error

        }

        switch($resultado){
        case 0:
        echo '<p style="color:white;">La base de datos <b>' .$mysqlDatabaseName .'</b> se ha almacenado correctamente en la siguiente ruta '.getcwd().'/' .$mysqlExportPath .'</b></p>';
		break;
		usleep(3000000);
        case 1:
        echo '<p style="color:white;">Se ha producido un error al exportar <b>' .$mysqlDatabaseName .'</b> a '.getcwd().'/' .$respaldo_sql.'</b></p>';
		sleep(3);
        break;
        case 2:
        echo 'Se ha producido un error de exportación, compruebe la siguiente información: <br/><br/><table><tr><td>Nombre de la base de datos MySQL:</td><td><b>' .$mysqlDatabaseName .'</b></td></tr><tr><td>Nombre de usuario MySQL:</td><td><b>' .$mysqlUserName .'</b></td></tr><tr><td>Contraseña MySQL:</td><td><b>NOTSHOWN</b></td></tr><tr><td>Nombre de host MySQL:</td><td><b>' .$mysqlHostName .'</b></td></tr></table>';
		sleep(3);
        break;
        }	

    }
    
}


    
    
    
}   //fin de la clase


//añadir en variables de entorno de windows en el path C:\wamp64\bin\mysql\mysql5.7.26\bin